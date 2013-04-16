<?php

//development 


require '../localvendor/OAuth2/Exception/OAuth2Exception.php';
require '../localvendor/OAuth2/Exception/ClientException.php';
require '../localvendor/OAuth2/Exception/InvalidAccessTokenException.php';

require '../localvendor/OAuth2/Storage/ScopeInterface.php';
require '../localvendor/OAuth2/Storage/SessionInterface.php';
require '../localvendor/OAuth2/Storage/ClientInterface.php';
require '../localvendor/OAuth2/Util/RequestInterface.php';

require '../localvendor/OAuth2/Grant/GrantTypeInterface.php';
require '../localvendor/OAuth2/Grant/AuthCode.php';
require '../localvendor/OAuth2/Util/Request.php';
require '../localvendor/OAuth2/Util/SecureKey.php';
require '../localvendor/OAuth2/Util/RedirectUri.php';
require '../localvendor/OAuth2/ResourceServer.php';
require '../localvendor/OAuth2/AuthServer.php';
require '../localvendor/OAuth2/Grant/Password.php';
require '../localvendor/OAuth2/Grant/RefreshToken.php';

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
	 		return 100; 

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

$resourceserver = new \OAuth2\ResourceServer( new SessionModel, new ScopeModel);



$checkToken = function () use ($resourceserver) {

	return function() use ($resourceserver)
	{
		// Test for token existance and validity
    	try {
    		$resourceserver->isValid();
    	}

    	// The access token is missing or invalid...
    	catch (\OAuth2\Exception\InvalidAccessTokenException $e)
    	{
    		$app = Slim::getInstance();
    		$res = $app->response();
			$res['Content-Type'] = 'application/json';
			$res->status(403);

			$res->body(json_encode(array(
				'error'	=>	$e->getMessage()
			)));
    	}
    };

};

?>