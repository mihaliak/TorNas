module.exports = function ($stateProvider, $urlRouterProvider, $authProvider, API) {

    $authProvider.loginUrl = API + 'login';

    $urlRouterProvider.otherwise('/');

    $stateProvider.state('login', {
        url: '/login',
        controller: 'AuthController as vm',
        templateUrl: '/views/login/login.html',
        data: {
            public: true
        }
    });

    $stateProvider.state('app', {
        abstract: true,
        templateUrl: '/views/layout/default.html',
    });

    $stateProvider.state('app.index', {
        url: '/',
        controller: 'DashboardController as vm',
        templateUrl: '/views/index/index.html',
    });

    $stateProvider.state('app.files', {
        url: '/files/:id',
        params: {
            id: null
        },
        controller: 'FilesController as vm',
        templateUrl: '/views/files/index.html',
    });

    $stateProvider.state('app.torrents', {
        url: '/torrents',
        controller: 'TorrentsController as vm',
        templateUrl: '/views/torrents/index.html',
    });
};