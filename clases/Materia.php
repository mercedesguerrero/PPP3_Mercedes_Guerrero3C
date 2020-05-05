<?php
class Materia
{
    private $id;
    private $nombre;
    private $cuatrimestre;

    function __construct($id, $nombre, $cuatrimestre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->cuatrimestre = $cuatrimestre;
    }

    public function __get($property){
        if(property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value){
        if(property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    
    public function __toString()
    {        
        return "id: $this->id  || nombre: $this->nombre   </br>";
    }


    /** Devuelve array con nombres de las propiedades de la clase (para los headers de la tabla) */
    public static function getPublicProperties(){        
        return array('id','nombre','cuatrimestre');
        
    }

    /** toJSON*/
    public function jsonSerialize()
    {
        return 
        [
            'id'   => $this->id,
            'nombre' => $this->nombre,
            'cuatrimestre' => $this->cuatrimestre
        ];
    }

    /** Lee archivo (array de json de objeto)
     * 
     * $path = Ubicacion del archivo
     *  Retorna un listado de json de objetos de la clase
     */
    public static function leerFromJSON($path)
    {
        $retorno = array();
        $json = file_get_contents($path);
        $json_data = json_decode($json,true);
        //var_dump($json_data);
        
        foreach ($json_data as $key => $value) 
        {                                                                                
            array_push($retorno, new Materia($json_data[$key]['id'],$json_data[$key]['nombre'],$json_data[$key]['cuatrimestre']));
        }

        return $retorno;
    }

    public static function CargarMateria($path, $nombre, $cuatrimestre)
    {
        echo "<br>Entro Materia</br>";
        $lista= self::leerFromJSON($path);    
        
        $maxId = self::TraerMayorId($lista);
        $Id= $maxId + 1;
        $materia= self::BuscaXCriterio($lista, "id", $Id);
        
        if($materia!=null)
        {
            echo "<br>La materia ya existe.<br>";
        }
        else
        { 
            $materia= new Materia($Id, $nombre, $cuatrimestre);     
            array_push($lista, $materia);
            self::guardarJSON($lista, $path);
            echo "<br>La materia se guardó<br>";
                
            // else
            // {
            //     $unUsuario->BorrarUsuario($RUTA_USUARIOS);
            //     echo "No se pudo cargar con imagen.";
            // }
        }

        
    }

    public static function BuscaXCriterio($lista, $criterio, $dato)
    {
        $retorno=null;
        foreach ($lista as $objeto) {
            if ($objeto->$criterio == $dato) 
            {          
                $retorno= $objeto;
                break;
            }
        }
        return $retorno;
    }

    public static function TraerMayorId($lista)
    {
        $maxId= 0;

        if($lista!=null)
        {
            $maxId= $lista[0]->id;
            foreach ($lista as $user)
            {
                if($user->id > $maxId)
                {
                    $maxId = $user->id;
                }
            }
        }
        return $maxId;
    }

    public static function guardarJSON($listado, $nombreArchivo) 
    {
        $archivo = fopen($nombreArchivo, "w");
        $array = array();
        foreach($listado as $objeto){
            array_push($array, $objeto->jsonSerialize());
        }
        fputs($archivo, json_encode($array) . PHP_EOL);                    

        fclose($archivo);

        return $listado;
    }


    public static function CargarImagen($files, $id, $pathCarpetaImagenes, $numero)
    {
        if(isset($files))
        {
            $extension = self::TraerExtensionImagen($files);

            if($extension != null)
            {
                $nombreDelArchivoImagen = "LEG" . $id . "_" . $numero . $extension;
                $pathCompletaImagen = $pathCarpetaImagenes . $nombreDelArchivoImagen;
                return move_uploaded_file($files["tmp_name"], $pathCompletaImagen);
            }
        }
        return false;
    }

    public static function TraerExtensionImagen($files)
    {
        switch ($files["type"])
        {
            case 'image/jpeg':
                $extension = ".jpg";
                break;
            case 'image/png':
                $extension = ".png";
                break;
            default:
                return null;
                break;
        }
        return $extension;
    }

    public static function ImgUsuariosEnTabla($path)
    {
        $imagenes = scandir($path);
        $retorno = "<table border = 3 bordercolor = red align = left>";
        $retorno .= "<tbody>";
        foreach ($imagenes as $img)
        {
            if(!file_exists($img))
            {
                $retorno .= "<tr>";
                $retorno .= "<td><img src='" . $path . $img . "' height='160' width='160' /></td>";
                $retorno .= "</tr>";
            }
        }
        $retorno .= "</tbody>"; 
        $retorno .= "</table>";
    
        return "<div> " . $retorno . "</div>";
    }

    static public function login($id, $cuatrimestre)
    {
        echo "<br>Entro en login con datos:  $id , $cuatrimestre </br>";    
        $lista= Usuario::leerFromJSON(RUTA_USUARIOS);        
        $user= self::BuscaXCriterio($lista, "id", $id);
        
        if($user==null)
        {
            echo "<br>El user NO existe<br>";
        }
        else
        {      
            if($user->cuatrimestre== $cuatrimestre)
            {
                echo $user;
                echo "<br>Contraseña correcta<br>";
            }
            else
            {
                echo "<br>Contraseña incorrecta<br>";
            }
        }
    }

    static public function log($caso, $hora, $ip)
    {
        $lista = Log::leerFromJSON(RUTA_LOGS);
        echo "<br>Entro log con datos: $caso, $hora, $ip </br>";
        $Log= new Log($caso ,$hora,$ip);
        array_push($lista, $Log);
        self::guardarJSON($lista, RUTA_LOGS);
    }


    static public function modificarUsuario($path, $id, $email, $nombre, $cuatrimestre, $foto1, $foto2)    
    {
        $lista = Usuario::leerFromJSON($path);
        $user= self::BuscaXCriterio($lista, "id", $id);
        
        if($user==null)
        {
            echo "<br>El user NO existe, no se puede modificar<br>";
        }
        else
        {      
            $user->email= $email;
            $user->nombre= $nombre;
            $user->cuatrimestre= $cuatrimestre;

            $user->MoverImgABackUp(RUTA_CARPETA_BACKUP, RUTA_CARPETA_IMAGENES, $id, 1);
            $user->MoverImgABackUp(RUTA_CARPETA_BACKUP, RUTA_CARPETA_IMAGENES, $id, 2);
            
            self::guardarJSON($lista, $path);
        }
    }

    public function MoverImgABackUp($carpetaFotosBackup, $carpetaFotos, $id, $numFoto)
    {
        $extension = ".jpg";     
        
        $fotoUsuario= "LEG" . $id . "_" . $numFoto . $extension;   
        $pathFotoOriginal = $carpetaFotos . $fotoUsuario;
            
        if(file_exists($pathFotoOriginal))
        {
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $pathFotoBackUp = $carpetaFotosBackup . date('Ymd') . "_" . $fotoUsuario;
            return rename ($pathFotoOriginal, $pathFotoBackUp);
        }
        else
        {
            echo '<br/>Error! no existe la imagen.';
            die;
        }
    }

    static public function logs($fecha)
    {
        $lista = Log::leerFromJSON(RUTA_LOGS);
        echo "<br>Entro log con fecha: $fecha </br>";
        
        foreach($lista as $objeto)
        {//campo con formato 20190930_080941

            $dia= explode('_', $objeto->hora);

            if ($dia[0]>$fecha)
            {
                echo $objeto;
            }
        }
        
    }

    public static function verUsuarios($path)
    {
        $lista = self::leerFromJSON($path);  
        $retorno = "";
        
        if($lista != null)
        {
            foreach ($lista as $user)
            {
                $retorno .= "<div align='left'>id: " . $user->id . 
                    " || Email: " . $user->email . " || Nombre: " . $user->nombre . 
                    " || cuatrimestre: " . $user->cuatrimestre . "</div>";
                
            }
        }
        return $retorno;
    }

    // static public function verUsuarios($path)
    // {  
    //     $lista = self::leerFromJSON($path);   
        
    //     foreach($lista as $objeto)
    //     {            
    //         echo $objeto;
    //     }
    // }
    
    static public function verUsuario($id)
    {
         
        $lista = self::leerFromJSON(RUTA_USUARIOS);   
        $listaFiltrada= self::SubListaXCriterio($lista, "id", $id, FALSE); 

        foreach($listaFiltrada as $objeto)
        {            
            echo $objeto;
        }
    }

    public static function SubListaXCriterio($lista, $criterio, $dato, $caseSensitive)
    {
        $retorno=null;        
        $sublista=array();
        
        /*  
        if(!$caseSensitive)
        {//Si esta en FALSE paso Todo a minisculas (Array y dato)
            $lista = array_map('strtolower', $lista);  //Esta Mierda no me esta andando aca...
            $dato=strtolower($dato);
        }        
        */
        //self::debugAlgo($lista);        
        foreach ($lista as $objeto) 
        {   
            //echo "$criterio , $dato</br>";
        //    self::debugAlgo($objeto);         
            //if ($objeto->criterio == $dato) 
            if ( strtolower($objeto->$criterio) == strtolower($dato) )
            {//si encuentra lo agrego en la sublista
                array_push($sublista, $objeto);
            }
        }

        if(count($sublista)>0)
        {
            $retorno= $sublista;
        }
        return $retorno;
    }


}
?>