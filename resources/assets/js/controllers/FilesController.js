module.exports = function ($scope, $state, $stateParams, $uibModal, CategoryFactory, GenreFactory, FileFactory) {

    this.sort = {
        key: 'added.timestamp',
        desc: false
    };

    this.changeSort = (key) => {
        this.sort.key = key;
        this.sort.desc = ! this.sort.desc;
    }

    CategoryFactory.get((response) => {
        this.categories = response;
    });

    GenreFactory.get((response) => {
        this.genres = response;
    });

    this.load = (cb) => {
        FileFactory.get((response) => {
            this.items = response;

            this.items.map((item) => {
                if (item.series) {
                    let isFinished = item.status.is_finished;
                    let isPaused = item.status.is_paused;

                    for (let i = 0; i < item.series.length; i++) {
                        if (! item.series[i].status.is_finished) {
                            isFinished = false;
                        }

                        if (item.series[i].status.is_paused) {
                            isPaused = true;
                        }
                    }

                    item.status.is_finished = isFinished;
                    item.status.is_paused = isPaused;
                }

                return item;
            });

            if (cb) cb();
        });
    };

    this.load(() => {
        if ($stateParams.id) {
            this.show($stateParams.id);
        }
    });

    $scope.$on('loadFiles', (e, data) => {
        this.load();
    });

    this.toggleDropDown = (toggle) => {
        let state = this.dropDown[toggle];
        this.hideDropDowns();
        this.dropDown[toggle] = !state;
    };

    this.hideDropDowns = () => {
        this.dropDown = {
            category: false,
            genres: false,
            dates: false,
            type: false,
            sort: false
        };
    };

    this.hideDropDowns();

    this.show = (id) => {
        this.hideDropDowns();

        this.current = this.items.filter((item) => {
            return item.id == id;
        })[0];

        let modal = $uibModal.open({
            animation: true,
            templateUrl: '/views/files/show.html',
            controller: 'FileModalController',
            controllerAs: 'modal',
            scope: $scope,
            size: 'lg'
        });

        $state.go('.', { id: id }, { notify: false });
    };

    this.filterType = (item) => {
        if (! this.filter || ! this.filter.type) {
            return true;
        }

        switch (this.filter.type) {
            case 'active':
                return ! item.status.is_finished && ! item.status.is_paused;
                break;

            case 'done':
                return item.status.is_finished;

                break;

            case 'paused':
                return item.status.is_paused;
                break;
        }
    };

    this.getCheckedFiltersAsText = (items) => {
        return items.filter((item) => {
            return item.checked;
        }).map((item) => {
            return item.name;
        }).join(', ');
    };

    this.setActiveCategoryFilters = () => {
        this.activeCategoryFilters = this.getCheckedFiltersAsText(this.categories);
    };

    this.setActiveGenresFilters = () => {
        this.activeGenresFilters = this.getCheckedFiltersAsText(this.genres);
    };

    this.resetFilters = () => {
        this.filter.year = '';
        this.filter.name = '';
        this.filter.type = false;
        this.activeCategoryFilters = '';
        this.activeGenresFilters = '';

        for (let i = 0; i < this.categories.length; i++) {
            this.categories[i].checked = false;
        }

        for (let i = 0; i < this.genres.length; i++) {
            this.genres[i].checked = false;
        }
    };

    this.filterByGenre = (item) => {
        let selectedGenres = this.genres.filter((item) => {
            return item.checked;
        });

        if (selectedGenres.length) {
            let hasGenre = false;

            for (let i = 0; i < selectedGenres.length; i++) {
                if (item.genres.indexOf(selectedGenres[i].name) != -1) {
                    hasGenre = true;
                }
            }

            return hasGenre;
        }

        return true;
    };

    this.filterByCategory = (item) => {
        let selectedCategory = this.categories.filter((item) => {
            return item.checked;
        });

        if (selectedCategory.length) {
            let hasCategory = false;

            for (let i = 0; i < selectedCategory.length; i++) {
                if (item.category.value == selectedCategory[i].apiValue) {
                    hasCategory = true;
                }
            }

            return hasCategory;
        }

        return true;
    };
};