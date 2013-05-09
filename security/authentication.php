<?php

//development 


require '../libraries/OAuth2/Exception/OAuth2Exception.php';
require '../libraries/OAuth2/Exception/ClientException.php';
require '../libraries/OAuth2/Exception/InvalidAccessTokenException.php';

require '../libraries/OAuth2/Storage/ScopeInterface.php';
require '../libraries/OAuth2/Storage/SessionInterface.php';
require '../libraries/OAuth2/Storage/ClientInterface.php';
require '../libraries/OAuth2/Util/RequestInterface.php';

require '../libraries/OAuth2/Grant/GrantTypeInterface.php';
require '../libraries/OAuth2/Grant/AuthCode.php';
require '../libraries/OAuth2/Util/Request.php';
require '../libraries/OAuth2/Util/SecureKey.php';
require '../libraries/OAuth2/Util/RedirectUri.php';
require '../libraries/OAuth2/ResourceServer.php';
require '../libraries/OAuth2/AuthServer.php';
require '../libraries/OAuth2/Grant/Password.php';
require '../libraries/OAuth2/Grant/RefreshToken.php';

// Include the storage models
include 'ClientModel.php';
include 'ScopeModel.php';
include 'SessionModel.php';

// New Slim app
//Implement Get Glient




//Implement validate refresh token
function PasswordGrantAuthentication($parameters){


	$server = new \OAuth2\AuthServer(new ClientModel, new SessionModel, new ScopeModel);
		
		


	 	$validateCredentials = function($u, $p) { 
	 		//throw exception if 
	 		//throw new Exception('Missing auth parameters'); 
	 		if($u=="ivan.altamirano@gmail.com" && $p == "12345")
	 		{ 
	 			return 3 ; 
	 		}
	 		
	 		return false ; 

	 	};


		$passwordGrant = new \OAuth2\Grant\Password($server);
		$passwordGrant->setVerifyCredentialsCallback($validateCredentials);

		$server->addGrantType($passwordGrant);
		$server->addGrantType(new \OAuth2\Grant\RefreshToken($server));

		$request = new OAuth2\Util\Request(array(), $parameters);
		$server->setRequest($request);


		$array = $server->issueAccessToken();

		return $array;

}

$resourceServer = new \OAuth2\ResourceServer( new SessionModel, new ScopeModel);



$checkToken = function () use ($resourceServer) {

	return function() use ($resourceServer)
	{
		// Test for token existance and validity
    	try {
    		$resourceServer->isValid();
    	}

    	// The access token is missing or invalid...
    	catch (\OAuth2\Exception\InvalidAccessTokenException $e)
    	{
    		$app = Slim::getInstance();
    		$res = $app->response();
			$res['Content-Type'] = 'application/json';
			$res->status(401);

			$res->body(json_encode(array(
				'error'	=>	$e->getMessage()
			)));
			$app->stop();
    	}
    };

};

 function getOwnerId()
 {
		try {
			$resourceServer = new \OAuth2\ResourceServer(new SessionModel, new ScopeModel);
	    	$resourceServer->isValid();
	    	return $resourceServer->getOwnerId();
	    }
    	// The access token is missing or invalid...
    	catch (\OAuth2\Exception\InvalidAccessTokenException $e)
    	{
    		$app = Slim::getInstance();
    		$res = $app->response();
			$res['Content-Type'] = 'application/json';
			$res->status(401);

			$res->body(json_encode(array(
				'error'	=>	$e->getMessage()
			)));
			$app->stop();
    	}
   
}
?>