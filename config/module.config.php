<?php
return array(
    'speck-checkout' => array(
        'strategy' => new \SpeckCheckout\Strategy\BasicCheckoutStrategy(array(
            new \SpeckCheckout\Strategy\Step\UserInformation
        )),
    ),

    'controllers' => array(
        'invokables' => array(
            'checkout'         => 'SpeckCheckout\Controller\CheckoutController',
            'user-information' => 'SpeckCheckout\Controller\UserInformationController',
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
                    'user-information' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/user-info',
                            'defaults' => array(
                                'controller' => 'user-information',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'default' => array(
                        'type' => 'Segment',
                        'priority' => -1000,
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
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
