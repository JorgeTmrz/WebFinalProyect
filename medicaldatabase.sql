-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2020 at 03:30 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medicaldatabase`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`` PROCEDURE `AssignCite` (IN `Doctor` INT, IN `Paciente` INT, IN `Duration` INT, IN `fech` DATE, IN `hor` TIME)  BEGIN
	INSERT INTO citas(Doctor_Asigando,Paciente_asignado,Duracion,Fecha,Hora) 
    VALUES (Doctor,Paciente,Duration,fech,hor);
END$$

CREATE DEFINER=`` PROCEDURE `AssingConsultCost` (IN `CostoCon` DOUBLE, IN `IDConsulta` INT)  BEGIN
	UPDATE citas SET Costo = CostoCon WHERE ID = IDConsulta;
END$$

CREATE DEFINER=`` PROCEDURE `birthDayOnAMonth` (IN `mes` INT)  BEGIN 
	SELECT * FROM pacientes WHERE month(Fecha) * 1 = mes;
END$$

CREATE DEFINER=`` PROCEDURE `citesOnADay` (IN `sortDate` DATE)  BEGIN
	SELECT * FROM citaspendientes WHERE Fecha = sortDate;
END$$

CREATE DEFINER=`` PROCEDURE `CompleteCite` (IN `citeID` INT)  BEGIN
	UPDATE citas SET Completada = true WHERE Id = citeID;
END$$

CREATE DEFINER=`` PROCEDURE `CreatePatient` (IN `Ced` VARCHAR(13), IN `name` VARCHAR(50), IN `LastName` VARCHAR(50), IN `fechaNacimiento` DATE, IN `TipoSangre` VARCHAR(3))  BEGIN

INSERT INTO pacientes(Cedula, nombre, Apellido, fecha_nacimiento, Tipo_Sangre) 
VALUES (Ced, name,LastName,fechaNacimiento,TipoSangre);

END$$

CREATE DEFINER=`` PROCEDURE `Createuser` (IN `nbr` VARCHAR(50), IN `tipo` INT)  BEGIN
    	INSERT INTO usuarios(Nombre, TipoUsuario) VALUES(nbr, tipo);
    END$$

CREATE DEFINER=`` PROCEDURE `CreateVisit` (IN `DocID` INT, IN `Pac_ID` INT, IN `Fech` DATE, IN `Motiv` VARCHAR(50), IN `Commen` VARCHAR(200), IN `Recipe` TEXT, IN `NextVisit` DATE)  BEGIN
	INSERT INTO visitas(Fecha, Motivo, Comentario, Proxima_visita, Receta, doctor_id, paciente_id) 
    VALUES (Fech,Motiv,Comen,NextVisit,Recipe,DocID,Pac_ID);
END$$

CREATE DEFINER=`` PROCEDURE `getEarningsByDay` (IN `Day` DATE)  SELECT COUNT(Id), SUM(Costo) FROM citas WHERE Fecha = Day$$

CREATE DEFINER=`` PROCEDURE `getRecipe` (IN `idVisit` INT)  BEGIN
	SELECT Receta FROM visitas WHERE ID = idVisit;
END$$

CREATE DEFINER=`` PROCEDURE `ModifyUser` (IN `UserId` INT, IN `nbr` VARCHAR(50), IN `tipo` INT)  BEGIN
	UPDATE usuarios SET Nombre = nbr, TIpoUsuario = tipo WHERE ID = Userid;
END$$

CREATE DEFINER=`` PROCEDURE `PayCite` (IN `IDEN` INT, IN `Payment` INT)  BEGIN
	SELECT @CurrentDebt := Costo FROM Citas;
    UPDATE citas SET Costo = @CurrentDebt - Payment WHERE ID = IDEN;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `citas`
--

CREATE TABLE `citas` (
  `Id` int(11) NOT NULL,
  `Costo` int(11) DEFAULT NULL,
  `Doctor_Asigando` int(11) NOT NULL,
  `Fecha` date NOT NULL DEFAULT current_timestamp(),
  `Hora` time NOT NULL,
  `Duracion` int(11) NOT NULL,
  `Paciente_asignado` int(11) NOT NULL,
  `Completada` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structure for view `citaspendientes`
-- (See below for the actual view)
--
CREATE TABLE `citaspendientes` (
`Id` int(11)
,`Costo` int(11)
,`Doctor_Asigando` int(11)
,`Fecha` date
,`Hora` time
,`Duracion` int(11)
,`Paciente_asignado` int(11)
,`Completada` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `pacientes`
--

CREATE TABLE `pacientes` (
  `Cedula` varchar(13) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `TIpo_Sangre` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `ID` int(11) NOT NULL,
  `TIpo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`ID`, `Tipo`) VALUES
(1, 'Admin'),
(2, 'Secretaria'),
(3, 'Doctor');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `TIpoUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visitas`
--

CREATE TABLE `visitas` (
  `ID` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Motivo` varchar(100) NOT NULL,
  `Comentario` varchar(200) NOT NULL,
  `Proxima_visita` date DEFAULT NULL,
  `Receta` text NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure for view `citaspendientes`
--
DROP TABLE IF EXISTS `citaspendientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `citaspendientes`  AS  select `citas`.`Id` AS `Id`,`citas`.`Costo` AS `Costo`,`citas`.`Doctor_Asigando` AS `Doctor_Asigando`,`citas`.`Fecha` AS `Fecha`,`citas`.`Hora` AS `Hora`,`citas`.`Duracion` AS `Duracion`,`citas`.`Paciente_asignado` AS `Paciente_asignado`,`citas`.`Completada` AS `Completada` from `citas` where `citas`.`Completada` = 0 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`Cedula`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitas`
--
ALTER TABLE `visitas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
