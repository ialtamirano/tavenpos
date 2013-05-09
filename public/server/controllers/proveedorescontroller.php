<?php
function getProveedores() {

    
 $proveedores = Model::factory('Proveedor')->find_many();
    
    $data=array();

    foreach ($proveedores as $proveedor) {    
            $proveedores_array = $proveedor->as_array();
            array_push( $data,$proveedores_array  );
    }
    
    echo json_encode($data);
    
    
}

function getProveedor($id) {
    
    $proveedor = Model::factory('Proveedor')->find_one($id);
    
    if(!empty($proveedor)) echo json_encode($proveedor->as_array());
}



function addProveedor() {
    $id = 0;
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $proveedor_json = json_decode($body);
    
    saveOrUpdateProveedor($id, $proveedor_json);
   
}




function updateProveedor($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $proveedor_json = json_decode($body);
    
    saveOrUpdateProveedor($id, $proveedor_json);
   
}

function saveOrUpdateProveedor($id, $proveedor_json)
{

    try{
    if(empty($id)){
        $proveedor = Model::factory('Proveedor')->create();
    }
    else{
        $proveedor = Model::factory('Proveedor')->find_one($id);
    }
    
    
    $proveedor->ProveedorNombre=$proveedor_json->ProveedorNombre;
    if(isset($proveedor_json->ProveedorRFC)) $proveedor->ProveedorRFC=$proveedor_json->ProveedorRFC;
    if(isset($proveedor_json->ProveedorTelefono)) $proveedor->ProveedorTelefono=$proveedor_json->ProveedorTelefono;
    if(isset($proveedor_json->ProveedorEmail)) $proveedor->ProveedorEmail=$proveedor_json->ProveedorEmail;
    if(isset($proveedor_json->ProveedorDireccion)) $proveedor->ProveedorDireccion=$proveedor_json->ProveedorDireccion;
    if(isset($proveedor_json->ProveedorCP)) $proveedor->ProveedorCP=$proveedor_json->ProveedorCP;
    if(isset($proveedor_json->ProveedorCiudad)) $proveedor->ProveedorCiudad=$proveedor_json->ProveedorCiudad;
    if(isset($proveedor_json->ProveedorPais)) $proveedor->ProveedorPais=$proveedor_json->ProveedorPais;
    if(isset($proveedor_json->ProveedorRegistro)) $proveedor->ProveedorRegistro=$proveedor_json->ProveedorRegistro;
    if(isset($proveedor_json->ProveedorWebSite)) $proveedor->ProveedorWebSite=$proveedor_json->ProveedorWebSite;
    if(isset($proveedor_json->ProveedorNotas)) $proveedor->ProveedorNotas=$proveedor_json->ProveedorNotas;
    if(isset($proveedor_json->ProveedorMapMarkers)) $proveedor->ProveedorMapMarkers = $proveedor_json->ProveedorMapMarkers;
    
    $proveedor->save();
    
    echo json_encode($proveedor->as_array());  
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}

function deleteProveedor($id) {
    try{
        $proveedor = Model::factory('Proveedor')->find_one($id);
        $proveedor->delete();
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function findProveedorByName($query) {

    try {
        
        $proveedores = Model::factory('Proveedor')
        ->where_like('ProveedorNombre', $query)    
        ->find_many();
        
        $data=array();

        foreach ($proveedores as $proveedor) {    
                $proveedores_array = $proveedor->as_array();
                array_push( $data,$proveedores_array  );
        }
        
        echo json_encode($data);
        
        
    } catch(Exception $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
    

}


?>