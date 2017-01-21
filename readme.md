# TorNas

TorNas is web application for storing informations about movies, tv shows and torrents from transmission server.

![Logo](https://raw.githubusercontent.com/mihaliak/TorNas/master/screenshots/files.png)

I created this application for personal need but then my friends asked me to share it so I made it open source.

This application is ready to use except some modules e.g. user management, episodes management. [see todo](https://github.com/mihaliak/TorNas#todo)

You can see screenshots of application in [screenshots folder](https://github.com/mihaliak/TorNas/tree/master/screenshots).

## Requirements
* ubuntu >= 16.04
* composer
* npm / yarn
* php >= 7.0
* mysql or any database supported by [laravel](https://laravel.com/docs/5.3/database#introduction)
* apache / nginx or any modern webserver
* python
* python-libtorrent (libtorrent-rasterbar version 0.16 or later) on ubuntu: `sudo apt-get install python-libtorrent -y`
* transmission server

## Installation
1. `git clone https://github.com/mihaliak/TorNas.git /var/www`
2. `composer install`
3. `yarn` or `npm install`
4. `gulp`
5. update `.env` file, set at least `APP_URL` `DB_*` `TRANSMISSION_*`
6. you may want to change admin account login/password you can do it in `database/seeds/CreateAdminAccount.php` or later in command line, see below
7. migrate and seed database, in directory /var/www run `php artisan migrate --seed`
8. You will have to update some files in `vendor` folder till my pull request will be accepted (not sure if it will be because project is inactive for long time) [see pull request here](https://github.com/kleiram/transmission-php/pull/67) and which file to edit [here](https://github.com/kleiram/transmission-php/pull/67/files#diff-866a7189c0f6cddb6312d0a3d4794e66) **If you wont edit this file you wont be able to remove all torrents at once, everything else will works**

## Users and passwords
Application supports multiple users and accounts but there is not any user management yet so you have to change default admin password, also you can create new users.

### Changing admin password
Replace YOUR_NEW_PASSWORD_HERE with your password.

1. In /var/www run `php artisan tinker` then type following commands:
2. `$admin = \TorNas\Modules\User\User::first();`
3. `$admin->password = bcrypt('YOUR_NEW_PASSWORD_HERE');`
4. `$admin->save();`

### Creating new user
Replace USER_NAME with user name and NEW_USER_PASSWORD with user password.

1. In /var/www run `php artisan tinker` then type following commands:
2. `\TorNas\Modules\User\User::create(['login' => 'USER_NAME', 'password' => bcrypt('NEW_USER_PASSWORD')]);`

### Removing user
Replace USER_NAME with user name.

1. In /var/www run `php artisan tinker` then type following commands:
2. `\TorNas\Modules\User\User::where('login', 'USER_NAME')->delete();`

## Todo
- [ ] User management
- [ ] Episode management
- [ ] Use medialibrary package to hold torrent files and not store them in File model
- [ ] Translations
- [ ] Refactoring
- [ ] Tests


## Contributing
Thank you for considering contributing to the TorNas! Just create pull request and I will check it out, if it will be reasonable pull request I will definitely accept it!

## License
All code is licensed under the [GPL version 3](http://www.gnu.org/licenses/gpl.html)