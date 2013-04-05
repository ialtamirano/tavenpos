<?php

//development 

require '../localvendor/Slim/Slim.php';

require '../localvendor/paris/idiorm.php';
require '../localvendor/paris/paris.php';

require '../config/connection.php';

require '../localvendor/OAuth2/Storage/ScopeInterface.php';
require '../localvendor/OAuth2/Storage/SessionInterface.php';
require '../localvendor/OAuth2/Util/RequestInterface.php';
require '../localvendor/OAuth2/Util/Request.php';
require '../localvendor/OAuth2/Exception/OAuth2Exception.php';
require '../localvendor/OAuth2/Exception/InvalidAccessTokenException.php';
require '../localvendor/OAuth2/ResourceServer.php';

include 'models/model_scope.php';
include 'models/model_session.php';


$app = new Slim(array(
    'templates.path' => 'client/'
));



// Initiate the Request handler
$request = new \OAuth2\Util\Request();

// Initiate the auth server with the models
$server = new \OAuth2\ResourceServer(new SessionModel, new ScopeModel);


$checkToken = function () use ($server) {

    return function() use ($server)
    {
        // Test for token existance and validity
        try {
            $server->isValid();
        }
        // The access token is missing or invalid...
        catch (\OAuth2\Exception\InvalidAccessTokenException $e)
        {


            $app = Slim::getInstance();
            $res = $app->response();
            $res['Content-Type'] = 'application/json';
            $res->status(403);

            $res->body(json_encode(array(
                'error' =>  $e->getMessage()
            )));
        }
    };

};


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
$app->get('/', function () use ($app,$server) {
    
        $app->render('index.html');
    
});

// MySql
$app->get('/mysql/', function () use ($server,$app) {
   
            $app->render('../server/mysql/phpminiadmin.php');
      
  
});

// MySql
$app->get('/oauth/', function () use ($server,$app) {
   
            $app->render('../server/oauth/index.html');
      
  
});
$app->get('/oauth/php-oauth/', function () use ($server,$app) {
   
            $app->render('../server/oauth/php-oauth/www/index.html');
      
  
});

$app->get('/oauth/php-oauth-grades-rs/', function () use ($server,$app) {
   
            $app->render('../server/oauth/php-oauth-grades-rs/www/index.html');
      
  
});




//Proveedores Routes
require 'models/proveedor.php';
require 'api/proveedores.php';
/*$app->get('/api/proveedores/',$checkToken(), 'getProveedores') ;*/
$app->get('/api/proveedores/',function () use ($server,$app){

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

        $json = '{
            "user" : {  
                "id": "1",    
                "email": "ivan.altamirano@gmail.com", 
                "firstName": "ivan", 
                "lastName": "altamirano", 
                "admin": true    
            }
        } ';
        $app = Slim::getInstance();
        $res = $app->response();
        $res['Content-Type'] = 'application/json';
        $res->status(200);
        $res->body($json);


   
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
/*

*/


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