<?php
//ok aqui lo que vamos a hacer es mapear una clase de php con la base de datos
//y esto lo hacemos asi como esta el archivo tienda
class Categoria extends Model { 
    
     public static $_id_column = 'CategoriaId';
      public static $_table = 'categorias';
      
    public function articulos() {
        return $this->has_many('Articulo','CategoriaId'); // Note we use the model name literally - not a pluralised version
    }
}
//ok
?>