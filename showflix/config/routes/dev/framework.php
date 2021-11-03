<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
// Permet le test des pages d'erreurs lors de la phase de développement
return function (RoutingConfigurator $routes) {
    $routes->import('@FrameworkBundle/Resources/config/routing/errors.xml')
        ->prefix('/_error')
    ;
};
