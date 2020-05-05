<?php

echo "<br>Consultar Alumno<br>";

if (isset($_GET["legajo"])   ) 
{
    $legajo = $_GET["legajo"];

    Usuario::verUsuario($legajo);
}
else
{
    echo "Falta cargar legajo";
}


?>