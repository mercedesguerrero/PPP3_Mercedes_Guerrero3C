<?php

if (isset($_POST["legajo"]) && isset($_POST["email"]) && isset($_POST["nombre"]) && isset($_POST["clave"]) && isset($_FILES["imagen1"]) && isset($_FILES["imagen2"])  ) 
{   
    $legajo =  $_POST['legajo'];
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $clave = $_POST['clave'];
    $imagen1 = $_FILES['imagen1'];
    $imagen2 = $_FILES['imagen2'];

    Usuario::modificarUsuario(RUTA_USUARIOS, $legajo, $email, $nombre, $clave, $imagen1, $imagen2);    

}
else
{
    echo "Falta cargar datos";
}


?>
