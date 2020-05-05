<?php
    if(isset($_POST['email']) && isset($_POST['clave']))
    {
        $email = $_POST['email'];
        $clave = $_POST['clave'];

        Usuario::login($email, $clave);
        
    }
    else
    {
        echo 'Error cargue "email" y "clave".';
    }
?>