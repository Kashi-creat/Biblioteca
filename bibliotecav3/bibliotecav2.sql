-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 06:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bibliotecav2`
--

-- --------------------------------------------------------

--
-- Table structure for table `area_tematica`
--

CREATE TABLE `area_tematica` (
  `id_area_tematica` int(11) NOT NULL,
  `nombre_area` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_tematica`
--

INSERT INTO `area_tematica` (`id_area_tematica`, `nombre_area`) VALUES
(1, 'Ciencia Ficción'),
(2, 'Historia'),
(3, 'Fantasía'),
(4, 'Tecnología'),
(5, 'Romance'),
(6, 'Terror'),
(7, 'Distopía'),
(8, 'Fábula'),
(9, 'Clásicos'),
(10, 'Misterio'),
(11, 'Novela'),
(12, 'Filosofía'),
(13, 'Ciencia'),
(14, 'Estrategia'),
(15, ''),
(16, ''),
(17, ''),
(18, 'xd'),
(19, ''),
(20, 'xd');

-- --------------------------------------------------------

--
-- Table structure for table `autor`
--

CREATE TABLE `autor` (
  `id_autor` int(11) NOT NULL,
  `nombre_autor` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `autor`
--

INSERT INTO `autor` (`id_autor`, `nombre_autor`) VALUES
(1, 'Isaac Asimov'),
(2, 'Gabriel García Márquez'),
(3, 'J.K. Rowling'),
(4, 'Stephen King'),
(5, 'Yuval Noah Harari'),
(6, 'Jane Austen'),
(7, 'George Orwell'),
(8, 'Mary Shelley'),
(9, 'Julio Verne'),
(10, 'Dan Brown'),
(11, 'Miguel de Cervantes'),
(12, 'H.P. Lovecraft'),
(13, 'xd'),
(14, 'xdxd'),
(15, 'Frank Herbert'),
(16, 'Antoine de Saint-Exupéry'),
(17, 'Homero'),
(18, 'Umberto Eco'),
(19, 'Ray Bradbury'),
(20, 'Platón'),
(21, 'Stephen Hawking'),
(22, 'Sun Tzu'),
(23, 'Pechuga'),
(24, 'Pechuga'),
(25, 'Pechuga'),
(26, 'Pechuga'),
(27, 'Pechuga'),
(28, 'asd'),
(29, 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `baja`
--

CREATE TABLE `baja` (
  `id_baja` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `motivo` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha_baja` date NOT NULL,
  `acta_generada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baja`
--

INSERT INTO `baja` (`id_baja`, `id_libro`, `motivo`, `fecha_baja`, `acta_generada`) VALUES
(1, 1, 'perdida', '2025-11-20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `devolucion`
--

CREATE TABLE `devolucion` (
  `id_devolucion` int(11) NOT NULL,
  `id_prestamo` int(11) NOT NULL,
  `fecha_devolucion_real` date NOT NULL,
  `observaciones_estado_fisico` text CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `editorial`
--

CREATE TABLE `editorial` (
  `id_editorial` int(11) NOT NULL,
  `nombre_editorial` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `editorial`
--

INSERT INTO `editorial` (`id_editorial`, `nombre_editorial`) VALUES
(1, 'Planeta'),
(2, 'Penguin Random House'),
(3, 'HarperCollins'),
(4, 'Editorial Norma'),
(5, 'Anagrama'),
(6, 'Seix Barral'),
(7, 'xd'),
(8, 'xdxd'),
(9, 'Sudamericana'),
(10, 'Chilton Books'),
(11, 'Debate'),
(12, 'Secker & Warburg'),
(13, 'Reynal & Hitchcock'),
(14, 'Gredos'),
(15, 'Bompiani'),
(16, 'Francisco de Robles'),
(17, 'La Oveja Negra'),
(18, 'Ballantine Books'),
(19, 'Crítica'),
(20, 'Alianza Editorial'),
(21, '');

-- --------------------------------------------------------

--
-- Table structure for table `libro`
--

CREATE TABLE `libro` (
  `id_libro` int(11) NOT NULL,
  `titulo` varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_editorial` int(11) NOT NULL,
  `ano_publicacion` smallint(4) NOT NULL,
  `edicion` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ISBN` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `codigo_clasificacion` varchar(11) DEFAULT NULL,
  `id_area_tematica` int(11) NOT NULL,
  `numero_inventario` int(11) NOT NULL,
  `estado` enum('disponible','dañado','baja','prestado') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `libro`
--

INSERT INTO `libro` (`id_libro`, `titulo`, `id_editorial`, `ano_publicacion`, `edicion`, `ISBN`, `codigo_clasificacion`, `id_area_tematica`, `numero_inventario`, `estado`, `observaciones`) VALUES
(1, 'Cien años de soledad', 9, 1967, 'Primera', '978-84-376-0494-7', 'F-L-GGM', 3, 0, 'baja', 'Sin observaciones'),
(2, 'Dune', 10, 1965, 'Edición de bolsillo', '978-0-441-17271-9', 'CF-H-FH', 1, 0, 'disponible', 'Sin observaciones'),
(3, 'Sapiens: De animales a dioses', 11, 2014, 'Edición revisada', '978-84-9992-622-6', 'H-A-YNH', 2, 0, 'disponible', 'Sin observaciones'),
(4, '1984', 12, 1949, 'Primera', '978-0-452-28423-4', 'D-GO-1984', 7, 0, 'prestado', 'Sin observaciones'),
(5, 'El Principito', 13, 1943, 'Primera', '978-84-376-0494-0', 'F-AS-EP', 8, 0, 'disponible', 'Sin observaciones'),
(6, 'La Odisea', 14, -800, 'Edición crítica', '978-84-249-2210-3', 'C-H-OD', 9, 0, 'disponible', 'Sin observaciones'),
(7, 'El nombre de la rosa', 15, 1980, 'Primera', '978-84-339-7346-3', 'M-UE-NR', 10, 0, 'disponible', 'Sin observaciones'),
(8, 'Don Quijote de la Mancha', 16, 1605, 'Primera', '978-84-376-0494-8', 'C-MC-DQ', 9, 0, 'disponible', 'Sin observaciones'),
(9, 'Crónica de una muerte anunciada', 17, 1981, 'Primera', '978-84-376-0494-9', 'N-GGM-CMA', 11, 0, 'disponible', 'Sin observaciones'),
(10, 'Fahrenheit 451', 18, 1953, 'Primera', '978-0-7432-4722-1', 'CF-RB-F451', 1, 0, 'disponible', 'Sin observaciones'),
(11, 'La República', 14, -380, 'Edición crítica', '978-84-249-1321-7', 'F-P-REP', 12, 0, 'disponible', 'Sin observaciones'),
(12, 'Breves respuestas a las grandes preguntas', 19, 2018, 'Primera', '978-84-9199-037-2', 'C-SH-BRGP', 13, 0, 'disponible', 'Sin observaciones'),
(13, 'El arte de la guerra', 20, -500, 'Edición crítica', '978-84-206-5742-7', 'E-ST-AG', 14, 0, 'disponible', 'Sin observaciones');

-- --------------------------------------------------------

--
-- Table structure for table `libro_autor`
--

CREATE TABLE `libro_autor` (
  `id_libro` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `id_compuesta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `libro_autor`
--

INSERT INTO `libro_autor` (`id_libro`, `id_autor`, `id_compuesta`) VALUES
(1, 2, 1),
(2, 15, 2),
(3, 5, 3),
(4, 7, 4),
(5, 16, 5),
(6, 17, 6),
(7, 18, 7),
(8, 11, 8),
(9, 2, 9),
(10, 19, 10),
(11, 20, 11),
(12, 21, 12),
(13, 22, 13);

-- --------------------------------------------------------

--
-- Table structure for table `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion_estimada` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `id_usuario`, `id_libro`, `fecha_prestamo`, `fecha_devolucion_estimada`) VALUES
(1, 2, 4, '2025-11-20', '2444-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo_usuario` enum('estudiante','docente') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `dni_o_matricula` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_completo`, `password`, `tipo_usuario`, `dni_o_matricula`) VALUES
(2, 'docente', '$2y$10$Uv/EmGzbVoTLMWJYbFzwMucpAHgJYTJ9uH2BhGyg/5JE4n7U.U5R6', 'docente', 222),
(3, 'estudiante', '$2y$10$qOpdb/APzVa6I/MMoUX/Lef/cShrp94eWX8RV.yPCEDuwkbJCMZyS', 'estudiante', 111),
(4, 'admin', '$2y$10$lZ3JK1Unzgw7FeLGZfhvP.2S5Mn249NrpDWrKosqEkBCWhwLlXkkW', 'docente', 999);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area_tematica`
--
ALTER TABLE `area_tematica`
  ADD PRIMARY KEY (`id_area_tematica`);

--
-- Indexes for table `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id_autor`);

--
-- Indexes for table `baja`
--
ALTER TABLE `baja`
  ADD PRIMARY KEY (`id_baja`),
  ADD KEY `FK id_libro2` (`id_libro`);

--
-- Indexes for table `devolucion`
--
ALTER TABLE `devolucion`
  ADD PRIMARY KEY (`id_devolucion`),
  ADD KEY `FK_D_id_prestamo` (`id_prestamo`);

--
-- Indexes for table `editorial`
--
ALTER TABLE `editorial`
  ADD PRIMARY KEY (`id_editorial`);

--
-- Indexes for table `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id_libro`),
  ADD KEY `FK id_editorial` (`id_editorial`),
  ADD KEY `FK id_area_tematica` (`id_area_tematica`);

--
-- Indexes for table `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD PRIMARY KEY (`id_compuesta`),
  ADD KEY `FK id_libro` (`id_libro`),
  ADD KEY `FK id_autor` (`id_autor`);

--
-- Indexes for table `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `FK_P_id_usuario` (`id_usuario`),
  ADD KEY `FK_P_id_libro` (`id_libro`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area_tematica`
--
ALTER TABLE `area_tematica`
  MODIFY `id_area_tematica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `autor`
--
ALTER TABLE `autor`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `baja`
--
ALTER TABLE `baja`
  MODIFY `id_baja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `devolucion`
--
ALTER TABLE `devolucion`
  MODIFY `id_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `editorial`
--
ALTER TABLE `editorial`
  MODIFY `id_editorial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `libro`
--
ALTER TABLE `libro`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `libro_autor`
--
ALTER TABLE `libro_autor`
  MODIFY `id_compuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baja`
--
ALTER TABLE `baja`
  ADD CONSTRAINT `FK id_libro2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Constraints for table `devolucion`
--
ALTER TABLE `devolucion`
  ADD CONSTRAINT `FK_D_id_prestamo` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamo` (`id_prestamo`);

--
-- Constraints for table `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `FK id_area_tematica` FOREIGN KEY (`id_area_tematica`) REFERENCES `area_tematica` (`id_area_tematica`),
  ADD CONSTRAINT `FK id_editorial` FOREIGN KEY (`id_editorial`) REFERENCES `editorial` (`id_editorial`);

--
-- Constraints for table `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD CONSTRAINT `FK id_autor` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`),
  ADD CONSTRAINT `FK id_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Constraints for table `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `FK_P_id_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  ADD CONSTRAINT `FK_P_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
