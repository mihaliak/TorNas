module.exports = function ($http, $httpParamSerializer) {
    return {
        search(name, success, error) {
            let queryParamaters = {
                s: name,
                r: 'json'
            };

            $http.get('http://www.omdbapi.com/?' + $httpParamSerializer(queryParamaters), {
                skipAuthorization: true
            }).success(success).error(error);
        },

        details(imdb, success, error) {
            let queryParamaters = {
                i: imdb,
                plot: 'full',
                r: 'json'
            };

            $http.get('http://www.omdbapi.com/?' + $httpParamSerializer(queryParamaters), {
                skipAuthorization: true
            }).success(success).error(error);
        },
    };
};
