<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Http;

use Traversable;
use Zend\Http\Client;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingClient extends Client
{
    public function __construct($uri = null, $options = null)
    {
        $this->config['ssltransport'] = 'tls';
        $this->config['verifypeer'] = 'false';
        $this->config['adapter'] = 'Zend\Http\Client\Adapter\Curl';

        parent::__construct($uri, $options);
    }

    public function sendJob($parameters, $consumerKeys, $tokens)
    {
        $oauthParameters = $this->getOauthParameters($consumerKeys, $tokens);
        $postParameters  = array_merge($parameters, $oauthParameters);
        $request = $this->getRequest();
        $headers = $request->getHeaders();

        $request->setUri('https://api.xing.com/vendor/jobs/postings');
        $request->setMethod('POST');
        $request->getPost()->fromArray($postParameters);

        if (!$headers->has('Accept')) {
            $headers->addHeaderLine('Accept', 'application/json'); //'application/vnd.xing.jobs.v1+json');
        }

        return $this->send($request);
    }

    protected function getOauthParameters($consumerKeys, $tokens)
    {
        //$nonce = md5(microtime() . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''));

        $parameters = [
        //    'oauth_version'   => '1.0',
        //    'oauth_nonce'     => $nonce,
        //    'ouath_timestamp' => time(),
            'oauth_token' => $tokens['access_token'],
            'oauth_consumer_key' => $consumerKeys['key'],
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_signature' => $consumerKeys['secret'] . '%26' . $tokens['access_token_secret']

        ];

        return $parameters;
    }
}