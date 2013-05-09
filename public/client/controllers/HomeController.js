function HomeCtrl($scope, currentUser,AuthenticationService) {
    $scope.userInfo = currentUser.info;

}