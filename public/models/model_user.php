<?php

class UserModel {

	private $db;

    public function __construct()
    {
        require_once 'db.php';
        $this->db = new DB();
    }

	public function getUser($id)
	{
		$result = $this->db->query('SELECT * FROM users WHERE id = :id', array(':id' => $id));
		$row = $result->fetch();

		if ($row) {
			return array(
				'id'	=>	$row->id,
				'firstname'	=>	$row->firstname,
				'lastname'	=>	$row->lastname,
				'email'	=>	$row->email,
				'phone'	=>	$row->phone
			);
		} else {
			return false;
		}

	}

	public function getUsers()
	{
		$result = $this->db->query('SELECT * FROM users');
		$users = array();

		while ($row = $result->fetch()) {
			$users[] = array(
				'id'	=>	$row->id,
				'firstname'	=>	$row->firstname,
				'lastname'	=>	$row->lastname,
				'email'	=>	$row->email,
				'phone'	=>	$row->phone
			);
		}

		return $users;
	}

}