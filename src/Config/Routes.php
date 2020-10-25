<?php

// Routes to Email Templates
$routes->group('emails', ['namespace' => '\Tatter\Outbox\Controllers'], function ($routes)
{
	$routes->get('templates/new/(:segment)', 'Templates::new/$1');
	$routes->get('templates/send/(:segment)', 'Templates::send/$1');
	$routes->post('templates/send/(:segment)', 'Templates::send_commit/$1');
	$routes->presenter('templates', ['controller' => 'Templates']);
});
