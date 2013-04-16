<?php
	class UserModel {
		private $db;

		public function __construct(){

			require_once 'db.php';
			$this->db = new DB();

		}

		
	}
?>