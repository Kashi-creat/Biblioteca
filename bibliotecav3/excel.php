<?php
include 'includes/conexion.php'; 
include 'includes/login_check.php';
include 'includes/rol_check.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// ---- IMPORTAR EXCEL (Docente) ----
if (isset($_POST['importar'])) {
    requireRole(['docente']);
    $archivo = $_FILES['archivo_excel']['tmp_name'];

    if ($archivo) {
        $spreadsheet = IOFactory::load($archivo);
        $hoja = $spreadsheet->getActiveSheet();
        $filas = $hoja->toArray();

        foreach ($filas as $i => $fila) {
            if ($i == 0) continue; // saltar encabezado

            $titulo = $conn->real_escape_string($fila[0] ?? '');
            $autor = $conn->real_escape_string($fila[1] ?? '');
            $editorial = $conn->real_escape_string($fila[2] ?? '');
            $anio = (int)$fila[3] ?? 0;
            $edicion = $conn->real_escape_string($fila[4] ?? '');
            $area = $conn->real_escape_string($fila[5] ?? '');
            $isbn = $conn->real_escape_string($fila[6] ?? '');
            $codigo = $conn->real_escape_string($fila[7] ?? '');
            $estado = $conn->real_escape_string($fila[8] ?? '');
            $observaciones = $conn->real_escape_string($fila[9] ?? '');

            if ($titulo == "") continue;

            // Verificar o insertar autor
            $res = $conn->query("SELECT id_autor FROM autor WHERE nombre_autor='$autor'");
            if ($res->num_rows > 0) {
                $id_autor = $res->fetch_assoc()['id_autor'];
            } else {
                $conn->query("INSERT INTO autor (nombre_autor) VALUES ('$autor')");
                if ($conn->error) {
                    die("Error insertando autor '$autor': " . $conn->error);
                }
                $id_autor = $conn->insert_id;
            }
            // Verificar o insertar editorial
            $res = $conn->query("SELECT id_editorial FROM editorial WHERE nombre_editorial='$editorial'");
            $id_editorial = ($res->num_rows > 0)
                ? $res->fetch_assoc()['id_editorial']
                : ($conn->query("INSERT INTO editorial (nombre_editorial) VALUES ('$editorial')") && $conn->insert_id);
            if (!$id_editorial || $id_editorial == 0) {
                die("Error: no se pudo obtener o insertar editorial para '$editorial'");
            }

            // Verificar o insertar área temática
            $res = $conn->query("SELECT id_area_tematica FROM area_tematica WHERE nombre_area='$area'");
            $id_area = ($res->num_rows > 0)
                ? $res->fetch_assoc()['id_area_tematica']
                : ($conn->query("INSERT INTO area_tematica (nombre_area) VALUES ('$area')") && $conn->insert_id);
            if (!$id_area || $id_area == 0) {
                die("Error: no se pudo obtener o insertar temática para '$area'");
            }
            
            // Insertar libro
            $conn->query("INSERT INTO libro (titulo, ano_publicacion, id_editorial, id_area_tematica, edicion, ISBN, codigo_clasificacion, estado, observaciones) 
                        VALUES ('$titulo', $anio, $id_editorial, $id_area, '$edicion', '$isbn', '$codigo', '$estado', '$observaciones')");
            $id_libro = $conn->insert_id;

            // Insertar relación libro-autor
            $conn->query("INSERT INTO libro_autor (id_libro, id_autor) VALUES ($id_libro, $id_autor)");
        }

        header("Location: index.php?view=libros&msg=importacion_exitosa");
        exit;
    }
}

// ---- EXPORTAR EXCEL (Docente) ----
if (isset($_POST['exportar'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $sheet->setCellValue('A1', 'Título');
    $sheet->setCellValue('B1', 'Autor');
    $sheet->setCellValue('C1', 'Editorial');
    $sheet->setCellValue('D1', 'Año');
    $sheet->setCellValue('E1', 'Edicion');
    $sheet->setCellValue('F1', 'Área temática');
    $sheet->setCellValue('G1', 'ISBN');
    $sheet->setCellValue('H1', 'Codigo de Clasificacion');
    $sheet->setCellValue('I1', 'Estado');
    $sheet->setCellValue('J1', 'Observaciones');


    $sql = "SELECT l.titulo, l.ano_publicacion as anio, l.edicion, l.ISBN, l.codigo_clasificacion, l.estado, l.observaciones, a.nombre_autor, e.nombre_editorial, ar.nombre_area
        FROM libro l
        INNER JOIN libro_autor la ON l.id_libro = la.id_libro
        INNER JOIN autor a ON la.id_autor = a.id_autor
        INNER JOIN editorial e ON l.id_editorial = e.id_editorial
        INNER JOIN area_tematica ar ON l.id_area_tematica = ar.id_area_tematica";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    $filaExcel = 2;

    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A$filaExcel", $row['titulo']);
        $sheet->setCellValue("B$filaExcel", $row['nombre_autor']);
        $sheet->setCellValue("C$filaExcel", $row['nombre_editorial']);
        $sheet->setCellValue("D$filaExcel", $row['anio']);
        $sheet->setCellValue("E$filaExcel", $row['edicion']);
        $sheet->setCellValue("F$filaExcel", $row['nombre_area']);
        $sheet->setCellValue("G$filaExcel", $row['ISBN']);
        $sheet->setCellValue("H$filaExcel", $row['codigo_clasificacion']);
        $sheet->setCellValue("I$filaExcel", $row['estado']);
        $sheet->setCellValue("J$filaExcel", $row['observaciones']);
        $filaExcel++;
    }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="libros.xlsx"');
    $writer->save('php://output');
    exit;
}

// Redirecciona al index si se accede directamente sin POST
header("Location: index.php");
exit;
?>