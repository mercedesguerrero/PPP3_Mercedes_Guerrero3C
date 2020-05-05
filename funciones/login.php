<?php
    if(isset($_GET['legajo']) && isset($_GET['clave']))
    {
        $legajo = $_GET['legajo'];
        $clave = $_GET['clave'];

        Usuario::login($legajo, $clave);
        
    }
    else
    {
        echo 'Error cargue "legajo" y "clave".';
    }
?>