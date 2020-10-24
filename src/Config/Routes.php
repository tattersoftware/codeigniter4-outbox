<?php

// Routes to Email Templates
$routes->group('emails', ['namespace' => '\Tatter\Outbox\Controllers'], function ($routes)
{
	$routes->get('templates/new/(:segment)', 'Templates::new/$1');
	$routes->presenter('templates', ['controller' => 'Templates']);
});
