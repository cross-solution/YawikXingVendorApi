<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Entity;

/**
 * ${CARET}
 * 
 * @author Carsten Bleek <bleek@cross-solution.de>
 */
interface XingJobResponseInterface
{
    /**
     * @param mixed $postingId
     *
     * @return self
     */
    public function setPostingId($postingId);

    /**
     * @return mixed
     */
    public function getPostingId();

    /**
     * @param mixed $permalink
     *
     * @return self
     */
    public function setPermalink($permalink);

    /**
     * @return mixed
     */
    public function getPermalink();

    /**
     * @param mixed $message
     *
     * @return self
     */
    public function setMessage($message);

    /**
     * @return mixed
     */
    public function getMessage();

    /**
     * @param mixed $externalId
     *
     * @return self
     */
    public function setExternalId($externalId);

    /**
     * @return mixed
     */
    public function getExternalId();
}