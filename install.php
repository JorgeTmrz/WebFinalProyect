<?php
// ingresen la conexion include ->('Librerias/utils.php');


$mensaje = array();
$txt="";
if(isset($_POST['submit']))
{

    $ok = true;

    $datos[0] = $_POST['servidor'];
    $datos[1] = $_POST['usuario'];
    $datos[2] = $_POST['contraseña'];
    $datos[3] = $_POST['basededatos'];
    
    if(count($datos) != 4){
        $mensaje[] = "No ha ingresado 4 datos";
        $ok = false;
    }
   

    if($ok){
        $link = mysqli_connect($datos[0],$datos[1],$datos[2]);
       
        

        if($link == false){
            $mensaje[]="revisa porque no nos podemos conectar";
        }
        else{
            $sql = "create database {$datos[3]}";
            mysqli_query($link,$sql);

            mysqli_query($link, "use {$datos[3]}");
            
            $sql = "DROP TABLE IF EXISTS `citas`;";
         
            mysqli_query($link,$sql);

            $sql = "DROP TABLE IF EXISTS `citaspendientes`;";
            mysqli_query($link,$sql);

            $sql = "DROP TABLE IF EXISTS `pacientes`;";
            mysqli_query($link,$sql);

            $sql = "DROP TABLE IF EXISTS `rol`;";
            mysqli_query($link,$sql);

            $sql = "DROP TABLE IF EXISTS `usuarios`;";
            mysqli_query($link,$sql);

            $sql = "DROP TABLE IF EXISTS `visitas`;";
            mysqli_query($link,$sql);

            $sql = " CREATE TABLE IF NOT EXISTS `citas` (
                `Id` int(11) NOT NULL AUTO_INCREMENT,
                `Costo` int(11) DEFAULT NULL,
                `Doctor_Asigando` int(11) NOT NULL,
                `Fecha` date NOT NULL DEFAULT current_timestamp(),
                `Hora` time NOT NULL,
                `Duracion` int(11) NOT NULL,
                `Paciente_asignado` int(11) NOT NULL,
                `Completada` tinyint(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`Id`)
               
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
                
              
              mysqli_query($link,$sql);

              $sql ="CREATE TABLE IF NOT EXISTS `citaspendientes` (
                `Id` int(11) NOT NULL AUTO_INCREMENT,
                ,`Costo` int(11)
                ,`Doctor_Asigando` int(11)
                ,`Fecha` date
                ,`Hora` time
                ,`Duracion` int(11)
                ,`Paciente_asignado` int(11)
                ,`Completada` tinyint(1),
                PRIMARY KEY (`Id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
              mysqli_query($link,$sql);

             $sql = "CREATE TABLE IF NOT EXISTS `pacientes`
             (  `Cedula` varchar(13) NOT NULL,
                `Nombre` varchar(50) NOT NULL,
                `Apellido` varchar(50) NOT NULL,
                `fecha_nacimiento` date NOT NULL,
                `Tipo_Sangre` varchar(3) NOT NULL,
                PRIMARY KEY (`Cedula`)
             
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
             
             mysqli_query($link,$sql);

             $sql = "CREATE TABLE IF NOT EXISTS `rol`
             (
                `Id` int(11) NOT AUTO_INCREMENT, AUTO_INCREMENT=4,
                `Tipo` varchar(15) NOT NULL,
                PRIMARY KEY (`Id`)
             
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
             
             mysqli_query($link,$sql);

             $sql= "INSERT INTO `rol` (`Id`, `Tipo`) 
             VALUES
            (1, 'Admin'),
            (2, 'Secretaria'),
            (3, 'Doctor')";
              mysqli_query($link,$sql);

             $sql = "CREATE TABLE IF NOT EXISTS `usuarios`
             (
                `Id` int(4) NOT NULL AUTO_INCREMENT,
                `Nombre` varchar(50) NOT NULL,
                `TipoUsuario` int(11) NOT NULL,
                PRIMARY KEY (`id`)
             
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
             
             mysqli_query($link,$sql);

             $sql = "CREATE TABLE IF NOT EXISTS `visitas`
             (
                `Id` int(4) NOT NULL AUTO_INCREMENT,
                `Fecha` date NOT NULL,
                `Motivo` varchar(100) NOT NULL,
                `Comentario` varchar(200) NOT NULL,
                `Proxima_visita` date DEFAULT NULL,
                `Receta` text NOT NULL,
                `doctor_id` int(11) NOT NULL,
                `paciente_id` int(11) NOT NULL,
                PRIMARY KEY (`Id`)
             
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
             
             mysqli_query($link,$sql);


             $sql="DROP TABLE IF EXISTS `citaspendientes`;";
             mysqli_query($link,$sql);

             $sql="CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `citaspendientes`  AS  select `citas`.`Id` AS `Id`,`citas`.`Costo` AS `Costo`,`citas`.`Doctor_Asigando` AS `Doctor_Asigando`,`citas`.`Fecha` AS `Fecha`,`citas`.`Hora` AS `Hora`,`citas`.`Duracion` AS `Duracion`,`citas`.`Paciente_asignado` AS `Paciente_asignado`,`citas`.`Completada` AS `Completada` from `citas` where `citas`.`Completada` = 0 ; ";
             mysqli_query($link,$sql);
             

            $sql="DELIMITER $$
            
            CREATE DEFINER=`` PROCEDURE `AssignCite` (IN `Doctor` INT, IN `Paciente` INT, IN `Duration` INT, IN `fech` DATE, IN `hor` TIME)  BEGIN
                INSERT INTO citas(Doctor_Asigando,Paciente_asignado,Duracion,Fecha,Hora) 
                VALUES (Doctor,Paciente,Duration,fech,hor);
            END$$
            
            CREATE DEFINER=`` PROCEDURE `AssingConsultCost` (IN `CostoCon` DOUBLE, IN `IDConsulta` INT)  BEGIN
                UPDATE citas SET Costo = CostoCon WHERE Id = IDConsulta;
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
                SELECT Receta FROM visitas WHERE Id = idVisit;
            END$$
            
            CREATE DEFINER=`` PROCEDURE `ModifyUser` (IN `UserId` INT, IN `nbr` VARCHAR(50), IN `tipo` INT)  BEGIN
                UPDATE usuarios SET Nombre = nbr, TIpoUsuario = tipo WHERE ID = Userid;
            END$$
            
            CREATE DEFINER=`` PROCEDURE `PayCite` (IN `IDEN` INT, IN `Payment` INT)  BEGIN
                SELECT @CurrentDebt := Costo FROM Citas;
                UPDATE citas SET Costo = @CurrentDebt - Payment WHERE Id = IDEN;
            END$$
            
            DELIMITER ;
            ";

            mysqli_query($link,$sql);





              $info = "<?
define('DB_HOST','{$datos[0]}');
define('DB_USER','{$datos[1]}');
define('DB_PASS','{$datos[2]}');
define('DB_NAME','{$datos[3]}'); 
              ";
              // ingresen la libreria de config -> file_put_contents("Librerias/Configx.php",$info);
             echo "
                 <script>
                 alert('sistema instalado...');
                 window.location = 'index.php'  
                 </script>
             ";
              
        }

    }
}

$mensaje = implode("<br />",$mensaje);


?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Instalador</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body style="background-color:lightblue;">
        <div class="container">
 <h1 style="text-align: center;">Instalador/h1>
</br>
</br>
            <div class="center-align">
                <div class="col col-sm-6">
                <form action="" method="post">
                    <div class="form-group input-group">
                        <label class="input-group-addon" style="font-size:x-large "><strong>Escriba el nombre del servidor: </strong></label>                       
                        <input required type="text" name="servidor" class="form-control" value="localhost" id="servidor">
                    </div>
                    <div class="form-group input-group" style="font-size:x-large ">
                        <label class="input-group-addon"><strong>Escriba el nombre de usuario: </strong></label>                        
                        <input required type="text" name="usuario" class="form-control" value="root" id="usuario">
                    </div>  
                    <div class="form-group input-group" style="font-size:x-large ">
                        <label class="input-group-addon"><strong>Escriba la contraseña de la BDD: </strong></label> <br>                        
                        <input required type="password" name="contraseña" class="form-control" value="mysql" id="contraseña">
                    </div>
                    <div class="form-group input-group" style="font-size:x-large ">
                        <label class="input-group-addon"><strong> el nombre de la base de datos: </strong></label>                        
                        <input required type="text" name="basededatos" class="form-control" value="tarea7" id="basededatos">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success" style="font-size:large">Instalar</button>
                </form>
                </div>
            </div> 
        </div>                       
    </body>    
</html>