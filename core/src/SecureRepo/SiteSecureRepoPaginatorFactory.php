<?php

namespace Rcm\SecureRepo;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Paginator\Paginator;

class SiteSecureRepoPaginatorFactory
{
    public function __invoke($query): Paginator
    {
        return new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
    }
}
