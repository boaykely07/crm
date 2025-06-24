<?php

use CodeIgniter\Router\RouteCollection;

/** 
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::loginPage');
$routes->get('login', 'AuthController::loginPage');
$routes->get('register', 'AuthController::registerPage');
$routes->post('auth/login', 'AuthController::login');
$routes->post('auth/logout', 'AuthController::logout');

$routes->post('auth/register', 'AuthController::register');
$routes->get('utilisateurs', 'AuthController::getAllUsers');
$routes->get('dashboard', 'AuthController::dashboard');


$routes->get('admin', 'AdminController::listePrevisionsPage');
$routes->get('/admin/listePrevisions', 'AdminController::listePrevisionsPage');
$routes->get('/admin/modifierPrevision/(:num)', 'PrevisionsController::modifierPage/$1');
$routes->post('/previsions/update/(:num)', 'PrevisionsController::update/$1');
$routes->get('/admin/validerPrevision/(:num)', 'PrevisionsController::validerPrevision/$1');
$routes->get('/admin/supprimerPrevision/(:num)', 'PrevisionsController::supprimerPrevision/$1');

$routes->get('previsions', 'PrevisionsController::index');
$routes->get('previsions/details/(:num)', 'PrevisionsController::viewDetails/$1');
$routes->get('realisations/details/(:num)', 'RealisationsController::viewDetails/$1');
$routes->get('vue-annuel', 'VueAnnuelController::index');
$routes->get('api/previsions', 'PrevisionsController::getPrevisions');
$routes->get('api/realisations', 'RealisationsController::getRealisations');
$routes->get('api/previsions/details/(:num)', 'PrevisionsController::getDetails/$1');
$routes->get('api/realisations/details/(:num)', 'RealisationsController::getDetails/$1');
$routes->post('previsions/save', 'PrevisionsController::save');
$routes->post('realisations/create', 'RealisationsController::create');
$routes->get('crm', 'CRMController::index');
$routes->get('export-pdf', 'ExportController::exportPDF');
$routes->get('realisations/validation/(:num)', 'RealisationsController::validation/$1');
$routes->post('realisations/save', 'RealisationsController::save');
$routes->post('previsions/update/(:num)', 'PrevisionsController::save');


$routes->get('realisations', 'RealisationController::index');
$routes->post('realisations/save', 'RealisationController::save');
$routes->post('realisations/update/(:num)', 'RealisationController::save');
$routes->get('/admin/listeRealisations', 'AdminController::listeRealisationsPage');
$routes->get('/admin/modifierRealisation/(:num)', 'RealisationController::modifierPage/$1');
$routes->get('/admin/validerRealisation/(:num)', 'RealisationController::validerRealisation/$1');
$routes->get('/admin/supprimerRealisation/(:num)', 'RealisationController::supprimerRealisation/$1');


$routes->get('/admin/listeBudgetCRM', 'AdminController::listeBudgetCRMPage');
$routes->get('/admin/validerBudgetCRM/(:num)', 'AdminController::validerBudgetCRM/$1');

// Routes pour la gestion des tickets
$routes->get('/admin/tickets', 'AdminController::listeTicketsPage');
$routes->post('/admin/tickets/update-status/(:num)', 'TicketController::updateStatus/$1');
$routes->post('/admin/tickets/assign-agent/(:num)', 'TicketController::assignAgent/$1');
$routes->post('/admin/tickets/assign-groupe/(:num)', 'TicketController::assignGroupe/$1');

$routes->post('crm/create-budget', 'CRMController::createBudgetPage');
$routes->get('crm/actions', 'ActionsController::index');
$routes->post('crm/actions/modifier-statut/(:num)', 'ActionsController::modifierStatut/$1');
$routes->get('crm/stats', 'StatsController::index');
$routes->options('(:any)', function() {
    // La gestion est faite par le filtre CORS, donc on peut juste retourner une réponse vide
    return;
});

// Routes pour le tableau de bord agent
$routes->get('/agent/dashboard', 'AgentController::dashboard');
$routes->get('/agent/mes-tickets', 'AgentController::mesTickets');
$routes->get('/agent/tickets-groupe', 'AgentController::ticketsGroupe');

// Routes pour les agents
// Dans app/Config/Routes.php
$routes->group('agent', ['filter' => 'agent'], function($routes) {
    $routes->get('dashboard', 'AgentController::dashboard');
    $routes->get('mes-tickets', 'AgentController::mesTickets');
    $routes->get('tickets-groupe', 'AgentController::ticketsGroupe');
    $routes->post('tickets/(:num)/status', 'TicketController::updateStatus/$1');
    $routes->get('tickets/(:num)/test', 'TicketController::testRoute/$1'); // Route de test
    $routes->post('tickets/(:num)/take', 'AgentController::takeTicket/$1');
});

$routes->get('/admin/listeMessageClient', 'AdminController::listeMessageClientPage');