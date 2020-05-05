<?php
class Usuario
{
    private $email;
    private $clave;
    private $Id;

    function __construct($Id,$email, $clave)
    {
        $this->Id = $Id;
        $this->email = $email;
        $this->clave = $clave;
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
        return "email: $this->email </br>";
    }


    /** Devuelve array con nombres de las propiedades de la clase (para los headers de la tabla) */
    public static function getPublicProperties(){        
        return array('Id','email','clave');
        
    }

    /** toJSON*/
    public function jsonSerialize()
    {
        return 
        [
            'Id' => $this->Id,
            'email'   => $this->email,
            'clave' => $this->clave
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
            array_push($retorno, new Usuario($json_data[$key]['Id'],$json_data[$key]['email'],$json_data[$key]['clave']));
        }

        return $retorno;
    }

    public static function AltaUsuario($path, $email, $clave)
    {
        echo "<br>Entro en alta Usuario</br>";
        $lista= self::leerFromJSON($path);    
        
        $maxId = self::TraerMayorId($lista);
        $Id= $maxId + 1;
        $user= self::BuscaXCriterio($lista, "email", $email);
        
        if($user!=null)
        {
            echo "<br>El Usuario ya existe.<br>";
        }
        else
        { 
            $user= new Usuario($Id, $email, $clave);     
            array_push($lista, $user);
            self::guardarJSON($lista, $path);
            echo "<br>El Usuario se guardó<br>";

            echo $user;
                
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
            $maxId= $lista[0]->Id;
            foreach ($lista as $user)
            {
                if($user->Id > $maxId)
                {
                    $maxId = $user->Id;
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


    public static function CargarImagen($files, $legajo, $pathCarpetaImagenes, $numero)
    {
        if(isset($files))
        {
            $extension = self::TraerExtensionImagen($files);

            if($extension != null)
            {
                $nombreDelArchivoImagen = "LEG" . $legajo . "_" . $numero . $extension;
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

    static public function login($email, $clave)
    {
        echo "<br>Entro en login con datos:  $email , $clave </br>";    
        $lista= Usuario::leerFromJSON(RUTA_USUARIOS);        
        $user= self::BuscaXCriterio($lista, "email", $email);
        
        if($user==null)
        {
            echo "<br>El user NO existe<br>";
        }
        else
        {      
            if($user->clave== $clave)
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


    static public function modificarUsuario($path, $legajo, $email, $nombre, $clave, $foto1, $foto2)    
    {
        $lista = Usuario::leerFromJSON($path);
        $user= self::BuscaXCriterio($lista, "legajo", $legajo);
        
        if($user==null)
        {
            echo "<br>El user NO existe, no se puede modificar<br>";
        }
        else
        {      
            $user->email= $email;
            $user->nombre= $nombre;
            $user->clave= $clave;

            $user->MoverImgABackUp(RUTA_CARPETA_BACKUP, RUTA_CARPETA_IMAGENES, $legajo, 1);
            $user->MoverImgABackUp(RUTA_CARPETA_BACKUP, RUTA_CARPETA_IMAGENES, $legajo, 2);
            
            self::guardarJSON($lista, $path);
        }
    }

    public function MoverImgABackUp($carpetaFotosBackup, $carpetaFotos, $legajo, $numFoto)
    {
        $extension = ".jpg";     
        
        $fotoUsuario= "LEG" . $legajo . "_" . $numFoto . $extension;   
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
                $retorno .= "<div align='left'>Legajo: " . $user->legajo . 
                    " || Email: " . $user->email . " || Nombre: " . $user->nombre . 
                    " || Clave: " . $user->clave . "</div>";
                
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
    
    static public function verUsuario($legajo)
    {
         
        $lista = self::leerFromJSON(RUTA_USUARIOS);   
        $listaFiltrada= self::SubListaXCriterio($lista, "legajo", $legajo, FALSE); 

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