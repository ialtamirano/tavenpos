<?php

class DB {

	private $conn;
	private $statement;

	function __construct()
	{

		//$this->conn = new PDO('mysql:host=127.0.0.1;dbname=tavenposdb', 'root', '');
		$this->conn = getConnection();
	}

	function query($sql = '', $params = array())
	{
		$statement = $this->conn->prepare($sql);
		$statement->setFetchMode(PDO::FETCH_OBJ);
		$statement->execute($params);
		return $statement;
	}

	public function getInsertId()
	{
		return (int) $this->conn->lastInsertId();
	}

}