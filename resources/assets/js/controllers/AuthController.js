module.exports = function ($auth, $state, SweetAlert) {
    this.authenticate = () => {
        let credentials = {
            login: this.login,
            password: this.password
        }

        $auth.login(credentials).then(() => {
            $state.go('app.index', {});
        }, () => {
            SweetAlert.swal({
                title: 'Oops',
                text: 'Wrong login or password.',
                type: 'error'
            });
        });
    };

    this.logout = () => {
        $auth.logout();

        $state.go('login');
    };
};