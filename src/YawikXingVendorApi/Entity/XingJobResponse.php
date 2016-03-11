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


use Core\Entity\AbstractEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Entity for Xing responses of the vendor API
 *
 * @see https://dev.xing.com/docs/post/vendor/jobs/postings
 * @author Carsten Bleek <bleek@cross-solution.de>
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 *
 * @ODM\EmbeddedDocument
 */
class XingJobResponse extends AbstractEntity implements XingJobResponseInterface
{

    /**
     * Creation date.
     *
     * @var \DateTime
     * @ODM\Field(type="tz_date")
     */
    protected $date;

    /**
     * @ODM\Integer
     *
     * @var int
     */
    protected $code;

    /**
     * @ODM\String
     *
     * @var
     */
    protected $body;

    public function __construct()
    {
        $this->date = new \DateTime('now');

        $args = func_get_args();

        if (isset($args[0])) {
            $this->setCode($args[0]);
        }

        if (isset($args[1])) {
            $this->setBody($args[1]);
        }
    }

    /**
     * @param mixed $body
     *
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param int $code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


}