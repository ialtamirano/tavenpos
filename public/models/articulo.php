
<?php
class Articulo extends Model {

      public static $_id_column = 'ArticuloId';
      public static $_table = 'articulos';

      public function categoria(){

      	return $this->belongs_to('Categoria','CategoriaId');

      }
}
?>
