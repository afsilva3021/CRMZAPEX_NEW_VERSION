<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Login::index');
$routes->post('login', 'Auth::entrar', ['filter' => 'csrf']);
$routes->get('painel', 'Painel::index', ['filter' => 'autenticado']);
$routes->get('cadastros/(:segment)', 'Rotinas::cadastro/$1', ['filter' => 'autenticado']);
$routes->get('solicitacoes/(:segment)', 'Rotinas::solicitacao/$1', ['filter' => 'autenticado']);
$routes->get('administracao-compras/(:segment)', 'Rotinas::administracaoCompras/$1', ['filter' => 'autenticado']);
$routes->post('sair', 'Auth::sair', ['filter' => 'csrf']);
