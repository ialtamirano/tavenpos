<?php
/*CREATE TABLE `articulos` (
  `ArticuloId` int(11) NOT NULL AUTO_INCREMENT,
  `ArticuloCodigo` varchar(45) NOT NULL,
  `ArticuloNombre` varchar(45) NOT NULL,
  `CategoriaId` int(11) DEFAULT NULL,
  `IvaId` int(11) NOT NULL,
  `ArticuloPeso` decimal(10,0) DEFAULT NULL,
  `ArticuloBarcode` varchar(45) DEFAULT NULL,
  `ArticuloFoto` varchar(400) DEFAULT NULL,
  `ArticuloDescripcion` varchar(200) DEFAULT NULL,
  `ArticuloParentId` int(11) DEFAULT NULL,
  `ArticuloTipo` char(1) DEFAULT NULL,
  PRIMARY KEY (`ArticuloId`),  
  KEY `categoriasFK2` (`CategoriaId`),
  CONSTRAINT `categoriasFK2` FOREIGN KEY (`CategoriaId`) REFERENCES `categorias` (`CategoriaId`) 
) ENGINE=InnoDB DEFAULT CHARSET=latin1*/


function getArticulos()
{
    
    $articulos = Model::factory('Articulo')->find_many();
    
    $data=array();

    foreach ($articulos as $articulo) 
    {    

     

      $articulos_array = $articulo->as_array();

      $articulos_array['categoria'] = getArticuloCategoriaAsArray($articulo);

      

      array_push( $data,$articulos_array);
    }
    
    echo json_encode($data);

}

function getArticulo($id){
     $articulo = Model::factory('Articulo')->find_one($id);
    
    if(!empty($articulo)) echo json_encode($articulo->as_array());
}

function addArticulo(){
    $id = 0;
    $request = Slim::getInstance()->request();
    
    $body = $request->getBody();
    $articulo_json = json_decode($body);
    
    saveOrUpdateArticulo($id, $articulo_json);
   
}

function updateArticulo($id)
{
    $request = Slim::getInstance()->request();
    $body = $request->getBody();    
    
    $articulo_json = json_decode($body);
    
    
    saveOrUpdateArticulo($id, $articulo_json);

}

function updateArticuloImages($id)
{
    try {


        $request = Slim::getInstance()->request();
        $body = $request->getBody();    

        
        $articulo = Model::factory('Articulo')->find_one($id);


         if(isset($_FILES["imageOriginal"])){
               
               if(move_uploaded_file($_FILES["imageOriginal"]["tmp_name"],UPLOAD_IMAGES_PATH.$_FILES['imageOriginal']["name"])){
                  $articulo->ArticuloFoto=UPLOAD_IMAGES_PATH.$_FILES['imageOriginal']["name"];
               }

            }

            if(isset($_FILES["image"])){
               
               if(move_uploaded_file($_FILES["image"]["tmp_name"],UPLOAD_IMAGES_PATH.'min'.$_FILES['image']["name"])){
                  $articulo->ArticuloMiniatura=UPLOAD_IMAGES_PATH.'min'.$_FILES['image']["name"];
               }

            }

         $articulo->save();
         $articulos_array = $articulo->as_array();
         $articulos_array['categoria'] = getArticuloCategoriaAsArray($articulo);

         echo json_encode($articulos_array);  

      }
    catch(Exception $e){

      echo '{"error":{"text":'. $e->getMessage() .'}}'; 

    }

}
function saveOrUpdateArticulo($id,$articulo_json)
{
     try
    {
        if(empty($id)){
        
            $articulo = Model::factory('Articulo')->create();            
            
        }
        else{
        
            $articulo = Model::factory('Articulo')->find_one($id);
        }
      

        
        if(isset($articulo_json->ArticuloCodigo)) $articulo->ArticuloCodigo=$articulo_json->ArticuloCodigo;
        if(isset($articulo_json->ArticuloNombre)) $articulo->ArticuloNombre=$articulo_json->ArticuloNombre;
        if(isset($articulo_json->CategoriaId)) $articulo->CategoriaId=$articulo_json->CategoriaId;
        if(isset($articulo_json->IvaId)) $articulo->IvaId=$articulo_json->IvaId;
        if(isset($articulo_json->ArticuloPeso)) $articulo->ArticuloPeso=$articulo_json->ArticuloPeso;
        if(isset($articulo_json->ArticuloBarcode)) $articulo->ArticuloBarcode=$articulo_json->ArticuloBarcode;
        if(isset($articulo_json->ArticuloDescripcion)) $articulo->ArticuloDescripcion=$articulo_json->ArticuloDescripcion;
        if(isset($articulo_json->ArticuloParentId)) $articulo->ArticuloParentId=$articulo_json->ArticuloParentId;
        if(isset($articulo_json->ArticuloTipo)) $articulo->ArticuloTipo=$articulo_json->ArticuloTipo;
        if(isset($articulo_json->ArticuloPrecio)) $articulo->ArticuloPrecio=$articulo_json->ArticuloPrecio;
          
        $articulo->save();
        $articulos_array = $articulo->as_array();
        $articulos_array['categoria'] = getArticuloCategoriaAsArray($articulo);

        echo json_encode($articulos_array);  
        
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}

function deleteArticulo($id)
{

     try{
        $articulo = Model::factory('Articulo')->find_one($id);
        $articulo->delete();
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function getArticuloCategoriaAsArray($articulo)
{
  $categoria = $articulo->categoria()->find_one();

  if(!empty($categoria))
      { 
        return $categoria->as_array();
      }

      return null ;

}

function findArticuloByName($query)
{
    try {
        
        $articulos = Model::factory('Articulo')
        ->where_like('ArticuloNombre', $query)    
        ->find_many();
        
        $data=array();

        foreach ($articulos as $articulo) {    
                $articulos_array = $articulo->as_array();
                array_push( $data,$articulos_array  );
        }
        
        echo json_encode($data);
        
        
    } catch(Exception $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }

}
?>