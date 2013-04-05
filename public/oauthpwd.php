<?php 
session_start();
ini_set('display_errors', true);
error_reporting(-1);


//development 
require '../localvendor/Slim/Slim.php';



require '../localvendor/paris/idiorm.php';
require '../localvendor/paris/paris.php';

require '../config/connection.php';

require '../localvendor/OAuth2/Exception/OAuth2Exception.php';
require '../localvendor/OAuth2/Exception/ClientException.php';
require '../localvendor/OAuth2/Exception/InvalidAccessTokenException.php';

require '../localvendor/OAuth2/Storage/ScopeInterface.php';
require '../localvendor/OAuth2/Storage/SessionInterface.php';
require '../localvendor/OAuth2/Storage/ClientInterface.php';
require '../localvendor/OAuth2/Util/RequestInterface.php';

require '../localvendor/OAuth2/Grant/GrantTypeInterface.php';
require '../localvendor/OAuth2/Grant/AuthCode.php';
require '../localvendor/OAuth2/Grant/Password.php';
require '../localvendor/OAuth2/Util/Request.php';
require '../localvendor/OAuth2/Util/SecureKey.php';
require '../localvendor/OAuth2/Util/RedirectUri.php';



require '../localvendor/OAuth2/ResourceServer.php';
require '../localvendor/OAuth2/AuthServer.php';



// Include the storage models
include 'model_client.php';
include 'model_scope.php';
include 'model_session.php';

// New Slim app
$app = new Slim();

// Initiate the Request handler
$request = new \OAuth2\Util\Request();

// Initiate the auth server with the models
$server = new \OAuth2\AuthServer(new ClientModel, new SessionModel, new ScopeModel);

// Enable support for the authorization Password grant
$server->addGrantType(new \OAuth2\Grant\Password());


// Process sign-in form submission
$app->post('/signin', function () use ($app) {

	// Check the auth params are in the session
	if ( ! isset($_SESSION['params']))
	{
		throw new Exception('Missing auth parameters');
	}

	$params = unserialize($_SESSION['params']);

	// Check the user's credentials
	if ($_POST['username'] === 'alex' && $_POST['password'] === 'password')
	{
		// Add the user ID to the auth params and forward the user to authorise the client
		$params['user_id'] = 1;
		$_SESSION['params'] = serialize($params);
		$app->redirect('/oauth.php/authorise');
	}

	// Wrong username/password
	else
	{
		$app->redirect('/oauth.php/signin');
	}

});


$app->post('/access_token', function() use ($server) {

	header('Content-type: application/javascript');

	try {

		// Issue an access token
		$p = $server->issueAccessToken();
		echo json_encode($p);

	}

	catch (Exception $e)
	{
		// Show an error message
		echo json_encode(array('error' => $e->getMessage(), 'error_code' => $e->getCode()));
	}

});

$app->run();



?>