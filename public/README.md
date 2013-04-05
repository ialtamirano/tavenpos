# OAuth 2.0 Example Auth Server

## Setup

1. Setup a MySQL database and edit db.php with the connection settings
2. Run the following commands on the database:
	1. `INSERT INTO oauth_clients (id, secret, name, auto_approve) VALUE ('I6Lh72kTItE6y29Ig607N74M7i21oyTo', 'dswREHV2YJjF7iL5Zr5ETEFBwGwDQYjQ', 'Hello World App', 0);`
	2. `INSERT INTO oauth_client_endpoints (client_id, redirect_uri) VALUE ('I6Lh72kTItE6y29Ig607N74M7i21oyTo', 'http://client.dev/signin/redirect');`
	3. `INSERT INTO oauth_scopes (scope, NAME, description) VALUES ('user', 'User details', 'Retrieves a user\'s details’);`
3. Install [Composer](http://getcomposer.org/) and run `composer install`

The source code is fully documented and should be a good starting base for your own OAuth auth server.