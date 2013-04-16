angular.module('services.authentication.token-handler', ['ngCookies']);
// The current token.  You can watch this for changes due to logging in and out
angular.module('services.authentication.token-handler').factory('tokenHandler',function($cookies,$cookieStore) {

  var tokenInfo = null;

  var currentToken = {
    update: function(info) { 
    	$cookieStore.put('info',info);
    	tokenInfo = info; 
    },
    clear: function() { 
    	tokenInfo = null; 
    	$cookieStore.remove('info');
    },
    info: function() { 
    	tokenInfo = $cookieStore.get('info');
    	return tokenInfo; 
    }
   
  };

  return currentToken;
});

angular.module('services.authentication.token-handler').factory('myhttpinterceptor',function($q,$cookies,$cookieStore,tokenHandler){
        return {
            request : function(config){
      
                tokenInfo = tokenHandler.info();
                if(tokenInfo){
             	   config.headers['Authorization']=  'Bearer ' + tokenInfo.access_token;
            	}
                return config || $q.when(config);
            }
        }

 });



