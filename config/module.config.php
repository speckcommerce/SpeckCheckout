<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'checkout' => 'SpeckCheckout\Controller\CheckoutController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'checkout' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/checkout',
                    'defaults' => array(
                        'controller'    => 'checkout',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'speck-checkout' => __DIR__ . '/../view',
        ),
    ),
);
