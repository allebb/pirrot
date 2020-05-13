<?php

use Illuminate\Support\Collection;

$router->get('/testing', function(){
    //$recordingsPath = app('path') . '/../public/recordings/';
    $recordingsPath = app('path') . '/../public/test/';

    $audioFiles = new Collection();
    $filesInDirectory = array_diff(scandir($recordingsPath), array('.', '..'));

    foreach ($filesInDirectory as $file) {
        $audioFiles->add(new \Illuminate\Support\Facades\File($file));
    }
});

$router->get('/', function () {
    return redirect(url('/dashboard'));
});

$router->group(['middleware' => 'auth.pirrot'], function () use ($router) {

    $router->get('/dashboard', 'DashboardController@showDashboardPage');
    $router->get('/dashboard/stats', 'DashboardController@ajaxGetDashboardStats');

    $router->get('/audio-recordings', 'RecordingsController@showRecordingsPage');
    $router->get('/audio-recordings/{filename}/download', ['name'=> 'download-recording', 'uses' => 'RecordingsController@downloadAudioFile']);
    $router->get('/audio-recordings/{filename}/delete', 'RecordingsController@deleteAudioFile');

});


