var app = angular.module('app', [
    'ui.router',
    'ui.bootstrap',
    'ngStorage',
    'ngFileUpload',
    'satellizer',
    'oitozero.ngSweetAlert',
]);

app.constant('API', '/api/');

app.config(require('./config/routes.js'));
app.config(require('./config/http.js'));

app.run(require('./global/restriction.js'));
app.run(require('./global/helpers.js'));

app.controller('SidebarController', require('./controllers/SidebarController'));
app.controller('FilesController', require('./controllers/FilesController'));
app.controller('FileModalController', require('./controllers/FileModalController'));
app.controller('TorrentsController', require('./controllers/TorrentsController'));
app.controller('AuthController', require('./controllers/AuthController'));
app.controller('DashboardController', require('./controllers/DashboardController'));
app.controller('StoreTorrentController', require('./controllers/StoreTorrentController'));

app.factory('CategoryFactory', require('./factories/CategoryFactory'));
app.factory('GenreFactory', require('./factories/GenreFactory'));
app.factory('TorrentFactory', require('./factories/TorrentFactory'));
app.factory('StatsFactory', require('./factories/StatsFactory'));
app.factory('FileFactory', require('./factories/FileFactory'));

app.service('AutocompleteService', require('./services/AutocompleteService'));
