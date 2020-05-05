<?php

echo "<br>Logs <br>";
if (isset($_GET["fecha"])) 
{
    Usuario::logs($_GET["fecha"] );
}
else
{
    echo "Falta cargar fecha";
}


?>