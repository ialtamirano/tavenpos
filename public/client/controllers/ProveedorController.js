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

