module.exports = function () {
    String.prototype.trimLeft = function (charlist) {
        if (charlist === undefined) charlist = "\s";

        return this.replace(new RegExp(`^[${charlist}]+`), '');
    };

    String.prototype.trimRight = function (charlist) {
        if (charlist === undefined) charlist = "\s";

        return this.replace(new RegExp(`[${charlist}]+$`), '');
    };

    String.prototype.fullTrim = function (charlist) {
        return this.trimLeft(charlist).trimRight(charlist);
    }
};