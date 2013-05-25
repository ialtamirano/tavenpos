<?php
function getContactos() {

    
 $contactos = Model::factory('Contacto')->find_many();
    
    $data=array();

    foreach ($contactos as $contacto) {    
            $contactos_array = $contacto->as_array();
            array_push( $data,$contactos_array  );
    }
    
    echo json_encode($data);
    
    
}

function getContacto($id) {
    
    $contacto = Model::factory('Contacto')->find_one($id);
    
    if(!empty($contacto)) echo json_encode($contacto->as_array());
}



function addContacto() {
    $id = 0;
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $contacto_json = json_decode($body);
    
    saveOrUpdateContacto($id, $contacto_json);
   
}




function updateContacto($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $contacto_json = json_decode($body);
    
    saveOrUpdateContacto($id, $contacto_json);
   
}

function saveOrUpdateContacto($id, $contacto_json)
{

    try{
    if(empty($id)){
        $contacto = Model::factory('Contacto')->create();
    }
    else{
        $contacto = Model::factory('Contacto')->find_one($id);
    }
    
    
    $contacto->ContactoNombre=$contacto_json->ContactoNombre;

    if(isset($contacto_json->ContactoTelefono)) $contacto->ContactoTelefono=$contacto_json->ContactoTelefono;
    if(isset($contacto_json->ContactoEmail)) $contacto->ContactoEmail=$contacto_json->ContactoEmail;
    if(isset($contacto_json->ContactoDireccion)) $contacto->ContactoDireccion=$contacto_json->ContactoDireccion;
    if(isset($contacto_json->ContactoCP)) $contacto->ContactoCP=$contacto_json->ContactoCP;
    if(isset($contacto_json->ContactoCiudad)) $contacto->ContactoCiudad=$contacto_json->ContactoCiudad;
    if(isset($contacto_json->ContactoEstado)) $contacto->ContactoEstado=$contacto_json->ContactoEstado;
    if(isset($contacto_json->ContactoPais)) $contacto->ContactoPais=$contacto_json->ContactoPais;
    if(isset($contacto_json->ContactoRegistro)) $contacto->ContactoRegistro=$contacto_json->ContactoRegistro;
    if(isset($contacto_json->ContactoURL)) $contacto->ContactoURL=$contacto_json->ContactoURL;
    if(isset($contacto_json->ContactoNotas)) $contacto->ContactoNotas=$contacto_json->ContactoNotas;
    
    
    $contacto->save();
    
    echo json_encode($contacto->as_array());  
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}

function deleteContacto($id) {
    try{
        $contacto = Model::factory('Contacto')->find_one($id);
        $contacto->delete();
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function findContactoByName($query) {

    try {
        
        $contactos = Model::factory('Contacto')
        ->where_like('ContactoNombre', $query)    
        ->find_many();
        
        $data=array();

        foreach ($contactos as $contacto) {    
                $contactos_array = $contacto->as_array();
                array_push( $data,$contactoes_array  );
        }
        
        echo json_encode($data);
        
        
    } catch(Exception $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
    

}


?>