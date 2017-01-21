module.exports = function ($scope, $uibModalInstance, $state, $uibModal, FileFactory, CategoryFactory, GenreFactory, SweetAlert) {
    let vm = $scope.$parent.vm;

    this.item = vm.current;
    this.mode = 'show';

    this.showEpisodes = false;

    this.showYoutube = () => {
        window.open(`https://www.youtube.com/results?search_query=${this.item.name} Trailer`);
    };

    this.changeMode = () => {
        this.mode = this.mode == 'show' ? 'edit' : 'show';

        this.checkForLoadedCategoriesAndGenres();
        this.reloadEditModel();
    };

    this.reloadEditModel = () => {
        if (this.mode == 'edit') {
            this.edit = angular.copy(this.item);
            this.showEpisodes = this.edit.category.value == 'series';

            delete this.edit.cover;

            if (this.categories) {
                this.categories = this.categories.map((category) => {
                    category.active = category.apiValue == this.edit.category.value;

                    return category;
                });
            }

            if (this.genres) {
                this.genres = this.genres.map((genre) => {
                    genre.active = this.edit.genres.indexOf(genre.name) != -1;

                    return genre;
                });
            }
        }
    };

    this.checkForLoadedCategoriesAndGenres = () => {
        if (this.mode == 'edit' && ! this.categories && ! this.genres) {
            this.loadCategoriesAndGenres();
        }
    };

    this.loadCategoriesAndGenres = () => {
        CategoryFactory.get((response) => {
            this.categories = response.map((category) => {
                category.active = category.apiValue == this.edit.category.value;

                return category;
            });
        });

        GenreFactory.get((response) => {
            this.genres = response.map((genre) => {
                genre.active = this.edit.genres.indexOf(genre.name) != -1;

                return genre;
            });
        });
    };

    this.update = () => {
        for (let key in this.edit) {
            if (this.edit[key]) {
                this.edit[key] = this.edit[key].toString().fullTrim('-, –');
            }
        }

        let data = {
            name: this.edit.name,
            rating: this.edit.rating,
            runtime: this.edit.runtime,
            year: this.edit.year,
            genres: this.genres.filter((genre) => genre.active).map((genre) => genre.id),
            category: this.categories.filter((category) => category.active)[0].id
        };

        if (this.edit.episode && this.edit.episode != '') {
            data.episode = this.edit.episode;
        }

        if (this.edit.cover && this.edit.cover != '') {
            data.cover = this.edit.cover;
        }

        if (this.subtitles && this.subtitles.lang && this.subtitles.subtitles) {
            data.subtitles = this.subtitles;
        }

        FileFactory.update(this.item.id, data, () => {
            $scope.$emit('loadFiles', {});

            this.close();

            SweetAlert.swal({
                title: 'Good',
                text: 'File was updated.',
                type: 'success'
            });
        }, () => {
            SweetAlert.swal({
                title: 'Oops',
                text: 'File could not be updated.',
                type: 'error'
            });
        });
    };

    this.remove = () => {
        let remove = () => {
            FileFactory.remove(this.item.id, () => {
                $scope.$parent.vm.items = $scope.$parent.vm.items.filter((item) => {
                    return item.id != this.item.id;
                });

                this.close();

                SweetAlert.swal({
                    title: 'Good',
                    text: 'File was removed.',
                    type: 'success'
                });
            }, () => {
                SweetAlert.swal({
                    title: 'Oops',
                    text: 'File could not be removed.',
                    type: 'error'
                });
            });
        };

        SweetAlert.swal({
            title: 'Are you sure?',
            text: 'With this action you will remove file, torrent and all data.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d8528c',
            cancelButtonText: 'No keep everything',
            confirmButtonText: 'Yes remove everything',
            closeOnConfirm: false
        }, (confirmed) => {
            if (confirmed) {
                SweetAlert.close();

                remove();
            }
        });
    };

    this.setCategory = (category) => {
        for (let i = 0; i < this.categories.length; i++) {
            if (this.categories[i] == category) {
                this.categories[i].active = true;

                this.showEpisodeName = this.categories[i].apiValue == 'series';
            } else {
                this.categories[i].active = false;
            }
        }
    };

    this.setStateParams = () => {
        $state.go('.', { id: null }, { notify: false });
    };

    this.close = () => {
        $uibModalInstance.dismiss('cancel');
    };

    $uibModalInstance.result.then(this.setStateParams, this.setStateParams);
};