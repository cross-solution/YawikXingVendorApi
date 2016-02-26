<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Entity;

use Core\Entity\AbstractIdentifiableModificationDateAwareEntity as BaseEntity;
use Core\Entity\Collection\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * All Xing Vendor Api interaction data for a particular job.
 *
 * @ODM\Document(collection="yawikxingvendorapi.jobdata", repositoryClass="YawikXingVendorApi\Repository\JobData")
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobData extends BaseEntity
{


    /**
     * Id of the corresponding job
     *
     * @var string
     * @ODM\String
     */
    protected $jobId;

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
     * Collection of API Responses
     *
     * @var Collection
     * @ODM\EmbedMany(targetDocument="\YawikXingVendorApi\Entity\XingJobResponse")
     */
    protected $responses;

    /**
     * @param string|\MongoId $jobId
     *
     * @return self
     */
    public function setJobId($jobId)
    {
        $this->jobId = (string) $jobId; // typecast \MongoId objects.

        return $this;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param string $permalink
     *
     * @return self
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $postingId
     *
     * @return self
     */
    public function setPostingId($postingId)
    {
        $this->postingId = $postingId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostingId()
    {
        return $this->postingId;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $responses
     *
     * @return self
     */
    public function setResponses($responses)
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponses()
    {
        if (!$this->responses) {
            $this->responses = new ArrayCollection();
        }

        return $this->responses;
    }

    public function addResponse($code, $body)
    {
        if (200 <= (int) $code && 300 > (int) $code) {
            $data = is_array($body) ? $body : \Zend\Json\Json::decode($body, \Zend\Json\Json::TYPE_ARRAY);

            if (isset($data['posting_id'])) {
                $this->setPostingId($data['posting_id']);
            }

            if (isset($data['permalink'])) {
                $this->setPermalink($data['permalink']);
            }
        }

        $response = new XingJobResponse($code, $body);
        $responses = $this->getResponses();
        $responses->add($response);

        return $this;
    }

}