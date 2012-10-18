<?php
/**
 * Abstract Class to use for factories
 *
 * Abstract Class to use for factories
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm\Model\
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Model;

/**
 * Abstract Class to use for factories
 *
 * Abstract Class to use for factories.  Addes Doctrine Entity to factories
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm\Model\
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */

abstract class FactoryAbstract
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityManager;

    /**
     * Gets the doctrine entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->entityManager;
    }

    /**
     * Sets the doctrine entity manager - this is used for testing only
     *
     * @param $entityManager \Doctrine\ORM\EntityManager doctrine entity manager
     *
     * @return null
     */
    function setEm(\Doctrine\ORM\EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }
}