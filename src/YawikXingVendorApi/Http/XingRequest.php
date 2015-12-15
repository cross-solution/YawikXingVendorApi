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

use Zend\Http\Headers;
use Zend\Http\Request;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingRequest extends Request
{
    public function getHeaders($name = null, $default = false)
    {
        $headers = parent::getHeaders($name, $default);

        if (!$headers->has('Accept')) {
            $headers->addHeaderLine('Accept', 'application/vnd.xing.jobs.v1+json');
        }

        return $headers;
    }




}