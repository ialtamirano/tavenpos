<?php
//Todavia no esta al 100
/* Este es el script,

CREATE TABLE `cliente` (
  `ClienteId` int(11) NOT NULL AUTO_INCREMENT,
  `ClienteNombre` varchar(30) NOT NULL,
  `ClienteApellido` varchar(30) DEFAULT NULL,
  `ClienteFechaNacimiento` datetime DEFAULT NULL,
  `ClienteGenero` char(1) DEFAULT NULL,
  `ClienteTelefono` varchar(25) DEFAULT NULL,
  `ClienteEmail` varchar(120) DEFAULT NULL,
  `ClienteDireccion` varchar(50) DEFAULT NULL,
  `ClienteCP` varchar(5) DEFAULT NULL,
  `ClienteCiudad` varchar(45) DEFAULT NULL,
  `ClientePais` varchar(45) DEFAULT NULL,
  `ClienteRFC` varchar(15) DEFAULT NULL,
  `ClienteFoto` varchar(400) DEFAULT NULL,
  `ClienteNotas` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ClienteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

*/

function getClientes() {

    
    $clientes = Model::factory('Cliente')->find_many();
    
    $data=array();

    foreach ($clientes as $cliente) {    
            $clientes_array = $cliente->as_array();
            array_push( $data,$clientes_array  );
    }
    
    echo json_encode($data);

    
}

function getCliente($id) {

     
    $cliente = Model::factory('Cliente')->find_one($id);
    
    if(!empty($cliente)) echo json_encode($cliente->as_array());
}



function addCliente() {
    $id = 0;
    $request = Slim::getInstance()->request();
    $body = $request->getBody();    
    $cliente_json = json_decode($body);
    
    saveOrUpdateCliente($id, $cliente_json);
}

function updateCliente($id) {
    
    $request = Slim::getInstance()->request();
    $body = $request->getBody();    
    $cliente_json = json_decode($body);
    
    saveOrUpdateCliente($id, $cliente_json);
}



function saveOrUpdateCliente($id, $cliente_json)
{

    try
    {
        if(empty($id)){
        
            $cliente = Model::factory('Cliente')->create();            
            
        }
        else{
        
            $cliente = Model::factory('Cliente')->find_one($id);
        }
        
        
        $cliente->ClienteNombre=$cliente_json->ClienteNombre;
        if(isset($cliente_json->ClienteApellido)) $cliente->ClienteApellido=$cliente_json->ClienteApellido;
        if(isset($cliente_json->ClienteFechaNacimiento))$cliente->ClienteFechaNacimiento=$cliente_json->ClienteFechaNacimiento;
        if(isset($cliente_json->ClienteGenero)) $cliente->ClienteGenero= $cliente_json->ClienteGenero;
        if(isset($cliente_json->ClienteTelefono))  $cliente->ClienteTelefono=$cliente_json->ClienteTelefono;
        if(isset($cliente_json->ClienteEmail)) $cliente->ClienteEmail=$cliente_json->ClienteEmail;
        if(isset($cliente_json->ClienteDireccion)) $cliente->ClienteDireccion=$cliente_json->ClienteDireccion;
        if(isset($cliente_json->ClienteCP)) $cliente->ClienteCP=$cliente_json->ClienteCP;
        if(isset($cliente_json->ClienteCiudad)) $cliente->ClienteCiudad=$cliente_json->ClienteCiudad;
        if(isset($cliente_json->ClientePais)) $cliente->ClientePais=$cliente_json->ClientePais;
        if(isset($cliente_json->ClienteRFC)) $cliente->ClienteRFC=$cliente_json->ClienteRFC;
        if(isset($cliente_json->ClienteFoto)) $cliente->ClienteFoto=$cliente_json->ClienteFoto;
        
        $cliente->save();
        
        echo json_encode($cliente->as_array());  
        
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
    

}


function deleteCliente($id) {
    try{        
        $cliente = Model::factory('Cliente')->find_one($id);
        $cliente->delete();
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function findClienteByName($query) {

    try {
        
        $clientes = Model::factory('Cliente')
        ->where_like('ClienteNombre', $query)    
        ->find_many();
        
        $data=array();

        foreach ($clientes as $cliente) {    
                $clientes_array = $cliente->as_array();
                array_push( $data,$clientes_array  );
        }
        
        echo json_encode($data);
        
        
    } catch(Exception $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
    

}
/*
    $app->get('/api/clientes/', 'getClientes') ;
    $app->get('/api/clientes/:id','getCliente');
    $app->post('/api/clientes/','addCliente');
    $app->get('/api/clientes/search/:query', 'findClienteByName');
    $app->put('/api/clientes/:id', 'updateCliente');
    $app->delete('/api/clientes/:id', 'deleteCliente');
*/

?>