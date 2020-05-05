<?php

    /*
    3- A partir de este punto se debe guardar en una archivo info.log todas las peticiones que se hagan a la aplicación
    (caso, hora e ip).
    */

    $ip_server= $_SERVER['SERVER_ADDR'];
    $hora= date("Ymd_hms");
    Usuario::log($caso, $hora, $ip_server);

?>
