function  POSCtrl($scope,Categoria,Articulo)
{
    $scope.categorias = []
    $scope.articulos  = [];


     Categoria.query(function(data){
        $scope.categorias = data;
     });

     Articulo.query(function(data){

        $scope.articulos = data;

    });



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




