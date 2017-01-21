module.exports = function ($uibModalInstance, $timeout, GenreFactory, TorrentFactory, CategoryFactory, AutocompleteService, SweetAlert) {

    this.torrent = {
        name: '',
        episode: '',
        cover: '',
        year: '',
        runtime: '',
        category: '',
        genres: [],
        rating: ''
    };

    this.subtitles = {
        lang: ''
    };

    this.showEpisodeName = false;

    GenreFactory.get((genres) => {
        this.genres = genres.map((genre) => {
            genre.active = false;

            return genre;
        });
    });

    CategoryFactory.get((categories) => {
        this.categories = categories.map((category) => {
            category.active = false;

            return category;
        });
    });

    this.showResults = false;

    this.close = () => {
        $uibModalInstance.dismiss('cancel');
    };

    this.validateForm = () => {
        let errors = [];

        let selectedGenres = this.genres.filter((genre) => genre.active);
        let selectedCategory = this.categories.filter((category) => category.active);

        if (selectedGenres.length == 0) {
            errors.push('You have to select at least one genre.');
        }

        if (selectedCategory.length == 0) {
            errors.push('You have to select category.');
        } else {

            if (selectedCategory[0].apiValue == 'series' && this.torrent.episode == '') {
                errors.push('You have to type episode.');
            }

        }

        if (this.torrent.name == '') {
            errors.push('You have to type name.');
        }

        if (this.torrent.year == '') {
            errors.push('You have to type release year.');
        }

        if (this.torrent.cover == '') {
            errors.push('You have to type URL to cover.');
        }

        if (this.torrent.rating == '') {
            errors.push('You have to type rating.');
        }

        if (this.torrent.runtime == '') {
            errors.push('You have to type runtime in minutes.');
        }

        if (this.torrentType == 'file') {
            if (!this.torrentFile) {
                errors.push('You have to select torrent file.');
            }
        } else {
            if (!this.magnet) {
                errors.push('You have to type magnet link.');
            }
        }

        if (errors.length > 0) {
            SweetAlert.swal({
                title: 'Oops',
                text: errors.join("\n"),
                type: 'error'
            });

            return false;
        }

        return true;
    };

    this.store = () => {

        for (let key in this.torrent) {
            this.torrent[key] = this.torrent[key].toString().fullTrim('-, –');
        }

        this.subtitles.lang = this.subtitles.lang.toString().fullTrim('-, –');

        if (this.torrent.episode == '') {
            delete this.torrent.episode;
        }

        if (!this.validateForm()) return;

        this.torrent.genres = this.genres.filter((genre) => genre.active).map((genre) => genre.id);
        this.torrent.category = this.categories.filter((category) => category.active)[0].id;

        let data = angular.copy(this.torrent);

        if (this.subtitles.lang && this.subtitles.subtitles) {
            this.subtitles.episode = this.torrent.episode;

            data.subtitles = this.subtitles;
        }

        data.type = this.torrentType;

        if (data.type == 'file') {
            data.torrent = this.torrentFile;
        } else {
            data.magnet = this.magnet;
        }

        SweetAlert.swal({
            title: 'Adding torrent',
            text: 'Please wait, in case of adding torrent via magnet link this may take sometime...',
            type: 'info',
            showConfirmButton: false
        });

        TorrentFactory.store(data, () => {
            this.close();

            SweetAlert.swal({
                title: 'Good',
                text: 'Torrent was added.',
                type: 'success'
            });
        }, () => {
            SweetAlert.swal({
                title: 'Oops',
                text: 'Torrent could not be added.',
                type: 'error'
            });
        });
    };

    this.triggerAutocomplete = () => {
        if (this.autocompleteTimer) {
            $timeout.cancel(this.autocompleteTimer);
        }

        this.autocompleteTimer = $timeout(this.runAutocomplete, 1000);
    };

    this.runAutocomplete = () => {
        AutocompleteService.search(this.torrent.name, (response) => {
            this.autocompleteItems = response.Search
        }, () => {
            SweetAlert.swal({
                title: 'Chyba',
                text: 'Autocomplete HTTP Request Failed.',
                type: 'error'
            });
        });
    };

    this.autocomplete = (item) => {
        this.torrent.name = item.Title;
        this.torrent.year = item.Year;
        this.torrent.cover = item.Poster;
        this.setTorrentCategory(item.Type);

        AutocompleteService.details(item.imdbID, (response) => {
            this.setTorrentGenre(response.Genre);
            this.torrent.rating = response.imdbRating;
            this.torrent.runtime = response.Runtime.replace('min', '').trim();
        }, () => {
            SweetAlert.swal({
                title: 'Chyba',
                text: 'Autocomplete HTTP Request Failed.',
                type: 'error'
            });
        });

        this.showResults = false;
    };

    this.setCategory = (category) => {
        for (let i = 0; i < this.categories.length; i++) {
            if (this.categories[i] == category) {
                this.categories[i].active = true;
            } else {
                this.categories[i].active = false;
            }
        }

        this.showEpisodeName = category.apiValue == 'series';
    };

    this.setTorrentCategory = (category) => {
        for (let i = 0; i < this.categories.length; i++) {
            if (this.categories[i].apiValue == category) {
                this.categories[i].active = true;
            } else {
                this.categories[i].active = false;
            }
        }

        this.showEpisodeName = category == 'series';
    };

    this.setTorrentGenre = (genres) => {
        genres = genres.split(',');

        for (let i = 0; i < this.genres.length; i++) {
            this.genres[i].active = false;

            for (let j = 0; j < genres.length; j++) {
                if (this.genres[i].apiValue == genres[j].trim()) {
                    this.genres[i].active = true;
                }
            }
        }
    };
};