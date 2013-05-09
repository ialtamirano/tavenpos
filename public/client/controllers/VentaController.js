function VentasCtrl($scope,$filter,Venta,i18nNotifications)
{
    $scope.dateOptions = {
        changeYear:true,
        changeMonth:true,
        dateformat: "mm/dd/yyyy"
    }
    $scope.ventas = [];
    $scope.filteredVentas = [];
    $scope.currentMonth=new Date().getMonth();
    $scope.year = new Date().getFullYear();
    
    $scope.months = [{Month:0,Name:'Enero'},
                     {Month:1,Name:'Febrero'},
                     {Month:2,Name:'Marzo'},
                     {Month:3,Name:'Abril'},
                     {Month:4,Name:'Mayo'},
                     {Month:5,Name:'Junio'},
                     {Month:6,Name:'Julio'},
                     {Month:7,Name:'Agosto'},
                     {Month:8,Name:'Septiembre'},
                     {Month:9,Name:'Octubre'},
                     {Month:10,Name:'Noviembre'},
                     {Month:11,Name:'Diciembre'}];

    $scope.search = {
         VentaFecha :''
    };

    


    Venta.query(function(response){
      
        $scope.ventas = response;

    });
    


    $scope.selectedDate=function(){

        //var strMonth= $scope.currentMonth + 1;
        var strMonth= parseInt($scope.currentMonth) + 1; //WA because ui-selec2 missfunction

        if(parseInt($scope.currentMonth)<9){
            strMonth='0' + (parseInt($scope.currentMonth) +1);
        }

        $scope.search.VentaFecha = $scope.year + '-' + strMonth ;
    }

    $scope.selectedDate();
    

    
    $scope.nuevaVenta = new Venta({ VentaId:0,VentaFecha:new Date()});
    
    

    
    $scope.totalize = function() {
        var t = 0;
        if($scope.filteredVentas.length > 0){

            angular.forEach($scope.filteredVentas, function(venta1) {
                t += parseFloat(venta1.VentaTotal);
            });
        
        }
        
        return t;
    };
    

    $scope.removeVenta = function (venta)
    {
        Venta.remove({VentaId : venta.VentaId},
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
    

    
    $scope.updateVenta = function(venta){
        
        venta.$save({VentaId:venta.VentaId } ,function(){
                                //Si el valor del resource agregado trajo algun valor de regreso entonces nos regresamos, 
                                //al listado de Categorias        
                                if(venta.VentaId > 0)
                                {
                                   
                                }
                        });  
    };
    
    
    $scope.addVenta = function(){
                
         $scope.nuevaVenta.$save({VentaId:"new"} , function() {
                //Si el valor de categoria agregado trajo algun valor de regreso entonces nos regresamos
                //al listado de Categorias
                if($scope.nuevaVenta.VentaId > 0)
                {
                  i18nNotifications.pushForCurrentRoute('crud.venta.save.success', 'success', {date : $scope.nuevaVenta.VentaFecha  });
                  $scope.ventas.push($scope.nuevaVenta);
                  $scope.nuevaVenta = new Venta({VentaId:0,VentaFecha:new Date()});
 
                }
            });
            
    };
}