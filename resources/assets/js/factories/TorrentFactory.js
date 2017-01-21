module.exports = function ($http, Upload, API) {
    return {
        get(callback) {
            $http.get(`${API}torrent`).success(callback);
        },

        store(data, success, error) {
            Upload.upload({
                url: `${API}torrent`,
                data: data
            }).success(success).error(error);
        },

        toggle(hash, success, error) {
            $http.patch(`${API}torrent/${hash}`).success(success).error(error);
        },

        remove(hash, success, error) {
            $http.post(`${API}torrent/remove`, { hash }).success(success).error(error);
        },
    };
};
