<?php
    if(isset($_POST['email']) && isset($_POST['clave']))
    {
        $email = $_POST['email'];
        $clave = $_POST['clave'];

        Usuario::AltaUsuario(RUTA_USUARIOS, $email, $clave);
                
    }
    else
        echo 'Error cargue "email" y "clave".';
?>