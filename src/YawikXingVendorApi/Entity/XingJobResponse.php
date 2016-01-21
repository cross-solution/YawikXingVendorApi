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


use Core\Entity\AbstractIdentifiableModificationDateAwareEntity as BaseEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Entity for saving Xing responses of the vendor API
 *
 * @see https://dev.xing.com/docs/post/vendor/jobs/postings
 * @author Carsten Bleek <bleek@cross-solution.de>
 *
 * @ODM\Document(collection="XingJobResponse", repositoryClass="YawikXingVendorApi\Repository\XingJobResponse")
 */
class XingJobResponse extends BaseEntity implements XingJobResponseInterface
{
    /**
     * @ODM\String
     * @var string
     */
    protected $postingId;

    /**
     * @ODM\String
     * @var string
     */
    protected $permalink;

    /**
     * @ODM\String
     * @var string Response Message
     */
    protected $message;

    /**
     * @ODM\String
     * @var
     */
    protected $externalId;

    /**
     * @param mixed $postingId
     *
     * @return self
     */
    public function setPostingId($postingId)
    {
        $this->postingId = $postingId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostingId()
    {
        return $this->postingId;
    }

    /**
     * @param mixed $permalink
     *
     * @return self
     */
    public function setPermalink($permalink)
    {
        $this->postingId = $permalink;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermalink()
    {
        return $this->permalink;
    }


    /**
     * @param mixed $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $externalId
     *
     * @return self
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
}