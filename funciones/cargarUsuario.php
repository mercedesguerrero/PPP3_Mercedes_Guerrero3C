<?php
    if(isset($_POST['email']) && isset($_POST['nombre']) && isset($_POST['clave']) && isset($_FILES['imagen1']) && isset($_FILES['imagen2']))
    {
        $email = $_POST['email'];
        $nombre = $_POST['nombre'];
        $clave = $_POST['clave'];
        $imagen1 = $_FILES['imagen1'];
        $imagen2 = $_FILES['imagen2'];

        Usuario::AltaUsuario(RUTA_USUARIOS, $email, $nombre, $clave, $imagen1, $imagen2);
                
    }
    else
        echo 'Error cargue "email", "nombre", "clave" e "imagen".';
?>