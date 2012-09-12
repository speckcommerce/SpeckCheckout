<?php
return array(
    'speck-checkout' => array(
        'strategy' => new \SpeckCheckout\Strategy\BasicCheckoutStrategy(array(
            new \SpeckCheckout\Strategy\Step\UserInformation,
            new \SpeckCheckout\Strategy\Step\PaymentInformation,
            new \SpeckCheckout\Strategy\Step\OrderReview,
        )),
        'payment_methods' => array(
            'check' => 'SpeckCheckout\PaymentMethod\Check',
            'fax'   => 'SpeckCheckout\PaymentMethod\Fax',
            'phone' => 'SpeckCheckout\PaymentMethod\Phone',
            'po'    => 'SpeckCheckout\PaymentMethod\PO',
            'quote' => 'SpeckCheckout\PaymentMethod\Quote',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'checkout'         => 'SpeckCheckout\Controller\CheckoutController',
            'payment'          => 'SpeckCheckout\Controller\PaymentController',
            'user-information' => 'SpeckCheckout\Controller\UserInformationController',
            'order-review'     => 'SpeckCheckout\Controller\OrderReviewController',
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
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/user-info',
                            'defaults' => array(
                                'controller' => 'user-information',
                                'action' => 'index',
                            ),
                        ),
                        'child_routes' => array(
                            'addresses' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/address',
                                    'defaults' => array(
                                        'action' => 'pick-addresses',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'order' => array(
                        'type' => 'Literal',
                        'may_terminate' => false,
                        'options' => array(
                            'route' => '/order',
                        ),
                        'child_routes' => array(
                            'review' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/review',
                                    'defaults' => array(
                                        'controller' => 'order-review',
                                        'action' => 'index',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'payment' => array(
                        'type' => 'Literal',
                        'may_terminate' => false,
                        'options' => array(
                            'route' => '/payment',
                            'defaults' => array(
                                'controller' => 'payment',
                            ),
                        ),
                        'child_routes' => array(
                            'main' => array(
                                'type' => 'Literal',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/payment',
                                    'defaults' => array(
                                        'controller' => 'payment',
                                        'action' => 'payment',
                                    ),
                                ),
                                'child_routes' => array(
                                    'query' => array(
                                        'type' => 'Query',
                                    ),
                                ),
                            ),
                            'choose' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/choose',
                                    'defaults' => array(
                                        'controller' => 'payment',
                                        'action' => 'index',
                                    ),
                                ),
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
