<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

return array(

    'hybridauth' => [
        'XingVendorApi' => [
            'enabled' => 'true',

            // This is a hack due to bad design of HybridAuth
            // There's no simpler way to include "additional-providers"
            "wrapper" => [
                'class' => 'Hybrid_Providers_XING',
                'path' => __FILE__,
            ],
            'scope' => '',
        ],
    ],

    'log' => [
        'Log/YawikXingVendorApi/Publisher' => [
            'writers' => [
                [
                    'name' => 'stream',
                    'options' => [
                        'stream' => 'log/yawik-xing-vendor-api.publisher.log',
                        'formatter' => [
                            'name' => 'simple',
                            'options' => [
                                'dateTimeFormat' => 'Y-m-d H:i:s',
                                'format' => '%timestamp% {%uniqueId%} [%priorityName%]: %message%'
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                [
                    'name' => 'Core/UniqueId',
                ],
            ],
        ],
    ],

    'options' => array(
        'YawikXingVendorApi/ModuleOptions' => array(
            'class' => 'YawikXingVendorApi\Options\ModuleOptions',
        ),
        'YawikXingVendorApi/PublisherOptions' => array(
            'class' => 'YawikXingVendorApi\Options\PublisherOptions',
            'options' => array(
                'name' => 'Xing',
            ),
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
        ),
        'factories' => array(
            'YawikXingVendorApi/Listener' => 'YawikXingVendorApi\Factory\Listener\DeferredPublisherFactory',
            'YawikXingVendorApi/Listener/Publisher' => 'YawikXingVendorApi\Factory\Listener\PublisherFactory',
            'YawikXingVendorApi/Listener/PublisherWorker' => 'YawikXingVendorApi\Factory\Listener\PublisherWorkerFactory',
        ),
    ),

    'navigation' => array(
        'default' => array(
            'XingVendorAPI' => array(
                'label' => /*@translate*/ 'Xing Vendor API',
                'route' => 'lang/xing-vendor-api',
                'order' => '200',
                'resource' => 'route/lang/xing-vendor-api'
            ),
        ),
    ),

    'acl' => array(
        'rules' => array(
            'user' => array(
                'allow' => array(
                    'route/lang/xing-vendor-api' => array(
                        '__ALL__' => 'XingVendorApi/AuthorizedUser',
                    ),
                ),
            ),
        ),
        'assertions' => array(
            'factories' => array(
                'XingVendorApi/AuthorizedUser' => 'YawikXingVendorApi\Factory\Acl\AuthorizedUserAssertionFactory',
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'lang' => array(
                'child_routes' => array(
                    'xing-vendor-api' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/xing-vendor-api',
                            'defaults' => array(
                                'controller' => 'XingVendorApi/XingAuth',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'XingVendorApi/XingAuth' => 'YawikXingVendorApi\Controller\XingAuth',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'yawik-xing-vendor-api/xing-auth/index' => __DIR__ . '/../view/xing-auth.phtml',
        ),
    ),
);