<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Listener;

use Jobs\Listener\Events\JobEvent;
use Jobs\Listener\Response\JobResponse;
use YawikXingVendorApi\Options\ModuleOptions;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


/**
 * Job listener for triggering actions like sending mail notification
 *
 * @package CamMediaintown\Listener
 */

class Publisher implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string
     */
    protected $name = 'XingVendorApi';

    /**
     * @var
     */
    protected $createWorkerCallback;

    public function __construct($createWorkerCallback)
    {
        $this->createWorkerCallback = $createWorkerCallback;
    }

    /**
     * Gets the name of this Publisher.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param JobEvent $event
     *
     * @return JobResponse
     */
    public function postJob(JobEvent $event)
    {
        $logger = $this->getLogger();
        if ($logger) {
            $job = $event->getJobEntity();
            $logger->info('++==++');
            $logger->info(sprintf(
                '{ Job | %s | %s | %s }',
                $job->id, $job->title, $job->organization->organizationName->name
            ));
        }

        if (!$event->hasPortal($this->getName())) {
            $logger && $logger->notice('==> Skipped... Job is not activated for XING Export.')
                              ->info('--==--');

            return new JobResponse($this->getName(), JobResponse::RESPONSE_OK, 'This portal is not activated for the job.');
        }

        $worker   = call_user_func($this->createWorkerCallback);
        $logger && $worker->setLogger($logger);
        $response = $worker->run($event, $this->getName());

        $logger && $logger->info('--==--');
        return $response;
    }
}
