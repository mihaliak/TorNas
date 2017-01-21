module.exports = function ($http, API) {
    return {
        get(callback) {
            $http.get(`${API}genre`).success(callback);
        }
    };
};
