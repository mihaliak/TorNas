module.exports = function ($uibModal) {

    this.showModal = () => {
        $uibModal.open({
            animation: true,
            templateUrl: '/views/torrents/modal.html',
            controller: 'StoreTorrentController',
            controllerAs: 'modal',
            size: 'md',
        });
    };

    this.showNavigation = false;

    this.toggleNavigation = () => {
        this.showNavigation = ! this.showNavigation;
    };
};