<?php

//development 
require '../Slim/Slim.php';
//require '../vendor/redbean/rb.php';
require '../vendor/php-activerecord/activerecord.php';


//production
//require '../vendor/autoload.php';


require '../paris/idiorm.php';
require '../paris/paris.php';

require '../config/connection.php';






$app = new Slim(array(
    'templates.path' => 'application/'
));



// Prepare view
/*
$twigView = new View_Twig();
$twigView->twigOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../var/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view($twigView);

*/



// Define routes
$app->get('/', function () use ($app) {
    $app->render('index.html');
});

//MySql
$app->get('/mysql/', function () use ($app) {
    $app->render('mysql/phpminiadmin.php');
});



//Proveedores Routes
require 'models/proveedor.php';
require 'api/proveedores.php';
$app->get('/api/proveedores/', 'getProveedores') ;
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




// Run app
$app->run();


?>