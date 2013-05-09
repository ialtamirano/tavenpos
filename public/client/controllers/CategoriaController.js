function CategoriasCtrl( $scope, Categoria){
    
    $scope.categorias = Categoria.query();
    $scope.currentCategoria = new Categoria({CategoriaId:0});
    
    $scope.update = function(categoria){ 
        $scope.categoria = categoria;
        $scope.modalShown = true;
    };
    
    
    //Call to API Functions
    $scope.updateCategoria = function(categoria){
        
        categoria.$save({CategoriaId:categoria.CategoriaId } , 
        function(){
        
            //Si el valor de categoria agregado trajo algun valor de regreso entonces nos regresamos, 
            //al listado de Categorias        
            if(categoria.CategoriaId > 0)
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

