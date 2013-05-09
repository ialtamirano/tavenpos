function LoadingCtrl($scope,httpRequestTracker)
{
    $scope.hasPendingRequests = function () {
        return httpRequestTracker.hasPendingRequests();
    };
}