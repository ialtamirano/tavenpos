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
        currentArticulo : { ArticuloId : 1,ArticuloNombre: "Articulo Actual", ArticuloCantidad: 10 , ArticuloPrecio: 100.00 },
        articulos :[],
        iva : 0
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

    $scope.agregarDescuento = function(articulo)
    {
        articulo.ArticuloDescuento = 20;

    }

    $scope.quitarDescuento = function(articulo)
    {
        articulo.ArticuloDescuento = 0;

    }

    /*articulos :[{ "ArticuloId": "1","ArticuloNombre": "Articulo 1", "ArticuloCantidad" : 10, "ArticuloPrecio": "100.00"},{ "ArticuloId": "2","ArticuloNombre": "Articulo 2", "ArticuloCantidad" : 20, "ArticuloPrecio": "55.00"},                        { "ArticuloId": "3","ArticuloNombre": "Articulo 3", "ArticuloCantidad" : 5, "ArticuloPrecio": "145.00"},{ "ArticuloId": "4","ArticuloNombre": "Articulo 4", "ArticuloCantidad" : 20, "ArticuloPrecio": "199.00"},{ "ArticuloId": "5","ArticuloNombre": "Articulo 5", "ArticuloCantidad" : 30, "ArticuloPrecio": "25.00"}],*/
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
}




