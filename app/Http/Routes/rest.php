<?php

/**
 * @var $app MyPlugin\Framework\Foundation\Application
 */
/*
 * data Route
// */
// $app->group(function ($app) {
//     $app->get('data', 'ChartController@index');
//     $app->post('data', 'ChartController@store');
//     $app->get('data/{id}', 'ChartController@find')->int('id');
//     $app->post('data/{id}/duplicate', 'ChartController@duplicate')->int('id');
//     $app->post('process', 'ChartController@processData');
//     $app->post('remove', 'ChartController@destroy');
// })->withPolicy('ChartPolicy');

// /*
//  * sources Route
//  */
// $app->group(function ($app) {
//     $app->get('/', 'SourceController@index');
//     $app->get('/{sourceId}', 'SourceController@find')->int('sourceId');
// })->prefix('sources')->withPolicy('SourcePolicy');


    $app->post('task', 'TaskController@store');

    $app->post('updateTask', 'TaskController@updateTask');

    $app->post('updateTask', 'TaskController@updateTask');

    $app->post('deleteTask', 'TaskController@deleteTask');

    $app->post('moveTask', 'TaskController@moveTask');

    $app->get('getTask', 'TaskController@getTask');

    