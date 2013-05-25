'use strict';


// Declare app level module which depends on filters, and services
var mainmodule = angular.module('venderapp', [
    'venderapp.filters', 
    'venderapp.services', 
    'venderapp.directives',
    'ui',
    'services.authentication',
    'services.httpRequestTracker',
    'services.notifications',
    'services.i18nNotifications',
    'services.localizedMessages',
    'login']);

//TODO: move those messages to a separate module
angular.module('venderapp').constant('I18N.MESSAGES', {
  'errors.route.changeError':'Route change error',
  'crud.user.save.success':"El usuario con el id '{{id}}' fue guardado correctamente.",
  'crud.user.remove.success':"El usuario con el id '{{id}}' fue eliminado correctamente.",
  'crud.user.save.error':"Ocurrio un error al tratar de guardar el usuario...",
  'venta.save.success':"La venta del '{{date}}' fue agregada correctamente.",
  'login.error.notAuthorized':"No tiene permisos para acceder.  Desea ingresar como otro usuario?",
  'login.error.notAuthenticated':"Inicie sesión para acceder a esta aplicación."
});


 mainmodule.config(function($routeProvider,$locationProvider,$httpProvider) {
   
    var self = this;

    $routeProvider.
    when('/home', {templateUrl: 'client/partials/home.html', controller: HomeCtrl}).    
    when('/inbox', {templateUrl: 'client/partials/inbox.html', controller: InboxCtrl}).
    when('/mitienda', {templateUrl: 'client/partials/pos.html', controller: POSCtrl}).
    when('/ventas', {templateUrl: 'client/partials/ventas/ventas.html', controller: VentasCtrl}).
    when('/mercancias', {
        templateUrl: 'client/partials/articulos.html', 
        controller: MercanciasCtrl, 
        resolve: { mainmodule : ['AuthenticationService',function(AuthenticationService){
            return AuthenticationService.requireAuthenticatedUser();
          }] 
        }
    });

    //Proveedores
    $routeProvider.
    when('/proveedores', {templateUrl: 'client/partials/proveedores.html', controller: ProveedoresCtrl}).
    when('/proveedores/:ProveedorId', {templateUrl: 'client/partials/proveedores-detail.html', controller: ProveedoresDetailCtrl});    

    //Categorias
    $routeProvider.
    when('/categorias', {templateUrl: 'client/partials/categorias.html', controller: CategoriasCtrl});


    $routeProvider.when('/clientes', {templateUrl: 'client/partials/clientes.html', controller: ClientesCtrl});
    
    
    
    $routeProvider.when('/configuracion', {templateUrl: 'client/partials/configuracion/configuracion.html', controller: TiendaCtrl});
    $routeProvider.when('/mistiendas', {templateUrl: 'client/partials/configuracion/tiendas.html', controller: TiendaCtrl});
    $routeProvider.when('/usuarios', {templateUrl: 'client/partials/configuracion/usuarios.html', controller: TiendaCtrl});

    $routeProvider.otherwise({redirectTo: '/home'});


     
  });
  
  