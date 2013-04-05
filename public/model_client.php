<?php
class ClientModel implements \OAuth2\Storage\ClientInterface {

    private $db;

	public function __construct()
    {
        require_once 'db.php';
        $this->db = new DB();
    }


	public function getClient($clientId = null, $clientSecret = null, $redirectUri = null)
	{
		/*return array(
			'client_id' => '1234',
			'client secret' => '5678',
			'redirect_uri' => 'http://foo/redirect',
			'name' => 'Taven POS'
		);*/

		$result = $this->db->query('SELECT * FROM oauth_clients WHERE id = :id', array(':id' => $clientId));
		$row = $result->fetch();

		if ($row) {
			return array(
				'client_id'	=>	$row->id,
				'client_secret'	=>	$row->secret,
				'redirect_uri'	=>	$redirectUri,
				'name'	=>	$row->name,
				'auto_approve' => $row->auto_approve	
			);
		} else {
			return false;
		}
	}

}