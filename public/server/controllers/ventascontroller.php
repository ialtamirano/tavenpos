
<?php
function getVentas() {

    $ventas = Model::factory('Venta')->find_many();

    $data=array( );

    foreach ($ventas as $venta) {   
        $venta_array=$venta->as_array();
        array_push($data,$venta_array);    
    }
    
    echo json_encode($data);
    
}




function getVenta($id) {
        
    $venta = Model::factory('Venta')->find_one($id);
    
    if(!empty($venta)) echo json_encode($venta->as_array());

}

function addVenta() {
    $id = 0;
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $venta_json = json_decode($body);
    
    saveOrUpdateVenta($id, $venta_json);
   
}

function updateVenta($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $venta_json = json_decode($body);
    
    saveOrUpdateVenta($id, $venta_json);
   
}

function saveOrUpdateVenta($id, $venta_json)
{

    try{

        if(empty($id)){

            $venta = Model::factory('Venta')->create();
            $venta->CDate  = date("Y-m-d H:i:s");
            $venta->LMDate = date("Y-m-d H:i:s");

        }
        else{

            $venta = Model::factory('Venta')->find_one($id);
            $venta->LMDate = date("Y-m-d H:i:s");
        }
        
        $venta->VentaFecha=$venta_json->VentaFecha;
        if(isset($venta_json->VentaDescripcion)) {$venta->VentaDescripcion=$venta_json->VentaDescripcion;}
        else {   $venta->VentaDescripcion = "Registro de venta"; }
        if(isset($venta_json->VentaTotal)) $venta->VentaTotal=$venta_json->VentaTotal;
        if(isset($venta_json->VentaTipo)) $venta->VentaTipo=$venta_json->VentaTipo;
        if(isset($venta_json->VentaReferencia)) $venta->VentaReferencia=$venta_json->VentaReferencia;
        if(isset($venta_json->ClienteId)) $venta->ClienteId=$venta_json->ClienteId;
        
        
        $venta->save();
       
        echo json_encode($venta->as_array());  
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->__toString() .'}}'; 
    }
}

function deleteVenta($id) {
    try{
        $venta = Model::factory('Venta')->find_one($id);
        $venta->delete();
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

?>