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
  'crud.venta.save.success':"A sale with id '{{id}}' was saved successfully.",
  'crud.project.remove.success':"A project with id '{{id}}' was removed successfully.",
  'crud.project.save.error':"Something went wrong when saving a project...",

  'login.error.notAuthorized':"You do not have the necessary access permissions.  Do you want to login as someone else?",
  'login.error.notAuthenticated':"You must be logged in to access this part of the application."
});


 mainmodule.config(function($routeProvider,$locationProvider,$httpProvider) {
   
    var self = this;
    //por ejemplo, 
    $routeProvider.
    when('/home', {templateUrl: 'partials/home.html', controller: HomeCtrl}).    
    when('/inbox', {templateUrl: 'partials/inbox.html', controller: InboxCtrl}).
    when('/mitienda', {templateUrl: 'partials/mitienda.html', controller: POSCtrl}).
    when('/ventas', {templateUrl: 'partials/ventas/ventas.html', controller: VentasCtrl}).
    when('/mercancias', {
        templateUrl: 'partials/mercancias.html', 
        controller: MercanciasCtrl, 
        resolve: { mainmodule : ['AuthenticationService',function(AuthenticationService){
            return AuthenticationService.requireAdminUser();
        }] 
        }
    });

    //Proveedores
    $routeProvider.
    when('/proveedores', {templateUrl: 'partials/proveedores.html', controller: ProveedoresCtrl}).
    when('/proveedores/:ProveedorId', {templateUrl: 'partials/proveedores-detail.html', controller: ProveedoresDetailCtrl});    

    //Categorias
    $routeProvider.
    when('/categorias', {templateUrl: 'partials/categorias.html', controller: CategoriasCtrl});

    //Compras
    $routeProvider.when('/compras', {templateUrl: 'partials/compras.html', controller: ComprasCtrl});


    $routeProvider.when('/reportes', {templateUrl: 'partials/reportes.html', controller: ReportesCtrl});
    $routeProvider.when('/clientes', {templateUrl: 'partials/clientes.html', controller: ClientesCtrl});
    $routeProvider.when('/clientes/new', {templateUrl: 'partials/clientes-modal.html', controller: ClientesNewCtrl});
    $routeProvider.when('/clientes/edit/:id', {templateUrl: 'partials/clientes-modal.html', controller: ClientesEditCtrl});
    $routeProvider.when('/clientes/delete/:id', {templateUrl: 'partials/clientes-modal.html', controller: ClientesEditCtrl});
    
    
    $routeProvider.when('/configuracion', {templateUrl: 'partials/configuracion/configuracion.html', controller: TiendaCtrl});
    $routeProvider.when('/mistiendas', {templateUrl: 'partials/configuracion/tiendas.html', controller: TiendaCtrl});

    $routeProvider.otherwise({redirectTo: '/home'});
    
    //$httpProvider.responseInterceptors.push('onCompleteInterceptor');
    
  });
  
 /* mainmodule.run(function($http, onStartInterceptor) {
  $http.defaults.transformRequest.push(onStartInterceptor);
});*/