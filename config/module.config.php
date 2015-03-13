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

    'service_manager' => array(
        'invokables' => array(
        ),
        'factories' => array(
            'Jobs/Xing'       => 'YawikXingVendorApi\Factory\Listener\PublisherFactory',
        ),
    ),
    'hybridauth' => array(
        'XING' => array(
            'authorizedUser' => 'demo'
        )
    )
);