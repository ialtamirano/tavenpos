
<?php
function getVentas() {


    $ventas = Venta::find('all');

    $data=array( );

    foreach ($ventas as $venta) {    
        array_push($data,$venta->to_array());    
    }
    
    echo json_encode($data);
    
}

function getVenta($id) {
    
    $venta = Venta::find($id);//factory('Venta')->find_one($id);
    //var_dump($venta);
    if(!empty($venta)) echo json_encode($venta->to_array());
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

            $venta = new Venta();//::factory('Venta')->create();
            $venta->cdate  = date("Y-m-d H:i:s");
            $venta->lmdate = date("Y-m-d H:i:s");

        }
        else{

            $venta = Venta::find($id); //->find_one($id);
            $venta->lmdate = date("Y-m-d H:i:s");
        }

        //print_r($venta_json);
        
        $venta->ventafecha=$venta_json->ventafecha;
        if(isset($venta_json->ventadescripcion)) $venta->ventadescripcion=$venta_json->ventadescripcion;
        if(isset($venta_json->ventatotal)) $venta->ventatotal=$venta_json->ventatotal;
        if(isset($venta_json->ventatipo)) $venta->ventatipo=$venta_json->ventatipo;
        if(isset($venta_json->ventareferencia)) $venta->ventaReferencia=$venta_json->ventareferencia;
        if(isset($venta_json->clienteid)) $venta->clienteid=$venta_json->clienteid;
        
        
        $venta->save();
       
        echo json_encode($venta->to_array());  
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->__toString() .'}}'; 
    }
}

function deleteVenta($id) {
    try{
        $venta = Venta::find($id);//->find_one($id);
        $venta->delete();
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

?>