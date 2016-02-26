<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Rcm\Entity\Redirect as RedirectEntity;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\RedirectException;

/**
 * Redirect Repository
 *
 * Redirect Repository.  Used to get redirects for the CMS
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Redirect extends EntityRepository
{
    /**
     * Get Redirect List From DB
     *
     * @param integer $siteId Site Id
     *
     * @return array
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getRedirectList($siteId)
    {
        try {
            $result = $this->getQuery($siteId)->getResult();
        } catch (NoResultException $e) {
            $result = [];
        }

        return $result;
    }


    /**
     * save
     *
     * @param RedirectEntity $redirect
     * @return void
     */
    public function save(\Rcm\Entity\Redirect $redirect)
    {
        /** @var \Rcm\Entity\Redirect $result */
        $result = $this->findOneBy(
            [
                'requestUrl' => $redirect->getRequestUrl(),
                'redirectUrl' => $redirect->getRedirectUrl(),
                'siteId' => $redirect->getSiteId(),
            ]
        );

        if (!empty($result) && $result->getRedirectId() !== $redirect->getRedirectId()) {
            throw new RedirectException('Duplicate redirects not allowed');
        }


        if ($redirect->getSiteId() !== null) {
            $siteRepo = $this->getEntityManager()->getRepository(
                'Rcm\Entity\Site'
            );

            $site = $siteRepo->find($redirect->getSiteId());

            if (empty($site)) {
                throw new RedirectException('Valid site required');
            }
            $redirect->setSite($site);
        }

        $this->getEntityManager()->persist($redirect);
        $this->getEntityManager()->flush($redirect);
    }

    /**
     * getRedirectEntityList
     *
     * @param $siteId
     * @return array
     */
    public function getRedirectEntityList($siteId)
    {
        try {
            $result = $this->getRedirectQuery($siteId)->getResult();
        } catch (NoResultException $e) {
            $result = [];
        }

        return $result;
    }

    /**
     * @param $url
     * @param $siteId
     *
     * @return null|RedirectEntity
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRedirect($url, $siteId)
    {
        if (empty($url)) {
            throw new InvalidArgumentException('No URL found to search by');
        }

        try {
            $result = $this->getQuery($siteId, $url)->getResult();
        } catch (NoResultException $e) {
            return null;
        }

        return array_pop($result);
    }

    /**
     * Get Doctrine Query
     *
     * @param      $siteId Site Id For Search
     * @param null $url Url for search
     *
     * @return \Doctrine\ORM\Query
     */
    private function getQuery($siteId, $url = null)
    {
        if (empty($siteId) || !is_numeric($siteId)) {
            throw new InvalidArgumentException('Invalid Site Id To Search By');
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder
            ->select('r')
            ->from('\Rcm\Entity\Redirect', 'r', 'r.requestUrl')
            ->leftJoin('r.site', 'site')
            ->where('r.site = :siteId')
            ->orWhere('r.site is null')
            ->orderBy('site.siteId', 'DESC')
            ->setMaxResults(1)
            ->setParameter('siteId', $siteId);

        if (!empty($url)) {
            $queryBuilder->andWhere('r.requestUrl = :requestUrl');
            $queryBuilder->setParameter('requestUrl', $url);
        }

        return $queryBuilder->getQuery();
    }


    /**
     * getRedirectQuery
     *
     * @param $siteId
     * @param null $url
     * @return \Doctrine\ORM\Query
     */
    private function getRedirectQuery($siteId, $url = null)
    {
        if (empty($siteId) || !is_numeric($siteId)) {
            throw new InvalidArgumentException('Invalid Site Id To Search By');
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder
            ->select('r.redirectId', 'r.requestUrl', 'r.redirectUrl')
            ->from('\Rcm\Entity\Redirect', 'r')
            ->leftJoin('r.site', 'site')
            ->where('r.site = :siteId')
            ->orWhere('r.site is null')
            ->setParameter('siteId', $siteId);

        if (!empty($url)) {
            $queryBuilder->andWhere('r.requestUrl = :requestUrl');
            $queryBuilder->setParameter('requestUrl', $url);
        }

        return $queryBuilder->getQuery();
    }
}
