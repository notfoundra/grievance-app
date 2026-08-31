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
        $routes->get('reports', 'ReportController::index');
        $routes->get('reports/monthly', 'ReportController::exportMonthly');
        $routes->get('quisioner', 'QuisionerController::index');
        $routes->get('quisioner/data/(:num)', 'QuisionerController::data/$1');
        $routes->post('quisioner/import', 'QuisionerController::import');
        $routes->get('quisioner/downloadTemplate', 'QuisionerController::downloadTemplate');
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
    $routes->group('grievance/master-data', ['filter' => 'auth:admin'], function ($routes) {
        $routes->get('/', 'MasterDataController::index');
        $routes->get('list/(:segment)', 'MasterDataController::list/$1');
        $routes->post('(:segment)/store', 'MasterDataController::store/$1');
        $routes->post('(:segment)/update/(:num)', 'MasterDataController::update/$1/$2');
        $routes->post('(:segment)/toggle/(:num)', 'MasterDataController::toggleActive/$1/$2');
    });
    $routes->group('user', function ($routes) {
        $routes->get('/', 'UserController::index');
        $routes->get('getData', 'UserController::getData');
        $routes->post('store', 'UserController::store');
        $routes->get('edit/(:num)', 'UserController::edit/$1');
        $routes->post('update/(:num)', 'UserController::update/$1');
        $routes->post('delete/(:num)', 'UserController::delete/$1');
        $routes->post('toggleStatus/(:num)', 'UserController::toggleStatus/$1'); // Tambahkan ini
    });
    $routes->group('reports', function ($routes) {
        $routes->get('export/suggestion-form', 'ReportController::exportSuggestionForm');
    });
});
$routes->get('lapor', 'PublicSubmissionController::form');
$routes->post('lapor/submit', 'PublicSubmissionController::submit', ['filter' => 'honeypot']);
$routes->group('grievance', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('qr-poster', 'QrCodeController::poster');
    $routes->get('qr-poster/image', 'QrCodeController::image');
});
$routes->group('grievance/import', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'ImportController::index');
    $routes->post('process', 'ImportController::process');
});
