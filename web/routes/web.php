<?php

use Illuminate\Support\Collection;

$router->get('/', function () {
    return redirect(url('/dashboard'));
});

$router->group(['middleware' => 'auth.pirrot'], function () use ($router) {

    $router->get('/dashboard', ['as'=> 'dashboard', 'uses' => 'DashboardController@showDashboardPage']);
    $router->get('/dashboard/stats', 'DashboardController@ajaxGetDashboardStats');

    $router->get('/audio-recordings', ['as'=> 'recordings', 'uses' => 'RecordingsController@showRecordingsPage']);
    $router->get('/audio-recordings/{filename}/download', ['as'=> 'download-recording', 'uses' => 'RecordingsController@downloadAudioFile']);
    $router->get('/audio-recordings/{filename}/delete', ['as'=> 'delete-recording', 'uses' => 'RecordingsController@deleteAudioFile']);

    $router->get('/weather-reports', ['as'=> 'weather-reports', 'uses' => 'WeatherController@showWeatherPage']);

    $router->get('/settings', ['as'=> 'settings', 'uses' => 'SettingsController@showSettingsPage']);
    $router->post('/settings', 'SettingsController@updateSettings');

    $router->get('/support', ['as'=> 'support', 'uses' => 'ContentController@showSupportPage']);

});


