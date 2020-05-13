<?php

$router->get('/', function () {
    return redirect(url('/dashboard'));
});

$router->group(['middleware' => 'auth.pirrot'], function () use ($router) {
    $router->get('/dashboard', 'DashboardController@showDashboardPage');
    $router->get('/dashboard/stats', 'DashboardController@ajaxGetDashboardStats');
    $router->get('/recordings', 'RecordingsController@showRecordingsPage');
});


