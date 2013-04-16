<?php

//development 

require '../localvendor/Slim/Slim.php';
require '../localvendor/paris/idiorm.php';
require '../localvendor/paris/paris.php';
require '../config/connection.php';
require 'oauthserver.php';



$app = new Slim(array(
    'templates.path' => 'client/'
));





function requestJson(){

    $app = Slim::getInstance();
    $request = $app->request();
    $body = $request->getBody();
    $data = json_decode($body);    
    return $data;

}

function responseJson ($json=null)  {
       
        $app = Slim::getInstance();
        $res = $app->response();
        $res['Content-Type'] = 'application/json';
        $res->status(200);
        $res->body($json);
};


// Define routes
$app->get('/', function () use ($app) {
    
        $app->render('index.html');
    
});

// MySql
$app->get('/mysql/', function () use ($app) {
   
            $app->render('../server/mysql/phpminiadmin.php');
      
  
});




//Proveedores Routes
require 'models/proveedor.php';
require 'api/proveedores.php';
/*$app->get('/api/proveedores/',$checkToken(), 'getProveedores') ;*/
$app->get('/api/proveedores/',function () use ($app){

        $app = Slim::getInstance();
        $res = $app->response();
        $res['Content-Type'] = 'application/json';
        $res->status(401);
}) ;


$app->get('/api/proveedores/:id','getProveedor');
$app->post('/api/proveedores/new','addProveedor');
$app->get('/api/proveedores/search/:query', 'findProveedorByName');
$app->post('/api/proveedores/:id', 'updateProveedor');
$app->delete('/api/proveedores/:id', 'deleteProveedor');


//Tiendas
require 'models/tienda.php';
require 'api/tiendas.php';
$app->get('/api/tiendas/','getTiendas');
$app->get('/api/tiendas/:id','getTienda');
$app->post('/api/tiendas/new','addTienda');
$app->post('/api/tiendas/:id','updateTienda');
$app->delete('/api/tiendas/:id','deleteTienda');

//test
$app->get('/api/tiendas/test',function () use ($app){

    $app-render('api/test/testtiendas.php');

});

$app->post('/api/login', function () use ($app){
    
    $app = Slim::getInstance();
    $request = $app->request();
    $res = $app->response();
    $body = $request->getBody();
    $data = json_decode($body);
    
    try{


        //Obtener los parametros
        $_POST['grant_type'] = 'password';
        $_POST['client_id'] = '2Kv9vbSb9yc311L3H9k4wPPJXna9o586';
        $_POST['client_secret'] = 'QIWaZ82U9vHs7p2G2J6OGHTgGcK3y9oz';
        $_POST['username'] = $data->email;
        $_POST['password'] = $data->password;
        $_POST['scope'] = 'access';

        $tokenInfo=PasswordGrantAuthentication($_POST);

        $user=array(
            'user' => array(
                'id'=>'1',
                'email'=>'ivan.altamirano@gmail.com',
                'firstName'=>'Ivan',
                'lastName'=> 'Altamirano',
                'admin' => 'true'
            ),
            'token'=> array(
                'access_token'=>$tokenInfo['access_token'],
                'token_type'=> $tokenInfo['token_type'],
                'expires' => $tokenInfo['expires'],
                'expires_in' => $tokenInfo['expires_in'],
                'refresh_token'=>$tokenInfo['refresh_token'],
            )
        );

        $json = json_encode($user);

        $res['Content-Type'] = 'application/json';
        $res->status(200);
        $res->body($json);

    }catch(Exception $ex){
        $json = '{"error":{"text":'. $ex->getMessage() .'}}';

        $res['Content-Type'] = 'application/json';
        $res->status(403);
        $res->body($json);
    }  
});

$app->get('/api/current-user', function() use ($app){

         $json = '{
            "user" : {  
                "id": "1",    
                "email": "ivan.altamirano@gmail.com", 
                "firstName": "ivan", 
                "lastName": "altamirano", 
                "admin": true    
            }
        } ';


        $json = null;
        $app = Slim::getInstance();
        $res = $app->response();
        $res['Content-Type'] = 'application/json';
        $res->status(200);
        $res->body($json);

});


//Categorias Routes
require 'models/articulo.php';
require 'models/categoria.php';

require 'api/categorias.php';
$app->get('/api/categorias/', 'getCategorias') ;
$app->get('/api/categorias/:id','getCategoria');
$app->get('/api/categorias/:id/articulos','getCategoriaArticulos');
$app->post('/api/categorias/new','addCategoria');
$app->get('/api/categorias/search/:query', 'findCategoriaByName');
$app->post('/api/categorias/:id', 'updateCategoria');
$app->delete('/api/categorias/:id', 'deleteCategoria');

//Articulos Routes

require 'api/articulos.php';
$app->get('/api/articulos/', 'getArticulos') ;
$app->get('/api/articulos/:id','getArticulo');
$app->post('/api/articulos/new','addArticulo');
$app->get('/api/articulos/search/:query', 'findArticuloByName');
$app->post('/api/articulos/:id', 'updateArticulo');
$app->delete('/api/articulos/:id', 'deleteArticulo');



//Clientes Routes
require 'models/cliente.php';
require 'api/clientes.php';
$app->get('/api/clientes/', 'getClientes') ;
$app->get('/api/clientes/:id','getCliente');
$app->post('/api/clientes/new','addCliente');
$app->get('/api/clientes/search/:query', 'findClienteByName');
$app->post('/api/clientes/:id', 'updateCliente');
$app->delete('/api/clientes/:id', 'deleteCliente');

//Ventas Routes
require 'models/venta.php';
require 'api/ventas.php';
$app->get('/api/ventas/', 'getVentas') ;
$app->get('/api/ventas/:id','getVenta');
$app->post('/api/ventas/new','addVenta');
$app->post('/api/ventas/:id', 'updateVenta');
$app->delete('/api/ventas/:id', 'deleteVenta');

//User routes
require 'server/models/user.php';
require 'server/controllers/usercontroller.php';
$app->get('/api/users/', function() use ($app){ 

    $user_ctrl = new UserController();
    $json = json_encode($user_ctrl->getUsers());
    responseJson($json);

}) ;

$app->get('/api/users/:id',function($id) use ($app){
    $user_ctrl = new UserController();
    $json = json_encode($user_ctrl->getUser($id));
    responseJson($json);
 });

$app->post('/api/users/new',function() use ($app){

    
    $app = Slim::getInstance();
    $request = $app->request();
    $body = $request->getBody();
    $data = json_decode($body);


    $user_ctrl = new UserController();
    $json =  json_encode($user_ctrl->addUser($data));

    responseJson($json);
});

$app->post('/api/users/:id', function($id) use ($app){ 

    
    $app = Slim::getInstance();
    $request = $app->request();
    $body = $request->getBody();
    
    $data = json_decode($body);

    $user_ctrl = new UserController();
    $json = json_encode($user_ctrl->updateUser($id,$data));
    
    responseJson($json);
     
});

$app->delete('/api/users/:id', function($id) use ($app){

    $user_ctrl = new UserController();
    $json =  json_encode($user_ctrl->deleteUser($id));
    responseJson($json);

});



// Run app
$app->run();


?>