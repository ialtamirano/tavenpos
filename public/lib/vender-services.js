 var Services =angular.module('venderapp.services', ['ngResource','services.authentication']).
  value('version', '0.1');

    Services.factory( 'Proveedor', function($resource){
        return $resource('api/proveedores/:ProveedorId',{},{});
    });

    
  
    Services.factory('Tienda', function($resource){
        return $resource('api/tiendas/:TiendaId',{},{}); 
    });
  
    Services.factory( 'Categoria', function($resource){
        return $resource('api/categorias/:CategoriaId',{},{});
    });
  
    Services.factory( 'Tienda', function($resource){
        return $resource('api/tiendas/:TiendaId',{},{});
    });
    
    Services.factory( 'Articulo', function($resource){
        return $resource('api/articulos/:ArticuloId',{},{});
    });
    
    Services.factory( 'Venta', function($resource){
        return $resource('api/ventas/:VentaId',{},{});
    });
    
    
    /*Services.factory('MyInterceptor',function($q,$cookies,$cookieStore,tokenHandler){
        return {
            request : function(config){
                console.log(tokenHandler.info)
                return config || $q.when(config);
            }
        }

    });*/

    
    /////Loading Services
    /*Services.factory('loadingService', function() {
        
        var service = {
            requestCount: 0,
            isLoading: function() {
                return service.requestCount > 0;
            }
        };
        
        return service;
    });
    
    Services.factory('onStartInterceptor', function(loadingService) {
        return function (data, headersGetter) {
            loadingService.requestCount++;
            return data;
        };
    });
    
    // This is just a delay service for effect!
    Services.factory('delayedPromise', function($q, $timeout){
        return function(promise, delay) {
            var deferred = $q.defer();
            var delayedHandler = function() {        
                $timeout(function() { deferred.resolve(promise); }, delay);
            };
            
            promise.then(delayedHandler, delayedHandler);
            
            return deferred.promise;
        };
    });


    Services.factory('onCompleteInterceptor', function(loadingService,delayedPromise) {
        return function(promise) {
            var decrementRequestCount = function(response) {
                loadingService.requestCount--;
                if ( response.status == 401) {
                    alert('no authorizado')
                    return null;
                }  
                return response;
            };
        
            // Normally we would just chain on to the promise but ...
            return promise.then(decrementRequestCount, decrementRequestCount);
            // ... we are delaying the response by 2 secs to allow the loading to be seen.
            //return delayedPromise(promise, 2000).then(decrementRequestCount, decrementRequestCount);
        };
    });*/
