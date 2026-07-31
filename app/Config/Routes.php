<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('grievance', function ($routes) {

    $routes->get('/', 'GrievanceController::index');

    $routes->get('datatable', 'GrievanceController::datatable');

    $routes->get('dashboard-summary', 'GrievanceController::dashboardSummary');

    $routes->get('monthly-trend', 'GrievanceController::monthlyTrend');

    $routes->get('overdue', 'GrievanceController::overdueCases');

    $routes->get('detail/(:num)', 'GrievanceController::detail/$1');

    $routes->post('store', 'GrievanceController::store');

    $routes->post('update-status/(:num)', 'GrievanceController::updateStatus/$1');

    $routes->delete('delete/(:num)', 'GrievanceController::delete/$1');
});
