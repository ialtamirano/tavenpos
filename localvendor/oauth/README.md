# Introduction
This project installs all components for running an OAuth 2.0 service for
evaluating, testing and development.

It consist of an authorization server, a resource server and test clients. For
authentication the SimpleAuth library is used. This way, the full stack is 
controlled and there are no outside dependencies anymore which is perfect for 
development purposes and running it for instance in a virtual machine.

You can run this script on Linux and Mac OS X systems.

# Installation Directory
The directory for installation needs to exist before running the script. Here 
are some examples of what this path should be on various operating systems:

* Linux (Fedora/CentOS/RHEL): `/var/www/html/oauth`
* Linux (Debian/Ubuntu): `/var/www/oauth`
* Mac OS X: `/Library/WebServer/Documents/oauth`

Assuming your user account name is `fkooman` you can
run the following commands to create the directory and change the permissions:

On Fedora, CentOS and RHEL:

    $ su -c "mkdir /var/www/html/oauth"
    $ su -c "chown fkooman:fkooman /var/www/html/oauth"

On Ubuntu (Debian does not have `sudo`, use `su -c` like above with the Debian
path):

    $ sudo mkdir /var/www/oauth
    $ sudo chown fkooman:fkooman /var/www/oauth

On Mac OS X:

    $ sudo mkdir /Library/WebServer/Documents/oauth
    $ sudo chown fkooman:staff /Library/WebServer/Documents/oauth

# Web Location
You also need to know the web location at which the installation will be 
available. By default this will be `http://localhost/oauth`.

# Running
The script does not need root permissions, but the installation directory
needs to be available and writeable by the current user.

*NOTE*: the script will remove all files under the directory you install 
to!

So assuming you are in the directory where you downloaded this project to, you
can simply run it:

    $ bash ./install_all.sh /var/www/html/oauth http://localhost/oauth

The two parameters can be modified to reflect your installation directory
and URL. Both parameters are mandatory.

If there are any warnings or errors about missing software you can just install
them and run the script again.

On a minimal Debian (base) install you need to install the following software, 
e.g. using `apt-get`:

    $ su -c "apt-get install git unzip php5-cli php5 php5-sqlite curl php5-curl sqlite3"

On Fedora:

    $ su -c "yum install git patch php-pdo httpd php php-xml"

# Apache Configuration
You have to add the Apache configuration files to the correct location. 
They are generated in the installation directory under the `apache` directory.
They can be copied to `/etc/apache2/conf.d` on Debian and Ubuntu, to 
`/etc/apache2/other` on Mac OS X and `/etc/httpd/conf.d` on Fedora, CentOS and 
RHEL. For example:

    $ su -c "/var/www/html/oauth/apache/* /etc/httpd/conf.d/"

Don't forget to restart Apache after modifying these files. On Ubuntu/Debian
use `su -c "service apache2 restart"`, on Fedora, CentOS and RHEL use 
`su -c "service httpd restart"` and on Mac OS X use `apachectl restart`. 

That should be about it! After you are done installing, visit 
`http://localhost/oauth`, or the URL you specified to see what is available!
