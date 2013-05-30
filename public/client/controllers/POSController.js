'use strict';

function  POSCtrl($scope,$location,Categoria,Articulo,POS)
{
    $scope.categorias = []
    $scope.articulos  = [];
   

     Categoria.query(function(data){
        $scope.categorias = data;
     });

     Articulo.query(function(data){

        $scope.articulos = data;

    });


    
    $scope.limpiarVenta = function(){
        $scope.venta = {
            currentArticulo : {  },
            articulos :[],
            iva : 0,
            efectivo :0 
        };
    };

    $scope.aumentarCantidad = function(articulo){
        articulo.ArticuloCantidad += 1;
    };

    $scope.disminuirCantidad = function(articulo){

         articulo.ArticuloCantidad -= 1;


        if (articulo.ArticuloCantidad <= 0){

            var indice = $scope.venta.articulos.indexOf(articulo,0); 

            if(indice >= 0){
                $scope.removerArticulo(indice);
            }
                
        }
        
    };

    $scope.mostrarPago = function(){

            $scope.showPaymentForm = true;
     
    };

    $scope.hacerPago = function(){

    };
    $scope.cancelaPago = function(){
        $scope.venta.efectivo = 0;
        $scope.showPaymentForm = false;
    };

    $scope.restante = function(){
        var restante = 0;
        restante = $scope.total() - $scope.venta.efectivo;

        if(restante < 0)
        {
            restante = 0;
        }
        return restante;
    };

    $scope.cambio = function(){

        var cambio = 0 ;

        if( $scope.total() < $scope.venta.efectivo ){
            cambio = $scope.venta.efectivo - $scope.total();
        }

        return cambio;

    };

    $scope.agregarDescuento = function(articulo)
    {
        articulo.ArticuloDescuento = 20;

    }

    $scope.quitarDescuento = function(articulo)
    {
        articulo.ArticuloDescuento = 0;

    }

   
    $scope.agregaArticulo = function(articulo){
    
        var indice = $scope.venta.articulos.indexOf(articulo,0); 
            
        if (indice != -1 )
        {
            var found = $scope.venta.articulos[indice];
            found.ArticuloCantidad += 1;
        }
        else
        {
            articulo.ArticuloCantidad = 1;
            $scope.venta.articulos.push(articulo);
        }
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

    $scope.guardarVenta=function(){
       if($scope.restante() = 0){
            $scope.limpiarVenta();
       }
        
    };
    
    $scope.limpiarVenta();

    $scope.$on('$locationChangeStart', function(event,next,current){
        if($scope.total() > 0 ){
           if(!confirm("Hay una venta en proceso, deseas cancelarla?")){
            event.preventDefault();
            } 
        }
            
    });
}




