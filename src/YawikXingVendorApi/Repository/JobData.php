<?php
/**
 * YAWIK
 *
 * @copyright (c) 2013 - 2016 Cross Solution (http://cross-solution.de)
 * @license   MIT
 */

namespace YawikXingVendorApi\Repository;

use Core\Repository\AbstractRepository;
use Core\Repository\DoctrineMongoODM\PaginatorAdapter;

class JobData extends AbstractRepository
{
    /**
     * Gets a pagination cursor to the XingJobResponse collection
     *
     * @param $params
     * @return mixed
     */
    public function getPaginatorCursor($params)
    {
        $filter = $this->getService('filterManager')->get('Xing/PaginationQuery');
        /* @var $filter \Core\Repository\Filter\AbstractPaginationQuery  */
        $qb = $filter->filter($params, $this->createQueryBuilder());
        return $qb->getQuery()->execute();
    }

    /**
     *
     *
     * @param $jobId
     *
     * @return \YawikXingVendorApi\Entity\JobData
     */
    public function findOrCreate($jobId)
    {
        $data = $this->findOneBy([ 'jobId' => (string) $jobId ]);

        if (!$data) {
            $data = $this->create([ 'jobId' => (string) $jobId ]);
        }

        return $data;
    }
}