module.exports = function ($rootScope, $auth, $state) {
    $rootScope.$on('$stateChangeStart', function (e, stateItem) {
        if (stateItem.data && stateItem.data.public == true) return;

        if (!$auth.isAuthenticated()) {
            e.preventDefault();

            $state.go('login');
        }
    });
};