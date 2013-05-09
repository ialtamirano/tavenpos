<?php
/*CREATE TABLE `tienda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TiendaNombre` varchar(45) NOT NULL,
  `TiendaTelefono` varchar(25) DEFAULT NULL,
  `TiendaEmail` varchar(120) DEFAULT NULL,
  `TiendaDireccion` varchar(50) DEFAULT NULL,
  `TiendaCP` varchar(5) DEFAULT NULL,
  `TiendaCiudad` varchar(45) DEFAULT NULL,
  `TiendaPais` varchar(45) DEFAULT NULL,
  `TiendaSeriePref` varchar(5) NOT NULL,
  `TiendaSerieNum` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
*/

function getTiendas(){

    $tiendas = Model::factory('Tienda')->find_many();
    
    $data=array();

    foreach ($tiendas as $tienda) {    
            $tienda_array = $tienda->as_array();
            array_push( $data,$tienda_array  );
    }
    
    echo json_encode($data);

}

function getTienda($id){
    $tienda = Model::factory('Tienda')->find_one($id);
    
    if(!empty($tienda)) echo json_encode($tienda->as_array());
}

function addTienda(){
    $id = 0;
    $request = Slim::getInstance()->request();
    $body = $request->getBody();    
    $tienda_json = json_decode($body);
    
    saveOrUpdateTienda($id,$tienda_json);
     
    
    
}

function updateTienda($id)
{
    $request = Slim::getInstance()->request();
    $body = $request->getBody();    
    $tienda_json = json_decode($body);
    
    saveOrUpdateTienda($id,$tienda_json);
    
       
}

function saveOrUpdateTienda($id,$tienda_json)
{
    try{
            if(empty($id)){
            
                 $tienda = Model::factory('Tienda')->create();
            }
            else{
                $tienda = Model::factory('Tienda')->find_one($id);
            }
            
            if(isset($tienda_json->TiendaNombre)) $tienda->TiendaNombre = $tienda_json->TiendaNombre;
            if(isset($tienda_json->TiendaTelefono)) $tienda->TiendaTelefono=$tienda_json->TiendaTelefono;
            if(isset($tienda_json->TiendaEmail)) $tienda->TiendaEmail= $tienda_json->TiendaEmail;
            if(isset($tienda_json->TiendaDireccion)) $tienda->TiendaDireccion=$tienda_json->TiendaDireccion;
            if(isset($tienda_json->TiendaCP)) $tienda->TiendaCP=$tienda_json->TiendaCP;
            if(isset($tienda_json->TiendaCiudad)) $tienda->TiendaCiudad=$tienda_json->TiendaCiudad;
            if(isset($tienda_json->TiendaPais)) $tienda->TiendaPais=$tienda_json->TiendaPais;
            if(isset($tienda_json->TiendaSeriePref)) $tienda->TiendaSeriePref=$tienda_json->TiendaSeriePref;
            if(isset($tienda_json->TiendaSerieNum)) $tienda->TiendaSerieNum = $tienda_json->TiendaSerieNum;
            
            $tienda->save();
            
            echo json_encode($tienda->as_array());  
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}

function deleteTienda($id)
{
    $tienda = Model::factory('Tienda')->find_one($id);
    $tienda->delete();
}

?>
