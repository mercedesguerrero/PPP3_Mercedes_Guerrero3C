<?php
    if(isset($_POST['nombre']) && isset($_POST['cuatrimestre']))
    {
        $nombre = $_POST['nombre'];
        $cuatrimestre = $_POST['cuatrimestre'];

        Materia::CargarMateria(RUTA_MATERIAS, $nombre, $cuatrimestre);
                
    }
    else
        echo 'Error cargue "nombre" y "cuatrimestre".';
?>