<?php

class SessionModel implements \OAuth2\Storage\SessionInterface {

    private $db;

    public function __construct()
    {
        require_once 'db.php';
        $this->db = new DB();
    }

    public function createSession($clientId, $redirectUri, $type = 'user', $typeId = null, $authCode = null, $accessToken = null, $refreshToken = null, $accessTokenExpire = null, $stage = 'requested')
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
            VALUES (
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
            )', array(
            ':clientId' =>  $clientId,
            ':redirectUri'  =>  $redirectUri,
            ':type' =>  $type,
            ':typeId'   =>  $typeId,
            ':authCode' =>  $authCode,
            ':accessToken'  =>  $accessToken,
            ':refreshToken' =>  $refreshToken,
            ':accessTokenExpire'    =>  $accessTokenExpire,
            ':stage'    =>  $stage
        ));

        return $this->db->getInsertId();
    }

    public function updateSession($sessionId, $authCode = null, $accessToken = null, $refreshToken = null, $accessTokenExpire = null, $stage = 'requested')
    {
        $this->db->query('
            UPDATE oauth_sessions SET
                auth_code = :authCode,
                access_token = :accessToken,
                refresh_token = :refreshToken,
                access_token_expires = :accessTokenExpire,
                stage = :stage,
                last_updated = UNIX_TIMESTAMP(NOW())
            WHERE id = :sessionId',
        array(
            ':authCode' =>  $authCode,
            ':accessToken'  =>  $accessToken,
            ':refreshToken' =>  $refreshToken,
            ':accessTokenExpire'    =>  $accessTokenExpire,
            ':stage'    =>  $stage,
            ':sessionId'    =>  $sessionId
        ));
    }

    public function deleteSession($clientId, $type, $typeId)
    {
        $this->db->query('
                DELETE FROM oauth_sessions WHERE
                client_id = :clientId AND
                owner_type = :type AND
                owner_id = :typeId',
            array(
                ':clientId' =>  $clientId,
                ':type'  =>  $type,
                ':typeId' =>  $typeId
            ));
    }

    public function validateAuthCode($clientId, $redirectUri, $authCode)
    {
        $result = $this->db->query('
                SELECT * FROM oauth_sessions WHERE
                    client_id = :clientId AND
                    redirect_uri = :redirectUri AND
                    auth_code = :authCode',
            array(
                ':clientId' =>  $clientId,
                ':redirectUri'  =>  $redirectUri,
                ':authCode' =>  $authCode
            ));

        while ($row = $result->fetch())
        {
            return (array) $row;
        }

        return false;
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
        $this->db->query('INSERT INTO oauth_session_scopes (session_id, scope_id) VALUE (:sessionId, :scopeId)', array(
            ':sessionId'    =>  $sessionId,
            ':scopeId'  =>  $scopeId
        ));
    }

    public function getScopes($accessToken)
    {
        // Not needed for this demo
    }
}