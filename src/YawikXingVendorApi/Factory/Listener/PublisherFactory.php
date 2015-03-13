<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Factory\Listener;

use YawikXingVendorApi\Listener\Publisher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use YawikXingVendorApi\Options\ListenerPublisherOptions;

/**
 * Class RegisterFactory
 * @package Registration\Factory\Controller
 */
class PublisherFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RegistrationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $configJobPosterXing = array();
        if (array_key_exists('JobPoster', $config) && array_key_exists('XING', $config['JobPoster'])) {
            $configJobPosterXing = $config['JobPoster']['XING'];
        }
        $options = new ListenerPublisherOptions($configJobPosterXing);

        return new Publisher($options);
    }
}