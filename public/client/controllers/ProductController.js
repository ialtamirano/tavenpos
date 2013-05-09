function MercanciasCtrl($scope,Articulo,Categoria,$location,$http){

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

                if($scope.nuevoArticulo.ArticuloId > 0)
                {
                  $scope.articulos.push($scope.nuevoArticulo);
                  $scope.nuevoArticulo = new Articulo({ArticuloId:0});
                  
                }
        },function (data, status, headers, config){

           
        });
    
    };

     $scope.updateArticulo = function(articulo){
        
        articulo.$save({ArticuloId : articulo.ArticuloId},function (){

            
        });

        

        if(articulo.image){
            var formData = new FormData();
            formData.append('image', articulo.image, articulo.image.name);
            formData.append('imageOriginal', articulo.imageOriginal, articulo.image.name);

            $http.post('/api/articulos/' + articulo.ArticuloId+'/images', formData, { headers: { 'Content-Type': false }, transformRequest: angular.identity }
                ).success(
                    function(data,status,headers,config) {
                        articulo.ArticuloMiniatura = data.ArticuloMiniatura;                        
                    }
                );

        }

       
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