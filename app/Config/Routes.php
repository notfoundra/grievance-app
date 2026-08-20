<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth routes (public)
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Semua route di bawah ini wajib login
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('/', 'Grievance::index');

    $routes->group('grievance', function ($routes) {
        $routes->get('/', 'Grievance::index');
        $routes->get('case-log', 'Grievance::caseLog');
        $routes->get('follow-up', 'Grievance::followUp');
        $routes->get('follow-up-data', 'Grievance::followUpData');
    });

    $routes->group('dashboard', function ($routes) {
        $routes->get('summary', 'DashboardController::summary');
    });

    $routes->group('case', function ($routes) {
        $routes->post('ajax-list', 'CaseController::ajaxList');
        $routes->get('case-detail/(:num)', 'CaseController::caseDetail/$1');
        $routes->post('case-detail/(:num)/update', 'CaseController::update/$1');
        $routes->post('case-detail/(:num)/follow-up', 'CaseController::addUpdate/$1');
        $routes->get('new', 'CaseController::newCase');
        $routes->post('store', 'CaseController::store');
        $routes->get('attachment/(:num)', 'CaseController::downloadAttachment/$1');
    });
});
$routes->group('grievance/import', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'ImportController::index');
    $routes->post('process', 'ImportController::process');
});
