module.exports = function ($http, API) {
    return {
        get(callback) {
            $http.get(`${API}category`).success(callback);
        }
    };
};
