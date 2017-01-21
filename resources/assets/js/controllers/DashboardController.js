module.exports = function (StatsFactory, $timeout, $scope) {
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
        StatsFactory.get((response) => {
            this.stats = response;

            if (! timer) {
                this.refreshTimer();
            }
        });
    };

    this.load();

    $scope.$on('$destroy', function() {
        $timeout.cancel(timer);
    });
};