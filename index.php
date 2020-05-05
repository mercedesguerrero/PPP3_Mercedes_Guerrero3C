<?php
require_once 'clases/Usuario.php';
require_once 'clases/Log.php';

//$RUTA_USUARIOS="./archivos/usuarios.txt";
define("RUTA_USUARIOS", "./archivos/usuarios.txt"); 
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
                include "funciones/verUsuarios.php";
                break;
            case 'asignacion':
                include "funciones/verUsuario.php";
                break;
        }
        $caso= key($_GET);
        include 'funciones/logsDeLaAplicacion.php';

        break; 
    case "POST":
    //var_dump($_POST);
        switch (key($_POST)) {
            case 'ususario'://POST
                include "funciones/cargarUsuario.php";
                break;    
            case 'login'://POST
                include "funciones/modificarUsuario.php";
                break;   
            case 'materia'://POST
                include "funciones/modificarUsuario.php";
                break;     
            case 'profesor'://POST
                include "funciones/modificarUsuario.php";
                break;  
            case 'asignacion'://POST
                include "funciones/modificarUsuario.php";
                break;    
        }
        $caso= key($_POST);
        include 'funciones/logsDeLaAplicacion.php';

        break;
    
}     
?>