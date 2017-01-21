module.exports = function (TorrentFactory, $timeout, $scope, SweetAlert) {
    let timer = null;
    this.time = 15;

    this.refreshTimer = () => {
        if (--this.time == 0) {
            $timeout.cancel(timer);
            timer = null;
            this.time = 15;

            this.load();
        } else {
            timer = $timeout(this.refreshTimer, 1000);
        }
    };

    this.load = () => {
        TorrentFactory.get((response) => {
            this.items = response;

            if (! timer) {
                this.refreshTimer();
            }
        });
    };

    this.load();

    $scope.$on('$destroy', function() {
        $timeout.cancel(timer);
    });

    this.toggle = (item) => {
        TorrentFactory.toggle(item.hash, (response) => {
            item.status.is_paused = response.status == 'stopped';
            item.status.is_downloading = response.status == 'started';
            item.status.is_finished = false;

            SweetAlert.swal({
                title: 'Good',
                text: response.status == 'stopped' ? 'Torrent was paused' : 'Torrent was started',
                type: 'success'
            });

        }, (response) => {
            if (response.error && response.error == 'already_finished') {
                item.status.is_paused = false;
                item.status.is_downloading = false;
                item.status.is_finished = true;

                SweetAlert.swal({
                    title: 'Oops',
                    text: 'Torrent is already finished.',
                    type: 'error'
                });
            } else {
                SweetAlert.swal({
                    title: 'Oops',
                    text: 'Torrent could be paused / started. Request failed.',
                    type: 'error'
                });
            }

        });
    };

    this.removeAll = () => {
        let hashes = this.items.map(i => i.hash);

        TorrentFactory.remove(hashes, (response) => {
            this.items = [];

            SweetAlert.swal({
                title: 'Good',
                text: 'Torrents were removed.',
                type: 'success'
            });
        }, () => {
            SweetAlert.swal({
                title: 'Oops',
                text: 'Torrents could not be removed. Request failed.',
                type: 'error'
            });
        });
    };

    this.remove = (item) => {
        TorrentFactory.remove(item.hash, (response) => {
            let index = this.items.indexOf(item);

            if (index != -1) {
                this.items.splice(index, 1);
            }

            SweetAlert.swal({
                title: 'Good',
                text: 'Torrent was removed.',
                type: 'success'
            });
        }, () => {
            SweetAlert.swal({
                title: 'Oops',
                text: 'Torrent could be removed. Request failed.',
                type: 'error'
            });
        });
    };
};