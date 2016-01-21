<?php
/**
 * YAWIK
 *
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 */

namespace YawikXingVendorApi\Repository;

use Core\Repository\AbstractRepository;
use Core\Repository\DoctrineMongoODM\PaginatorAdapter;

class XingJobResponse extends AbstractRepository
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
}