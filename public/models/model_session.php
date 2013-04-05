<?php

class SessionModel implements \OAuth2\Storage\SessionInterface {

    private $db;

    public function __construct()
    {
        //require_once 'db.php';
        //$this->db = new DB();
    }

    public function createSession($clientId, $redirectUri, $type = 'user', $typeId = null, $authCode = null, $accessToken = null, $refreshToken = null, $accessTokenExpire = null, $stage = 'requested')
    {
        // Not needed for this demo
    }

    public function updateSession($sessionId, $authCode = null, $accessToken = null, $refreshToken = null, $accessTokenExpire = null, $stage = 'requested')
    {
        // Not needed for this demo
    }

    public function deleteSession($clientId, $type, $typeId)
    {
        // Not needed for this demo
    }

    public function validateAuthCode($clientId, $redirectUri, $authCode)
    {
        // Not needed for this demo
    }

    public function validateAccessToken($accessToken)
    {
        $this->db= getConnection();
        $query  ='SELECT id, owner_id, owner_type FROM oauth_sessions WHERE access_token = :accessToken';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam('accessToken',$accessToken);
        $stmt->execute();
        $row = $stmt->fetchObject();
        $this->db = null;
        if ($row) {
            return array(
                'id'    =>  $row->id,
                'owner_type' =>  $row->owner_type,
                'owner_id'  =>  $row->owner_id
            );
        } else {
            return false;
        }
    }

    public function getAccessToken($sessionId)
    {
        // Not needed for this demo
    }

    public function validateRefreshToken($refreshToken, $clientId)
    {
        // Not needed for this demo
    }

    public function updateRefreshToken($sessionId, $newAccessToken, $newRefreshToken, $accessTokenExpires)
    {
        // Not needed for this demo
    }

    public function associateScope($sessionId, $scopeId)
    {
        // Not needed for this demo
    }

    public function getScopes($sessionId)
    {
        $this->db= getConnection();
        $query  ='SELECT oauth_scopes.scope, oauth_scopes.name, oauth_scopes.description FROM oauth_session_scopes JOIN oauth_scopes ON oauth_session_scopes.scope_id = oauth_scopes.id WHERE session_id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam('id',$sessionId);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        $this->db = null;

        $scopes = array();

        //while ($row = $result->fetch()) {
        foreach ($results as $row){
            $scopes[] = $row->scope;
        }

        return $scopes;
    }
}