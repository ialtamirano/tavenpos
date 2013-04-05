#!/bin/bash

if [ -z "$1" ]
then

cat << EOF
    Please specify the location to install to. 

    Examples:
    
    Fedora, CentOS, RHEL: /var/www/html/oauth
    Debian, Ubuntu: /var/www/oauth
    Mac OS X: /Library/WebServer/Documents/oauth

    **********************************************************************
    * WARNING: ALL FILES IN THE INSTALLATION DIRECTORY WILL BE ERASED!!! *
    **********************************************************************

EOF
exit 1
else
    INSTALL_DIR=$1
fi

if [ -z "$2" ]
then
cat << EOF
    Please also specify the URL at which this installation will be available.

    Examples:

    http://localhost/oauth
    https://www.example.edu/oauth
    https://my.server.example.org

EOF
exit 1
else
    BASE_URL=$2
    BASE_PATH=`echo ${BASE_URL} | sed "s|[^/]*\/\/[^/]*||g"`
    DOMAIN_NAME=`echo ${BASE_URL} | sed "s|[^/]*\/\/||g" | sed "s|:.*||g" | sed "s|\/.*||g"`
    DATE_TIME=`date`
fi

cat << EOF
###############################################################################
# This script installs the following components to have a fully functional    #
# OAuth installation with all the components to quickly evaluate the software #
#                                                                             #
# The following components will be installed:                                 #
#                                                                             #
# * php-rest-service                                                          #
# * php-lib-remote-rs                                                         #
# * php-simple-auth
# * php-oauth                                                                 #
# * html-manage-applications                                                  #
# * html-manage-authorization                                                 #
# * html-view-grades                                                          #
# * php-oauth-grades-rs                                                       #
# * php-oauth-client                                                          #
# * OAuth Demo App                                                            #
# * php-remoteStorage                                                         #
# * html-music-player                                                         #
###############################################################################
EOF

if [ ! -d "${INSTALL_DIR}" ]
then
    echo "install dir ${INSTALL_DIR} does not exist (yet) make sure you created it and have write permission to it!";
    exit 1
fi

LAUNCH_DIR=`pwd`

# remove the existing installation
echo "ARE YOU SURE YOU WANT TO ERASE ALL FILES FROM: '${INSTALL_DIR}/'?"
select yn in "Yes" "No"; do
    case $yn in
        Yes ) break;;
        No ) exit;;
    esac
done

rm -rf ${INSTALL_DIR}/*

mkdir -p ${INSTALL_DIR}/apache

# the index page
cat ${LAUNCH_DIR}/res/index.html \
    | sed "s|{BASE_URL}|${BASE_URL}|g" \
    | sed "s|{DATE_TIME}|${DATE_TIME}|g" > ${INSTALL_DIR}/index.html

cat << EOF
#####################################
# php-rest-service (SHARED LIBRARY) #
#####################################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-rest-service.git
)
cat << EOF
######################################
# php-lib-remote-rs (SHARED LIBRARY) #
######################################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-lib-remote-rs.git
)

cat << EOF
#############################
# html-webapp-deps (SHARED) #
#############################
EOF
(
cd ${INSTALL_DIR}
mkdir -p html-webapp-deps/js
mkdir -p html-webapp-deps/bootstrap

# jQuery
curl -o html-webapp-deps/js/jquery.js http://code.jquery.com/jquery.min.js

# JSrender (JavaScript Template Rendering for jQuery)
curl -o html-webapp-deps/js/jsrender.js https://raw.github.com/BorisMoore/jsrender/master/jsrender.js

# JSO (JavaScript OAuth 2 client)
curl -o html-webapp-deps/js/jso.js https://raw.github.com/andreassolberg/jso/master/jso.js

# Bootstrap
curl -o html-webapp-deps/bootstrap.zip http://twitter.github.com/bootstrap/assets/bootstrap.zip
(cd html-webapp-deps/ && unzip bootstrap.zip && rm bootstrap.zip)
)

cat << EOF
###################
# php-simple-auth #
###################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-simple-auth.git
cd php-simple-auth
cp config/users.json.example config/users.json
ln -s ../../html-webapp-deps www/ext

# Apache config
cat docs/apache.conf \
    | sed "s|/APPNAME|${BASE_PATH}/php-simple-auth|g" \
    | sed "s|/PATH/TO/APP|${INSTALL_DIR}/php-simple-auth|g" > ${INSTALL_DIR}/apache/oauth_php-simple-auth.conf
)

cat << EOF
#############
# php-oauth #
#############
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-oauth.git
cd php-oauth

mkdir extlib
ln -s ../../php-rest-service extlib/

sh docs/configure.sh
php docs/initOAuthDatabase.php

# config
cat config/oauth.ini.defaults \
    | sed "s|authenticationMechanism = \"DummyResourceOwner\"|;authenticationMechanism = \"DummyResourceOwner\"|g" \
    | sed "s|;authenticationMechanism = \"SimpleAuthResourceOwner\"|authenticationMechanism = \"SimpleAuthResourceOwner\"|g" \
    | sed "s|allowResourceOwnerScopeFiltering = FALSE|allowResourceOwnerScopeFiltering = TRUE|g" \
    | sed "s|accessTokenExpiry = 3600|accessTokenExpiry = 28800|g" \
    | sed "s|/PATH/TO/APP|${INSTALL_DIR}/php-oauth|g" \
    | sed "s|enableApi = FALSE|enableApi = TRUE|g" \
    | sed "s|/var/www/html/php-simple-auth|${INSTALL_DIR}/php-simple-auth|g" > config/oauth.ini

# copy the attributes file
cp config/simpleAuthAttributes.json.example config/simpleAuthAttributes.json

# Apache config
cat docs/apache.conf \
    | sed "s|/APPNAME|${BASE_PATH}/php-oauth|g" \
    | sed "s|/PATH/TO/APP|${INSTALL_DIR}/php-oauth|g" > ${INSTALL_DIR}/apache/oauth_php-oauth.conf

# Register Clients
cat ${LAUNCH_DIR}/config/client_registrations.json \
    | sed "s|{BASE_URL}|${BASE_URL}|g" > docs/myregistration.json
php docs/registerClients.php docs/myregistration.json
)

cat << EOF
############################
# html-manage-applications #
############################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/html-manage-applications.git
cd html-manage-applications
ln -s ../html-webapp-deps ext

# configure
cat config/config.js.default \
    | sed "s|http://localhost|${BASE_URL}|g" > config/config.js
)

cat << EOF
##############################
# html-manage-authorizations #
##############################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/html-manage-authorizations.git
cd html-manage-authorizations
ln -s ../html-webapp-deps ext

# configure
cat config/config.js.default \
    | sed "s|http://localhost|${BASE_URL}|g" > config/config.js
)

cat << EOF
####################
# html-view-grades #
####################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/html-view-grades.git
cd html-view-grades
ln -s ../html-webapp-deps ext

# configure
cat config/config.js.default \
    | sed "s|http://localhost|${BASE_URL}|g" > config/config.js
)

cat << EOF
####################
# php-oauth-client #
####################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-oauth-client.git
cd php-oauth-client

mkdir extlib
ln -s ../../php-rest-service extlib/

sh docs/configure.sh
php docs/initDatabase.php

# Register Applications
cat ${LAUNCH_DIR}/config/application_registrations.json \
    | sed "s|{BASE_URL}|${BASE_URL}|g" > docs/myregistration.json

php docs/registerApplications.php docs/myregistration.json

cat docs/apache.conf \
    | sed "s|/APPNAME|${BASE_PATH}/php-oauth-client|g" \
    | sed "s|/PATH/TO/APP|${INSTALL_DIR}/php-oauth-client|g" > ${INSTALL_DIR}/apache/oauth_php-oauth-client.conf
)

cat << EOF
#######################
# php-oauth-grades-rs #
#######################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-oauth-grades-rs.git
cd php-oauth-grades-rs

mkdir extlib
ln -s ../../php-rest-service extlib/
ln -s ../../php-lib-remote-rs extlib/

sh docs/configure.sh

cat config/rs.ini \
    | sed "s|http://localhost/php-oauth/tokeninfo.php|${BASE_URL}/php-oauth/tokeninfo.php|g" > config/tmp_rs.ini
mv config/tmp_rs.ini config/rs.ini

# Apache config
cat docs/apache.conf \
    | sed "s|/APPNAME|${BASE_PATH}/php-oauth-grades-rs|g" \
    | sed "s|/PATH/TO/APP|${INSTALL_DIR}/php-oauth-grades-rs|g" > ${INSTALL_DIR}/apache/oauth_php-oauth-grades-rs.conf
)

cat << EOF
##################
# OAuth Demo App #
##################
EOF
(
mkdir -p ${INSTALL_DIR}/demo-oauth-app
cd ${INSTALL_DIR}/demo-oauth-app
cat ${LAUNCH_DIR}/res/oauth.php \
    | sed "s|{INSTALL_DIR}|${INSTALL_DIR}|g" \
    | sed "s|{BASE_URL}|${BASE_URL}|g" > ${INSTALL_DIR}/demo-oauth-app/index.php
)

cat << EOF
#####################
# php-remoteStorage #
#####################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/php-remoteStorage.git
cd php-remoteStorage
git checkout devel      # for now use devel branch

mkdir extlib
ln -s ../../php-rest-service extlib/
ln -s ../../php-lib-remote-rs extlib/

sh docs/configure.sh

cat config/remoteStorage.ini \
    | sed "s|http://localhost/php-oauth/tokeninfo.php|${BASE_URL}/php-oauth/tokeninfo.php|g" > config/tmp_remoteStorage.ini
mv config/tmp_remoteStorage.ini config/remoteStorage.ini

cat docs/apache.conf \
    | sed "s|/APPNAME|${BASE_PATH}/php-remoteStorage|g" \
    | sed "s|/PATH/TO/APP|${INSTALL_DIR}/php-remoteStorage|g" > ${INSTALL_DIR}/apache/oauth_php-remoteStorage.conf
)

cat << EOF
#####################
# html-music-player #
#####################
EOF
(
cd ${INSTALL_DIR}
git clone https://github.com/fkooman/html-music-player.git
cd html-music-player
ln -s ../html-webapp-deps ext

# configure
cat config/config.js.default \
    | sed "s|http://localhost|${BASE_URL}|g" > config/config.js
)

# Done
echo "**********************************************************************"
echo "* INSTALLATION DONE                                                  *"
echo "**********************************************************************"
echo
echo Please visit ${BASE_URL}.
echo
