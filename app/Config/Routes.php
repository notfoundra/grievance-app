<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Grievance::index');
$routes->group('grievance', function ($routes) {

    $routes->get('/', 'Grievance::index');
    $routes->get('case-log', 'Grievance::caseLog');
});
$routes->group('dashboard', function ($routes) {

    $routes->get('summary', 'DashboardController::summary');
});
$routes->group('case', function ($routes) {

    $routes->post('ajax-list', 'CaseController::ajaxList');

    $routes->get('case-detail/(:num)', 'CaseController::caseDetail/$1');
    $routes->get('new', 'CaseController::newCase');
    $routes->post('store', 'CaseController::store');
    $routes->get('attachment/(:num)', 'CaseController::downloadAttachment/$1');
});
