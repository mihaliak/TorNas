module.exports = function (API, $httpProvider) {
    $httpProvider.interceptors.push(($q, $injector) => {
        return {
            responseError: (rejection) => {
                let $state = $injector.get('$state');

                if ([400, 401].includes(rejection.status) && $state.current.name != 'login') {
                    $injector.get('$auth').logout();
                    $state.go('login');
                }

                return $q.reject(rejection);
            }
        };
    });
};
