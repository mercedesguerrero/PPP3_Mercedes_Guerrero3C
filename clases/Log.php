<?php

class Log{

    private $caso; 
    private $hora;
    private $ip; 
  
    function __construct($caso, $hora,$ip)
    {
        $this->caso= $caso;
        $this->hora= $hora;
        $this->ip= $ip;        
    }


// ------- Getters && Setters ------------------- 

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
        return "caso: $this->caso  || hora: $this->hora ||ip: $this->ip </br>";
    }


    /** Devuelve array con ips de las propiedades de la clase (para los headers de la tabla) */
    public static function getPublicProperties(){        
        return array('caso','hora','ip');        
    }

    /** seria un tojson*/
    public function jsonSerialize()
    {
        return 
        [
            'caso'   => $this->caso,
            'hora'   => $this->hora,
            'ip' => $this->ip    
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
         //   array_push($retorno,new Log($json_data[$key]['ip'],$json_data[$key]['apellido'],$json_data[$key]['hora'],$json_data[$key]['nomFoto']));                                                                                   
            array_push($retorno,new Log($json_data[$key]['caso'],$json_data[$key]['hora'],$json_data[$key]['ip']));
        }
        return $retorno;
    }
    

}
?>