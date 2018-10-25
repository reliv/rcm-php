<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;
use Rcm\Exception\RedirectException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateRedirect
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
     * @param array  $redirectData
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $options
     *
     * @return Redirect
     */
    public function __invoke(
        array $redirectData,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        $this->assertValid($redirectData);

        /** @var Redirect $redirectToUpdate */
        $newRedirect = new Redirect(
            $createdByUserId,
            $createdReason
        );

        $newRedirect->populate($redirectData);

        $this->repository->save($newRedirect);

        return $newRedirect;
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
