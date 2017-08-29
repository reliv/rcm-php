<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;
use Rcm\Exception\RedirectException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateRedirect
{
    /**
     * @var \Rcm\Repository\Redirect
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Redirect::class
        );
    }

    /**
     * @param int    $id
     * @param array  $redirectData
     * @param string $modifiedByUserId
     * @param string $modifiedReason
     * @param array  $options
     *
     * @return null|Redirect
     */
    public function __invoke(
        int $id,
        array $redirectData,
        string $modifiedByUserId,
        string $modifiedReason,
        array $options = []
    ) {
        /** @var Redirect $redirectToUpdate */
        $redirectToUpdate = $this->repository->find($id);

        if (empty($redirectToUpdate)) {
            return null;
        }

        $this->assertValid($redirectData);

        $redirectToUpdate->setRedirectUrl($redirectData['redirectUrl']);
        $redirectToUpdate->setRequestUrl($redirectData['requestUrl']);
        $redirectToUpdate->setSiteId($redirectData['siteId']);
        $redirectToUpdate->setModifiedByUserId(
            $modifiedByUserId,
            $modifiedReason
        );

        $this->repository->save($redirectToUpdate);

        return $redirectToUpdate;
    }

    /**
     * @param array $redirectData
     *
     * @return void
     */
    protected function assertValid(array $redirectData)
    {
        if (!array_key_exists('redirectUrl', $redirectData)) {
            throw new RedirectException(
                'Missing required data: redirectUrl'
            );
        }
        if (!array_key_exists('requestUrl', $redirectData)) {
            throw new RedirectException(
                'Missing required data: requestUrl'
            );
        }
        if (!array_key_exists('siteId', $redirectData)) {
            throw new RedirectException(
                'Missing required data: siteId'
            );
        }
    }
}
