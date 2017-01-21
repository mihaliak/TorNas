module.exports = function ($http, API, Upload) {
    return {
        get(callback) {
            $http.get(`${API}file`).success(callback);
        },

        update(id, data, success, error) {
            Upload.upload({
                url: `${API}file/${id}`,
                data: data
            }).success(success).error(error);
        },

        remove(id, success, error) {
            $http.delete(`${API}file/${id}`).success(success).error(error);
        },

        storeSubtitles(id, data, success, error) {
            $http.post(`${API}file/${id}`, data).success(success).error(error);
        }
    };
};
