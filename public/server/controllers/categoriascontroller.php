<?php
function getCategorias() {

    
    $categorias = Model::factory('Categoria')->find_many();
    
    $data=array();

    foreach ($categorias as $categoria) {    
            $categorias_array = $categoria->as_array();
            array_push( $data,$categorias_array  );
    }
    
    echo json_encode($data);
    
    
}

function getCategoria($id) {
   /* $sql = "SELECT * FROM categorias WHERE categoriaId=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $categoria = $stmt->fetchObject();
        $db = null;
        echo json_encode($categoria);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }*/
    
    $categoria = Model::factory('Categoria')->find_one($id);
    
    if(!empty($categoria)) echo json_encode($categoria->as_array());
}

function getCategoriaArticulos($id){

    $categoria = Model::factory('Categoria')->find_one($id);
    
    if(!empty($categoria)){
        $articulos =  $categoria->articulos()->find_many();
        
        $data=array();
        
        foreach ($articulos as $articulo) {    
            $articulos_array = $articulo->as_array();
            array_push( $data,$articulos_array  );
        }
    
        echo json_encode($data);
    } 
}



//INSERT INTO `tavenposdb`.`categoria` (`categoriaNombre`, `categoriaDescripcion`)

function addCategoria() {
   
    
    $id = 0;
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $categoria_json = json_decode($body);
    
    saveOrUpdateCategoria($id, $categoria_json);
}


function updateCategoria($id) {

    
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $categoria_json = json_decode($body);
    
    saveOrUpdateCategoria($id, $categoria_json);
}


function saveOrUpdateCategoria($id, $categoria_json)
{

    try{
    if(empty($id)){
        $categoria = Model::factory('Categoria')->create();
    }
    else{
        $categoria = Model::factory('Categoria')->find_one($id);
    }
    
    
    $categoria->CategoriaNombre=$categoria_json->CategoriaNombre;
    if(isset($categoria_json->CategoriaDescripcion)) $categoria->CategoriaDescripcion=$categoria_json->CategoriaDescripcion;

    
    $categoria->save();
    
    echo json_encode($categoria->as_array());  
    }
    catch(Exception $e)
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}



function deleteCategoria($id) {

    try{
        $categoria = Model::factory('Categoria')->find_one($id);
        $categoria->delete();
    }catch(Exception $e)
    {
         echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function findCategoriaByName($query) {
  
    
     try {
        
        $categorias = Model::factory('Categoria')
        ->where_like('CategoriaNombre', $query)    
        ->find_many();
        
        $data=array();

        foreach ($categorias as $categoria) {    
                $categorias_array = $categoria->as_array();
                array_push( $data,$categorias_array  );
        }
        
        echo json_encode($data);
        
        
    } catch(Exception $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
    
}
/*
    $app->get('/api/categorias/', 'getCategorias') ;
    $app->get('/api/categorias/:id','getCategoria');
    $app->post('/api/categorias/','addCategoria');
    $app->get('/api/categorias/search/:query', 'findCategoriaByName');
    $app->put('/api/categorias/:id', 'updateCategoria');
    $app->delete('/api/categorias/:id', 'deleteCategoria');
*/

?>