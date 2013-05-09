function AppCtrl($scope, $location, $resource, $rootScope,i18nNotifications,localizedMessages)
{
    $scope.notifications = i18nNotifications;

  $scope.removeNotification = function (notification) {
    i18nNotifications.remove(notification);
  };

  $scope.$on('$routeChangeError', function(event, current, previous, rejection){
    i18nNotifications.pushForCurrentRoute('errors.route.changeError', 'error', {}, {rejection: rejection});
  });


    $scope.$location = $location;


    $scope.$watch('$location.path()', function(path) {
        return $scope.activeNavId = path || '/';
    });
    
    return $scope.getClass = function(id) {
      if($scope.activeNavId != undefined ){
          if ($scope.activeNavId.substring(0, id.length) === id) {
            return 'active';
          } 
          else {
            return '';
          }
      }
    };
}