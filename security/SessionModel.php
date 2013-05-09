<?php
class SessionModel implements \OAuth2\Storage\SessionInterface {
	private $db;

	public function __construct(){

		//require_once 'db.php';

		$this->db = new DB();
	}

	public function createSession(
        $clientId,
        $redirectUri,
        $type = 'user',
        $typeId = null,
        $authCode = null,
        $accessToken = null,
        $refreshToken = null,
        $accessTokenExpire = null,
        $stage = 'requested'
    )
    {

        $this->db->query('
          	INSERT INTO oauth_sessions (
          		client_id, 
          		redirect_uri, 
          		owner_type,
          		owner_id, 
          		auth_code, 
          		access_token, 
          		refresh_token,
          		access_token_expires, 
          		stage, 
          		first_requested,
          		last_updated
          	) 
          	VALUES(
          		:clientId, 
          		:redirectUri, 
          		:type,
          		:typeId,
          		:authCode,
          		:accessToken,
          		:refreshToken,
          		:accessTokenExpire, 
          		:stage, 
          		UNIX_TIMESTAMP(NOW()), 
          		UNIX_TIMESTAMP(NOW())
          	)',
          	array (
          		':clientId' => $clientId, 
          		':redirectUri' => $redirectUri, 
          		':type'=>$type, 
          		':typeId'=>$typeId, 
          		':authCode' =>$authCode,
            	':accessToken' => $accessToken, 
            	':refreshToken'=> $refreshToken,
            	':stage' =>$stage, 
            	':accessTokenExpire' => $accessTokenExpire
          	)
	    );

		return $this->db->getInsertId();
    }

    public function updateSession(
        $sessionId,
        $authCode = null,
        $accessToken = null,
        $refreshToken = null,
        $accessTokenExpire = null,
        $stage = 'requested'
    ){

    	$this->db->query('
    	 	UPDATE oauth_sessions SET 
    	 		auth_code = :authCode, 
    	 		access_token = :accessToken, 
    	 		stage = :stage,
    	 		last_updated = UNIX_TIMESTAMP(NOW()) 
    	 	WHERE id = :sessionId',
       		array(
       			':authCode' => $authCode,
       			':accessToken' => $accessToken,
       			':stage' => $stage,
       			':sessionId' => $sessionId
       		)
       	);
    }

    public function deleteSession(
        $clientId,
        $type,
        $typeId
    )
    {
    	$this->db->query('
    		DELETE FROM oauth_sessions WHERE 
    			client_id = :clientId AND 
    			owner_type = :type AND 
    			owner_id = :typeId'
    			,
    		array(
    			':clientId' => $clientId,
    			':type' => $type,
    			':typeId' => $typeId
    		)
    	);
    }

    public function validateAuthCode(
        $clientId,
        $redirectUri,
        $authCode
    ){

    	$result=$this->db->query('
    		SELECT id FROM oauth_sessions WHERE 
    			client_id = :clientId AND
    			redirect_uri = :redirectUri AND
    			auth_code = :authCode'
    		,
    		array(
    			':clientId' => $clientId,
    			':redirectUri' => $redirectUri,
    			':authCode' => $redirectUri
    		)
    	);

        while ($row = $result->fetch()) {
            return (array) $row;
        }
    }
    /**
     * Validate an access token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT id, owner_id, owner_type FROM oauth_sessions WHERE access_token = $accessToken
     * </code>
     *
     * Response:
     *
     * <code>
     * Array
     * (
     *     [id] => (int) The session ID
     *     [owner_type] => (string) The owner type
     *     [owner_id] => (string) The owner ID
     * )
     * </code>
     *
     * @param  [type] $accessToken [description]
     * @return [type]              [description]
     */
    public function validateAccessToken($accessToken){

        $result = $this->db->query('
                SELECT 
                    id,
                    owner_id, 
                    owner_type 
                FROM oauth_sessions 
                WHERE access_token = :accessToken',
                array(
                    ':accessToken' => $accessToken
                )
        );

        $row=$result->fetch();

        if($row){
            return array(
                'id' => $row->id,
                'owner_type' => $row->owner_id,
                'owner_id' => $row->owner_id
            );
        }

    }

    /**
     * Return the access token for a given session
     *
     * Example SQL query:
     *
     * <code>
     * SELECT access_token FROM oauth_sessions WHERE id = $sessionId
     * </code>
     *
     * @param  int         $sessionId The OAuth session ID
     * @return string|null            Returns the access token as a string if
     *  found otherwise returns null
     */
    public function getAccessToken($sessionId){}

    /**
     * Validate a refresh token
     * @param  string $refreshToken The refresh token
     * @param  string $clientId     The client ID
     * @return int                  The session ID
     */
    public function validateRefreshToken($refreshToken, $clientId){}

    /**
     * Update the refresh token
     *
     * Example SQL query:
     *
     * <code>
     * UPDATE oauth_sessions SET access_token = $newAccessToken, refresh_token =
     *  $newRefreshToken, access_toke_expires = $accessTokenExpires, last_updated = UNIX_TIMESTAMP(NOW()) WHERE
     *  id = $sessionId
     * </code>
     *
     * @param  string $sessionId             The session ID
     * @param  string $newAccessToken        The new access token for this session
     * @param  string $newRefreshToken       The new refresh token for the session
     * @param  int    $accessTokenExpires    The UNIX timestamp of when the new token expires
     * @return void
     */
    public function updateRefreshToken(
        $sessionId,
        $newAccessToken,
        $newRefreshToken,
        $accessTokenExpires
    ){}

    /**
     * Associates a session with a scope
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_scopes (session_id, scope_id) VALUE ($sessionId,
     *  $scopeId)
     * </code>
     *
     * @param int    $sessionId The session ID
     * @param string $scopeId   The scope ID
     * @return void
     */
    public function associateScope($sessionId, $scopeId){

    	$this->db->query(' 
    		INSERT INTO oauth_session_scopes (
    			session_id, 
    			scope_id
    		) VALUE (
    			:sessionId,
      			:scopeId 
      		)'
    		,
    		array(
    			':sessionId' => $sessionId,
    			':scopeId' => $scopeId
    		)
    	);
    }

    /**
     * Return the scopes associated with an access token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT oauth_scopes.scope FROM oauth_session_scopes JOIN oauth_scopes ON
     *  oauth_session_scopes.scope_id = oauth_scopes.id WHERE
     *  session_id = $sessionId
     * </code>
     *
     * Response:
     *
     * <code>
     * Array
     * (
     *     [0] => (string) The scope
     *     [1] => (string) The scope
     *     [2] => (string) The scope
     *     ...
     *     ...
     * )
     * </code>
     *
     * @param  int   $sessionId The session ID
     * @return array
     */
    public function getScopes($sessionId){
        $result=$this->db->query('
            SELECT oauth_scopes.scope FROM oauth_session_scopes JOIN oauth_scopes ON
            oauth_session_scopes.scope_id = oauth_scopes.id WHERE
            session_id = :sessionId
        ',array(
            ':sessionId' => $sessionId
        ));

        $scopes = array();

        while ($row = $result->fetch()) {
            $scopes[] = $row->scope;
        }

        return $scopes;

    }


}
?>