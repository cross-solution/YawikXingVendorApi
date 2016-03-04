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

use YawikXingVendorApi\Entity\XingData;


/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingClient
{
    protected $postfields;
    protected $urlBase = 'https://api.xing.com/vendor/jobs/postings';
    protected $logger;

    public function __construct($consumerKeys, $tokens, $logger = null)
    {
        $this->postfields = implode('&', [
            'oauth_token=' . $tokens['access_token'],
            'oauth_consumer_key=' . $consumerKeys['key'],
            'oauth_signature_method=PLAINTEXT',
            'oauth_signature=' . $consumerKeys['secret'] . '%26' . $tokens['access_token_secret'],
        ]);

        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function sendJob(XingData $data, $postingId=null)
    {
        $data = $data->toJson();
        $postfields = $this->postfields . '&' . $data;
        $action = $postingId ? 'UPDATE' : 'INSERT';

        return $this->request($action, $postfields, $postingId);
    }

    public function activateJob($postingId)
    {
        return $this->request('ACTIVATE', $this->postfields, $postingId);
    }

    public function deactivateJob($postingId)
    {
        return $this->request('DEACTIVATE', $this->postfields, $postingId);
    }

    public function deleteJob($postingId)
    {
        return $this->request('DELETE', $this->postfields, $postingId);
    }


    protected function request($action, $postFields, $postingId=null)
    {
        $url = $this->urlBase;
        $ch = curl_init();

        switch ($action) {
            case 'INSERT':
                curl_setopt($ch, CURLOPT_POST, true);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                $url .= '/' . $postingId;
                break;

            case 'UPDATE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                $url .= '/' . $postingId;
                break;

            default:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                $url .= '/' . $postingId . '/' . strtolower($action);
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $logger = $this->getLogger();
        $logger && $logger->debug('Api-Call: ' . $url . '; PostFields: ' . $postFields);

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return [ 'code' => $code, 'body' => $body,
                 'data' => \Zend\Json\Json::decode($body, \Zend\Json\Json::TYPE_ARRAY),
                 'success' => 200 <= (int) $code && 300 > (int) $code ];
    }
}