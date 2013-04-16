<?php
class ClientModel implements \OAuth2\Storage\ClientInterface {

   	public function __construct(){
   		require_once 'db.php';
		$this->db = new DB();
   	}
   	
   	/**
	 * Validate a client
	 *
	 * Example SQL query:
	 *
	 * <code>
	 * # Client ID + redirect URI
	 * SELECT oauth_clients.id FROM oauth_clients LEFT JOIN client_endpoints ON client_endpoints.client_id
	 *  = oauth_clients.id WHERE oauth_clients.id = $clientId AND client_endpoints.redirect_uri = $redirectUri
	 *
	 * # Client ID + client secret
	 * SELECT oauth_clients.id FROM oauth_clients  WHERE oauth_clients.id = $clientId AND
	 *  oauth_clients.secret = $clientSecret
	 *
	 * # Client ID + client secret + redirect URI
	 * SELECT oauth_clients.id FROM oauth_clients LEFT JOIN client_endpoints ON client_endpoints.client_id
	 *  = oauth_clients.id WHERE oauth_clients.id = $clientId AND oauth_clients.secret = $clientSecret
	 *  AND client_endpoints.redirect_uri = $redirectUri
	 * </code>
	 *
	 * Response:
	 *
	 * <code>
	 * Array
	 * (
	 *     [client_id] => (string) The client ID
	 *     [client secret] => (string) The client secret
	 *     [redirect_uri] => (string) The redirect URI used in this request
	 *     [name] => (string) The name of the client
	 * )
	 * </code>
	 *
	 * @param  string     $clientId     The client's ID
	 * @param  string     $clientSecret The client's secret (default = "null")
	 * @param  string     $redirectUri  The client's redirect URI (default = "null")
	 * @return bool|array               Returns false if the validation fails, array on success
	 */
	public function getClient($clientId = null, $clientSecret = null, $redirectUri = null){

		//Implementar los querys dependiendo los parametros que vengan llenos


       /*'SELECT oauth_clients.id FROM oauth_clients LEFT JOIN client_endpoints ON client_endpoints.client_id
	   = oauth_clients.id WHERE oauth_clients.id = $clientId AND client_endpoints.redirect_uri = $redirectUri'*/
		$result = $this->db->query('
			SELECT 
				oauth_clients.id,
				oauth_clients.name,
				oauth_clients.secret
				

			FROM oauth_clients  WHERE 
				oauth_clients.id = :clientId AND
	 			oauth_clients.secret = :clientSecret
			'
			,
			array
			(
				':clientId' => $clientId,
				':clientSecret' => $clientSecret
			)
		);

		$row = $result->fetch();

		if($row){
			return array(
				'client_id' => $row->id,
				'client secret' => $row->secret,
				'redirect_uri' => $redirectUri,
				'name' => $row->name
				);
		}else{
			return false;
		}
	}
}
?>