// Based loosely around work by Witold Szczerba - https://github.com/witoldsz/angular-http-auth
angular.module('services.authentication', ['services.authentication.current-user', 'services.authentication.interceptor', 'services.authentication.retry-queue','services.authentication.token-handler']);

// The AuthenticationService is the public API for this module.  Application developers should only need to use this service and not any of the others here.
angular.module('services.authentication').factory('AuthenticationService', ['$http', '$location', '$q', 'AuthenticationRetryQueue', 'currentUser','tokenHandler',function($http, $location, $q, queue, currentUser,tokenHandler) {

  // TODO: We need a way to refresh the page to clear any data that has been loaded when the user logs out
  //  a simple way would be to redirect to the root of the application but this feels a bit inflexible.
  function redirect(url) {
    url = url || '/';
    $location.path(url);
  }

  function updateCurrentUser(user) {
    currentUser.update(user);
    if ( !!user ) {
      queue.retry();
    }
  }

  function updateCurrentToken(token){
    tokenHandler.update(token);
   
  }


  var service = {
    isLoginRequired: function() {
      return queue.hasMore();
    },

    getLoginReason: function() {
      return queue.getReason();
    },

    showLogin: function() {
      // Push a no-op onto the queue to create a manual login
      queue.push({ retry: function() {}, cancel: function() {}, reason: 'user-request' });
    },

    login: function(email, password) {

      var request = $http.post('/api/login', {email: email, password: password},{
        headers : { 'Content-Type' : 'application/x-www-form-urlenconded'}
      });

      return request.then(function(response) {
        //set the authorization token

       
      
        updateCurrentToken(response.data.token);
        updateCurrentUser(response.data.user);
        return currentUser.isAuthenticated();
      });

    },

    cancelLogin: function(redirectTo) {
      queue.cancel();
      redirect(redirectTo);
    },

    logout: function(redirectTo) {
      $http.post('/api/logout').then(function() {
        tokenHandler.clear();
        currentUser.clear();
        redirect(redirectTo);
      });
    },

    // Ask the backend to see if a users is already authenticated - this may be from a previous session.
    // The app should probably do this at start up
    requestCurrentUser: function() {
      if ( currentUser.isAuthenticated() ) {
        return $q.when(currentUser);
      } else {
        return $http.get('/api/current-user').then(function(response) {
          updateCurrentUser(response.data.user);
          return currentUser;
        });
      }
    },

    requireAuthenticatedUser: function() {
      var promise = service.requestCurrentUser().then(function(currentUser) {
        if ( !currentUser.isAuthenticated() ) {
          return queue.pushPromiseFn(service.requireAuthenticatedUser, 'unauthenticated-client');
        }
      });
      return promise;
    },

    requireAdminUser: function() {
      var promise = service.requestCurrentUser().then(function(currentUser) {
        if ( !currentUser.isAdmin() ) {
          return queue.pushPromiseFn(service.requireAdminUser, 'unauthorized-client');
        }
      });
      return promise;
    }
  };

  // Get the current user when the service is instantiated
  service.requestCurrentUser();

  return service;
}]);

