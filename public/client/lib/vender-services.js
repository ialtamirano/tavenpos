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

    Services.factory( 'POS', function($resource){
        return $resource('api/pos/:VentaId',{},{});
    });
    
    
 
