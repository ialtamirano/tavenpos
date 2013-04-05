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
require '../localvendor/OAuth2/Util/Request.php';
require '../localvendor/OAuth2/Util/SecureKey.php';
require '../localvendor/OAuth2/Util/RedirectUri.php';



require '../localvendor/OAuth2/ResourceServer.php';
require '../localvendor/OAuth2/AuthServer.php';
require '../localvendor/OAuth2/Grant/Password.php';



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

// Enable support for the authorization code grant
$server->addGrantType(new \OAuth2\Grant\AuthCode());
$server->addGrantType(new \OAuth2\Grant\Password());


// Clients will redirect to this address
$app->get('/', function () use ($server, $app) {

	// Tell the auth server to check the required parameters are in the query string
	$params = $server->checkAuthoriseParams();

	// Save the verified parameters to the user's session
	$_SESSION['params'] = serialize($params);

	// Redirect the user to sign-in
	$app->redirect('/oauth.php/signin');

});




// Sign-in
$app->get('/signin', function () {

	// Check the authorization params are set
	if ( ! isset($_SESSION['params']))
	{
		throw new Exception('Missing auth parameters');
	}

	// Get the params from the session
	$params = unserialize($_SESSION['params']);
	?>

	<form method="post">
		<h1>Sign in to <?php echo $params['client_details']['name']; ?></h1>

		<p>
			<label for="username">Username: </label>
			<input type="text" name="username" id="password" value="alex">
		</p>
		<p>
			<label for="password">Password: </label>
			<input type="password" name="password" id="password" value="password">
		</p>
		<p>
			<input type="submit" name="submit" id="submit" value="Sign in">
		</p>
	</form>

	<?php

});




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

// The user authorises the app
$app->get('/authorise', function () use ($app) {

	// Check the auth params are in the session
	if ( ! isset($_SESSION['params']))
	{
		throw new Exception('Missing auth parameters');
	}

	$params = unserialize($_SESSION['params']);

	// Check the user is signed in
	if ( ! isset($params['user_id']))
	{
		$app->redirect('/oauth.php/signin');
	}
	?>

	<h1>Authorise <?php echo $params['client_details']['name']; ?></h1>

	<p>
		The application <strong><?php echo $params['client_details']['name']; ?></strong> would like permission to access your:
	</p>

	<ul>
		<?php foreach ($params['scopes'] as $scope): ?>
			<li>
				<?php echo $scope['name']; ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<p>
		<form method="post" style="display:inline">
			<input type="submit" name="approve" id="approve" value="Approve">
		</form>

		<form method="post" style="display:inline">
			<input type="submit" name="deny" id="deny" value="Deny">
		</form>
	</p>

	<?php
});


// Process authorise form
$app->post('/authorise', function() use ($server, $app) {

	// Check the auth params are in the session
	if ( ! isset($_SESSION['params']))
	{
		throw new Exception('Missing auth parameters');
	}

	$params = unserialize($_SESSION['params']);

	// Check the user is signed in
	if ( ! isset($params['user_id']))
	{
		$app->redirect('/oauth.php/signin');
	}

	// If the user approves the client then generate an authoriztion code
	if (isset($_POST['approve']))
	{
		$authCode = $server->newAuthoriseRequest('user', $params['user_id'], $params);

		echo '<p>The user authorised a request and so would be redirected back to the client...</p>';

		// Generate the redirect URI
		echo OAuth2\Util\RedirectUri::make($params['redirect_uri'], array(
			'code' => $authCode,
			'state'	=> $params['state']
		));
	}

	// The user denied the request so send them back to the client with an error
	elseif (isset($_POST['deny']))
	{
		echo '<p>The user denied the request and so would be redirected back to the client...</p>';
		echo OAuth2\Util\RedirectUri::make($params['redirect_uri'], array(
			'error' => 'access_denied',
			'error_message' => $server::getExceptionMessage('access_denied'),
			'state'	=> $params['state']
		));
	}

});



// The client will exchange the authorization code for an access token
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
