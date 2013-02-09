'use strict';

/* Controllers */

function AppCtrl($scope, $location, $resource, $rootScope)
{
    $scope.$location = $location;


    $scope.$watch('$location.path()', function(path) {
        return $scope.activeNavId = path || '/';
    });
    
    return $scope.getClass = function(id) {
      if($scope.activeNavId != undefined ){
          if ($scope.activeNavId.substring(0, id.length) === id) {
            return 'active';
          } 
          else {
            return '';
          }
      }


    };
}

function LoadingCtrl($scope,loadingService)
{
    $scope.$watch( function() { return loadingService.isLoading(); }, function(value) { $scope.loading = value; });
    
}

function HomeCtrl() {

}
HomeCtrl.$inject = [];


function DashboardCtrl(){


}
DashboardCtrl.$inject=[];

function InboxCtrl()
{
    
}

InboxCtrl.$inject = [];

function  POSCtrl($scope,Categoria)
{
    $scope.categorias =Categoria.query();
    $scope.venta = {
        currentArticulo : { ArticuloId : 1,ArticuloDescripcion: "Articulo Actual", ArticuloCantidad: 10 , ArticuloPrecio: 100.00 },
        articulos :[{ "ArticuloId": "1","ArticuloDescripcion": "Articulo 1", "ArticuloCantidad" : 10, "ArticuloPrecio": "100.00"},{ "ArticuloId": "2","ArticuloDescripcion": "Articulo 2", "ArticuloCantidad" : 20, "ArticuloPrecio": "55.00"},                        { "ArticuloId": "3","ArticuloDescripcion": "Articulo 3", "ArticuloCantidad" : 5, "ArticuloPrecio": "145.00"},{ "ArticuloId": "4","ArticuloDescripcion": "Articulo 4", "ArticuloCantidad" : 20, "ArticuloPrecio": "199.00"},{ "ArticuloId": "5","ArticuloDescripcion": "Articulo 5", "ArticuloCantidad" : 30, "ArticuloPrecio": "25.00"}],
        iva : 16
    };
    
    $scope.agregaArticulo = function()
    {
        var articulo = angular.copy($scope.venta.currentArticulo);
        
        $scope.venta.articulos.push(articulo);
    };
    
    $scope.removerArticulo = function(index) {        
        $scope.venta.articulos.splice(index,1);
    };
   
    $scope.total = function(){
        var total = 0;
        total = $scope.subtotal() + $scope.iva();
        return total;
    };
    
    
    $scope.iva = function () {
        var total = 0;
        total = $scope.subtotal() * ($scope.venta.iva / 100) ;
        return total;
    };
    
    $scope.subtotal = function() {
        var total = 0;
        angular.forEach($scope.venta.articulos, function(articulo) {
            total += articulo.ArticuloCantidad * articulo.ArticuloPrecio;
        });

        return total;
    };
}


function VentasCtrl($scope,$filter,Venta)
{

    $scope.ventas = [];
    $scope.filteredVentas = [];
    $scope.currentMonth=new Date().getMonth();
    
    $scope.months = ['Ene','Feb','Mar','Abr','May','Jun',
                     'Jul','Ago','Sep','Oct','Nov','Dic'];
    $scope.year = 2012;
    

    $scope.search = {
         ventafecha : '2012-09'
    };

    Venta.query(function(response){
      
        $scope.ventas = response;

    });
    
    
    $scope.setSelectedMonth=function(index){
        var strMonth= index+1;

        if(index<9){
            strMonth='0' + (index +1);
        }

        $scope.search.ventafecha = $scope.year + '-' + strMonth ;
    }
    
    $scope.nuevaVenta = new Venta({VentaId:0});
    
    
    $scope.update = function(venta){ 
        $scope.venta = venta;
        $scope.modalShown = true;
    };
    
    $scope.totalize = function() {
        var t = 0;
        if($scope.filteredVentas.length > 0){

            angular.forEach($scope.filteredVentas, function(venta1) {
                t += venta1.ventatotal;
            });
        
        }
        
        return t;
    };
    

    $scope.removeVenta = function (venta)
    {
        Venta.remove({VentaId : venta.ventaid},
                        function(data, status, headers, config) { 
                            //Handle Success here
                            var index = $scope.ventas.indexOf(venta,0); 
                            
                            if (index != -1 ) $scope.ventas.splice(index,1) ;   
     
                            $scope.errorMessage = "La venta se elimino correctamente.";
                            
                        },
                        function(data, status, headers, config ){
                            //Handle error here
                            $scope.errorMessage = "Ocurrio un error al eliminar";
                        });
         
    };  
    

    
    $scope.updateVenta = function(){
        
        $scope.venta.$save({VentaId:$scope.venta.ventaid } ,function(){
                                //Si el valor del resource agregado trajo algun valor de regreso entonces nos regresamos, 
                                //al listado de Categorias        
                                if($scope.venta.ventaid > 0)
                                {
                                   $scope.modalShown = false;
                                }
                        });  
    };
    
    
    $scope.addVenta = function(){
                
         $scope.nuevaVenta.$save({VentaId:"new"} , function() {
                //Si el valor de categoria agregado trajo algun valor de regreso entonces nos regresamos
                //al listado de Categorias
                if($scope.nuevaVenta.ventaid > 0)
                {
                  $scope.ventas.push($scope.nuevaVenta);
                  $scope.nuevaVenta = new Venta({VentaId:0});
                }
            });
            
    };
}


function MercanciasCtrl($scope,Articulo,Categoria,$location){

    $scope.errorMessage = "";
    $scope.articulos  = [];



    $scope.currentPage = 0;
    $scope.pageSize = 10;
    
    $scope.categorias = Categoria.query();
    $scope.nuevoArticulo = new Articulo({ArticuloId:0});
    
    $scope.pageCount = function() {
        return $scope.articulos.length / $scope.pageSize;
    };
    $scope.pageList = function() {
        var p = [];
        var i;
        for (i = 0; i < $scope.pageCount(); ++i) {
            p.push(i + 1);
        }
        return p;
    };

    Articulo.query(function(response){

        $scope.articulos = response;

    });


     $scope.addArticulo = function(){
                
        
         $scope.nuevoArticulo.$save({ArticuloId:"new"} , function() {
                //Si el valor de categoria agregado trajo algun valor de regreso entonces nos regresamos
                //al listado de Categoriaso
                if($scope.nuevoArticulo.ArticuloId > 0)
                {
                  $scope.articulos.push($scope.nuevoArticulo);
                  $scope.nuevoArticulo = new Articulo({ArticuloId:0});
                  
                }
            });
            
    };

     $scope.updateArticulo = function(articulo){
        
        articulo.$save({ArticuloId: articulo.ArticuloId } , 
        function(){
            
            //Si el valor del resource agregado trajo algun valor de regreso entonces nos regresamos, 
            //al listado de Categorias        
            if(articulo.ArticuloId > 0)
            {
              // $scope.modalShown = false;
            }
            
            
        });  
    };

    
    $scope.setPage = function(page) {
        $scope.currentPage = page - 1;
    };
    
    $scope.remove = function (articulo)
    {        
        Articulo.remove({ArticuloId : articulo.ArticuloId},
                            function(data, status, headers, config) { 
                                //Handle Success here
                                var indice = $scope.articulos.indexOf(articulo,0); 
            
                                if (indice != -1 )
                                {
                                    $scope.articulos.splice(indice,1);    
                                }
                                
                                $scope.errorMessage = "El articulo se elimino correctamente.";
            
                            },function(data, status, headers, config ){
                                
                                //Handle error here
                                $scope.errorMessage = "Ocurrio un error al eliminar";
                            }
                        );
        
    }  ;
    
    
}




function ProveedoresCtrl( $scope, Proveedor,$location){
    
    $scope.errorMessage = "";
    $scope.proveedores = Proveedor.query();
    
    $scope.currentPage = 0;
    $scope.pageSize = 10;
    $scope.pageCount = function() {
        return $scope.proveedores.length / $scope.pageSize;
    };
    
    $scope.pageList = function() {
        var p = [];
        var i;
        for (i = 0; i < $scope.pageCount(); ++i) {
            p.push(i + 1);
        }
        return p;
    };
    
    $scope.setPage = function(page) {
        $scope.currentPage = page - 1;
    };
  
    $scope.remove = function (proveedor)
    {        
        Proveedor.remove({ProveedorId : proveedor.ProveedorId},
                            function(data, status, headers, config) { 
                                //Handle Success here
                                var index = $scope.proveedores.indexOf(proveedor,0); 
            
                                if (index != -1 )
                                {
                                    $scope.proveedores.splice(index,1) ;   
                                }
                                
                                $scope.errorMessage = "El proveedor se elimino correctamente.";
            
                            },function(data, status, headers, config ){
                                
                                //Handle error here
                                $scope.errorMessage = "Ocurrio un error al eliminar";
                            }
                        );
        
    }   ;
}

function ProveedoresDetailCtrl($scope,$routeParams,$location, Proveedor)
{
   if($routeParams.ProveedorId>0){ 
    $scope.proveedor = Proveedor.get({ProveedorId:$routeParams.ProveedorId});
   
   }
   else
   {
       $scope.proveedor = new Proveedor({ProveedorId:0});
    }
   

    $scope.saveProveedor = function()
    {
        if($scope.myMarkers.length > 0)
        {
            var markers =[]; 
            
            angular.forEach($scope.myMarkers, function(value, key){                   
                   
                   var position = {
                        Lat : value.position.Xa,
                        Long: value.position.Ya
                    };

                   markers.push(position);                   
            });
            $scope.proveedor.ProveedorMapMarkers = angular.toJson(markers);
        }
        
        
        if ($scope.proveedor.ProveedorId > 0){
            $scope.proveedor.$save({ProveedorId: $scope.proveedor.ProveedorId},function(){
                $location.path('/proveedores');
            });
            
        }
        else
        {
            $scope.proveedor.$save({ProveedorId:"new"},function ()
                {
                    $scope.mensaje = "Guardado" ;
                    //Si el valor del proveedor agregado trajo algun valor de regreso entonces nos regresamos
                    //al listado de proveedores
                    if($scope.proveedor.ProveedorId > 0)
                    {                    
                        $location.path('/proveedores');
                    }
                    
                }
            );
            
            
        }
           
    };
    
    //AngularUI Maps
    $scope.myMarkers = [];
    
        
    $scope.mapOptions = {
        center: new google.maps.LatLng(27.922, -110.892),
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    $scope.addMarker = function($event) {
        $scope.myMarkers.push(new google.maps.Marker({
            map: $scope.myMap,
            position: $event.latLng
        }));
    };
    
     $scope.setZoomMessage = function(zoom) {
        //$scope.zoomMessage = 'You just zoomed to '+zoom+'!';
        //console.log(zoom,'zoomed')
    };
    
    $scope.openMarkerInfo = function(marker) {
        $scope.currentMarker = marker;
        $scope.currentMarkerLat = marker.getPosition().lat();
        $scope.currentMarkerLng = marker.getPosition().lng();
        $scope.myInfoWindow.open($scope.myMap, marker);
    };
 
    $scope.setMarkerPosition = function(marker, lat, lng) {
        marker.setPosition(new google.maps.LatLng(lat, lng));
    };
    
    //Este es un listener para ver cuando los marcadores del proveedor ya se 
    //cargaron desde la base de datos.
    $scope.$watch('proveedor.ProveedorMapMarkers',function(marcadores){
        var markers = angular.fromJson(marcadores);
        
        angular.forEach(markers, function(marker)
        {
            $scope.myMarkers.push(new google.maps.Marker({
                map: $scope.myMap,
                position: new google.maps.LatLng(marker.Lat, marker.Long)
            }));
        });
        
        
        
    });
    
    ///End AngularUI Maps
    
    
}




function CategoriasCtrl( $scope, Categoria){
    
    $scope.categorias = Categoria.query();
    $scope.currentCategoria = new Categoria({CategoriaId:0});
    
    $scope.update = function(categoria){ 
        $scope.categoria = categoria;
        $scope.modalShown = true;
    };
    
    
    //Call to API Functions
    $scope.updateCategoria = function(){
        
        $scope.categoria.$save({CategoriaId:$scope.categoria.CategoriaId } , 
        function(){
        
        //Si el valor de categoria agregado trajo algun valor de regreso entonces nos regresamos, 
        //al listado de Categorias        
        if($scope.categoria.CategoriaId > 0)
        {
           $scope.modalShown = false;
        }
        });  
    };
        
    
    $scope.addCategoria = function()
    {
        $scope.currentCategoria.$save({CategoriaId:"new"} , function() 
        {
            //Si el valor de categoria agregado trajo algun valor de regreso entonces nos regresamos, al listado de Categorias
        
            if($scope.currentCategoria.CategoriaId > 0)
            {
                $scope.categorias.push($scope.currentCategoria);
                $scope.currentCategoria = new Categoria({CategoriaId:0});
            }
        }
        );    
    };
    
    $scope.removeCategoria = function (categoria)
    {
        Categoria.remove({CategoriaId : categoria.CategoriaId},
        
        function(data, status, headers, config) 
        { 
            //Handle Success here
            var index = $scope.categorias.indexOf(categoria,0); 
            
            if (index != -1 )
            {
                $scope.categorias.splice(index,1) ;   
            }
                                
            $scope.errorMessage = "La categoria se elimino correctamente.";
        },
        function(data, status, headers, config )
        {
            //Handle error here
            $scope.errorMessage = "Ocurrio un error al eliminar a la categoria";
        }); 
    };
    

}



function ComprasCtrl(){
    
}

function ReportesCtrl(){
}

function ClientesCtrl()
{

     

}


function ClientesNewCtrl(){

      
}



function ClientesEditCtrl(){

      
}

function ClientesModalCtrl(){


}

function TiendaCtrl($scope){
    
     var master = {
    nombre: 'John Smith',
    telefono: '99999',
    email: '99999',
    direccion:{
      linea1: '123 Main St.',
      ciudad:'Anytown',
      estado:'AA',
      cp:'12345'
    },
    contacts:[
      {tipo:'phone', valor:'1(234) 555-1212'}
    ]
  };
    
    
    $scope.cp = /^\d\d\d\d\d$/;
    
    $scope.addContact = function() {
        
        $scope.tienda.contactos.push({tipo:'', valor:''});
    };
 
  $scope.removeContact = function(contacto) {
    var contacts = $scope.tienda.contactos;
    for (var i = 0, ii = contacts.length; i < ii; i++) {
      if (contacto === contacts[i]) {
        contacts.splice(i, 1);
      }
    }
  };
  
  
    $scope.cancel = function(){
        $scope.tienda = angular.copy(master);
    }
    
    $scope.save = function(){
         master = $scope.tienda;
        $scope.cancel();
    }
   
   $scope.isCancelDisabled = function() {
         return angular.equals(master, $scope.tienda);
    };
 
    $scope.isSaveDisabled = function() {
        return $scope.TiendaForm.$invalid || angular.equals(master, $scope.tienda);
     };
 
         $scope.cancel();
    
}
