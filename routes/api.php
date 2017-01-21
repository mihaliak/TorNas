<?php

Route::post('login', 'Auth\AuthController@login')->name('auth.login');
Route::post('logout', 'Auth\AuthController@logout')->name('auth.logout');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('category', 'Category\CategoryController@index')->name('category.index');
    Route::get('user', 'Auth\AuthController@user')->name('auth.user');
    Route::get('stats', 'Stats\StatsController@index')->name('stats.index');
    Route::get('genre', 'Genre\GenreController@index')->name('genre.index');

    Route::get('torrent', 'Torrent\TorrentController@index')->name('torrent.index');
    Route::post('torrent', 'Torrent\TorrentController@store')->name('torrent.store');
    Route::patch('torrent/{hash}', 'Torrent\TorrentController@toggle')->name('torrent.toggle');
    Route::post('torrent/remove', 'Torrent\TorrentController@remove')->name('torrent.remove');

    Route::get('file', 'File\FileController@index')->name('file.index');
    Route::post('file/{id}', 'File\FileController@update')->name('file.update');
    Route::delete('file/{id}', 'File\FileController@remove')->name('file.remove');
});
