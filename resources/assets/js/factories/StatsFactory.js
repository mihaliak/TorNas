module.exports = function ($http, API) {
    return {
        get(callback) {
            $http.get(`${API}stats`).success(callback);
        }
    };
};
