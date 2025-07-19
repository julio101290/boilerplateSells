<?php

$routes->group('admin', function ($routes) {


    $routes->resource('sells', [
        'filter' => 'permission:sells-permission',
        'controller' => 'SellsController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplatesells\Controllers',
    ]);

    $routes->get('newSells'
            , 'SellsController::newSell'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('editSell/(:any)'
            , 'SellsController::editSell/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->post('sells/save'
            , 'SellsController::save'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->post('sells/getLastCode'
            , 'SellsController::getLastCode'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('sells/report/(:any)'
            , 'SellsController::report/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );
    $routes->get('sells/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'SellsController::sellsFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('listSells/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'SellsController::sellsListFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('reporteVentas'
            , 'SellsController::reportSellsProducts'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('sellsReport/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'SellsController::sellsReport/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->post('payments/save'
            , 'PaymentsController::save'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('payments/getPayments/(:any)'
            , 'PaymentsController::ctrGetPayments/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );
    $routes->get('payments/delete/(:any)'
            , 'PaymentsController::delete/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('xmlenlace/getXMLEnlazados/(:any)'
            , 'SellsController::getXMLEnlazados/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('xmlenlace/getXMLEnlazadosCartaPorte/(:any)'
            , 'SellsController::getXMLEnlazadosCartaPorte/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );
    $routes->get('xml/xmlSinAsignar/(:any)'
            , 'SellsController::xmlSinAsignar/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('enlacexml/delete/(:num)'
            , 'EnlacexmlController::delete/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->post('xmlenlace/enlazaVenta'
            , 'SellsController::enlazaVenta'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('facturar/(:any)'
            , 'FacturaElectronicaController::timbrar/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('xml/generarPDFDesdeVenta/(:any)'
            , 'SellsController::generaPDFDesdeVenta/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('xml/descargaAcuseCancelacion/(:any)'
            , 'SellsController::descargaAcuseCancelacion/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('sells/dashboard/'
            , 'DashboardController::index'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('graficas/(:any)/(:any)'
            , 'DashboardController::traerInfo/$1/$2'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('graficas/(:any)/(:any)'
            , 'DashboardController::traerInfo/$1/$2'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );
    
    
    /**
     * Rutas para las notas de crédito
     */
    $routes->resource('notascredito', [
        'filter' => 'permission:listaNotaCredito-permission',
        'controller' => 'NotasCreditoController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplatesells\Controllers'
    ]);

    $routes->get('notasCredito/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'NotasCreditoController::notasCreditoFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );
    
    $routes->get('editNotaCredito/(:any)'
            , 'NotasCreditoController::editNotaCredito/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );
    $routes->get('pagos/delete/(:any)'
            , 'NotasCreditoController::delete/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );
    
    $routes->get('timbrarNotaCredito/(:any)'
            , 'FacturaElectronicaController::timbrarNotaCredito/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );

    $routes->get('xml/generarPDFDesdeNotaCredito/(:any)'
            , 'XmlController::generaPDFDesdeNotaCredito/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );

    $routes->get('newNotaCredito'
            , 'NotasCreditoController::newNotaCredito'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );

    $routes->post('notasCredito/save'
            , 'NotasCreditoController::save'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );

    $routes->get('xml/generarPDFNotaCredito/(:any)'
            , 'NotasCreditoController::generaPDFDesdeNotaCredito/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );
    
    $routes->get('xml/generarPDFDesdeRemNotaCredito/(:any)'
            , 'XmlController::generaPDFNotaCredito/$1'
            , ['namespace' => 'julio101290\FacturaElectronicaController\Controllers']
            );
    $routes->get('xmlenlace/getXMLEnlazadosNotaCredito/(:any)'
            , 'XmlController::getXMLEnlazadosNotaCredito/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
            );
});
