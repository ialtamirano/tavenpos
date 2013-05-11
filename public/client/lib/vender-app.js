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
  'crud.user.save.success':"A user with id '{{id}}' was saved successfully.",
  'crud.user.remove.success':"A user with id '{{id}}' was removed successfully.",
  'crud.user.save.error':"Something went wrong when saving a user...",
  'crud.project.save.success':"A project with id '{{id}}' was saved successfully.",
  'crud.venta.save.success':"A sale with date '{{date}}' was saved successfully.",
  'crud.project.remove.success':"A project with id '{{id}}' was removed successfully.",
  'crud.project.save.error':"Something went wrong when saving a project...",

  'login.error.notAuthorized':"You do not have the necessary access permissions.  Do you want to login as someone else?",
  'login.error.notAuthenticated':"Inicia sesión para acceder a esta aplicación."
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
  
  