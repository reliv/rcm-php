<?php

namespace RcmUser\Db;

use Doctrine\ORM\EntityManager;

/**
 * DoctrineMapperInterface
 *
 * DoctrineMapperInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface DoctrineMapperInterface
{
    /**
     * setEntityManager
     *
     * @param EntityManager $entityManager entityManager
     *
     * @return void
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * setEntityClass
     *
     * @param string $entityClass entityClass namespace
     *
     * @return void
     */
    public function setEntityClass($entityClass);

    /**
     * getEntityClass
     *
     * @return string
     */
    public function getEntityClass();
}
