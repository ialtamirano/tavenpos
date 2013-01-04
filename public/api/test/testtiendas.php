<?php
$data = array(  
                "TiendaNombre" => "Tienda1", 
                "TiendaEmail"  => "tienda1@correo.com",
                "TiendaTelefono" => "622855",
                "TiendaDireccion" =>"direccion",
                "TiendaCP" => "85400",
               
                "TiendaPais" => "Mexico",
                "TiendaSeriePref" => "serie",
                "TiendaSerieNum" => "serienum"
            );      
            
$data_string = json_encode($data);       


 
$ch = curl_init('http://tavenpos.pagodabox.com/api/tiendas/new');                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
 
$result = curl_exec($ch);
echo $result;
?>