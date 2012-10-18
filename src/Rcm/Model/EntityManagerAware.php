<?php
/**
 * Doctrine Aware Trait
 *
 * Allows any class to have an entity manager
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\HtmlArea
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
//namespace Rcm\Model;
//use Doctrine\ORM\EntityManager,
//    \Rcm\Exception\InvalidArgumentException;
//trait EntityManagerAware
//{
//    /**
//     * @var EntityManager entity manager
//     */
//    public $entityManager;
//
//    /**
//     * Get the Doctrine EntityManager.
//     *
//     * @return EntityManager $entityManager
//     *
//     * @throws InvalidArgumentException
//     */
//    public function getEm()
//    {
//
//        if (empty($this->entityManager)
//            || !is_a($this->entityManager, 'EntityManager')
//        ) {
//            throw new InvalidArgumentException(
//                'No Entity Manager Found.  Must be passed in when class
//                is created.'
//            );
//        }
//
//        return $this->entityManager;
//    }
//
//    /**
//     * Set the Doctrine Entity manager
//     *
//     * @param EntityManager $entityManager Entity Manager
//     *
//     * @return null
//     */
//    public function setEm(EntityManager $entityManager)
//    {
//        $this->entityManager = $entityManager;
//    }
//}