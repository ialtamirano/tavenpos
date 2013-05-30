'use strict';

/* Directives */


angular.module('venderapp.directives', []).
  directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
  }]);

  angular.module('venderapp.directives', []).
  directive('selectOnClick', function(){
  		return function (scope,element,attrs){
  			element.click(function(){
  				element.select();
  			});
  		};
  });