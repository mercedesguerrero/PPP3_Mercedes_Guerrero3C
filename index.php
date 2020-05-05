<?php
require_once 'clases/Usuario.php';
require_once 'clases/Materia.php';
require_once 'clases/Log.php';

//$RUTA_USUARIOS="./archivos/usuarios.txt";
define("RUTA_USUARIOS", "./archivos/usuarios.txt"); 
define("RUTA_MATERIAS", "./archivos/materias.txt");
define("RUTA_CARPETA_IMAGENES", "./img/"); 
define("RUTA_CARPETA_BACKUP", "./img/backup/"); 
define("RUTA_LOGS", "./archivos/logs.txt"); 
//$RUTA_CARPETA_IMAGENES = "./img/";

$content= file_get_contents("php://input");
$method = $_SERVER['REQUEST_METHOD'];
echo $method . "<br>";
switch ($method) 
{
    case "GET":
    //var_dump($_GET);
        switch (key($_GET)) {
            case 'materia':
                include "funciones/login.php";
                break;
            case 'profesor':
                include "funciones/profesor.php";
                break;
            case 'asignacion':
                include "funciones/asignacion.php";
                break;
        }
        $caso= key($_GET);
        include 'funciones/logsDeLaAplicacion.php';

        break; 
    case "POST":
    //var_dump($_POST);
        switch (key($_POST)) {
            case 'usuario'://POST
                include "funciones/usuario.php";
                break;    
            case 'login'://POST
                include "funciones/login.php";
                break;   
            case 'materia'://POST
                include "funciones/materia.php";
                break;     
            case 'profesor'://POST
                include "funciones/profesor.php";
                break;  
            case 'asignacion'://POST
                include "funciones/asignacion.php";
                break;    
        }
        $caso= key($_POST);
        include 'funciones/logsDeLaAplicacion.php';

        break;
    
}     
?>