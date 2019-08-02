<?php

namespace RcmUser\User\Service;

use RcmUser\Event\EventProvider;
use RcmUser\Result;
use RcmUser\User\Entity\UserInterface;

/**
 * Class UserPropertyService
 *
 * UserPropertyService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserPropertyService extends EventProvider
{
    const EVENT_IDENTIFIER = UserPropertyService::class;

    const EVENT_GET_USER_PROPERTY = 'getUserProperty';
    const EVENT_POPULATE_USER_PROPERTY = 'populateUserProperty';
    const EVENT_GET_USER_PROPERTY_IS_ALLOWED = 'getUserPropertyIsAllowed';

    /**
     * getUserProperty
     *
     * @param UserInterface $user              user
     * @param string        $propertyNameSpace propertyNameSpace
     * @param null          $dflt              dflt
     * @param bool          $refresh           refresh
     *
     * @return mixed
     */
    public function getUserProperty(
        UserInterface $user,
        $propertyNameSpace,
        $dflt = null,
        $refresh = false
    ) {
        $property = $user->getProperty(
            $propertyNameSpace,
            null
        );

        // if a property is not set, see try to get it from an event listener
        if ($property === null || $refresh) {
            // @event getUserProperty.pre -
            $this->getEventManager()->trigger(
                self::EVENT_GET_USER_PROPERTY,
                $this,
                [
                    'user' => $user,
                    'propertyNameSpace' => $propertyNameSpace
                ]
            );
        }

        $property = $user->getProperty(
            $propertyNameSpace,
            $dflt
        );

        return $property;
    }

    /**
     * populateUserProperty
     * Build a new user property and populate data
     *
     * @param string $propertyNameSpace propertyNameSpace
     * @param mixed  $data              data to populate property
     *
     * @return Result
     */
    public function populateUserProperty(
        $propertyNameSpace,
        $data = []
    ) {
        $results = $this->getEventManager()->trigger(
            self::EVENT_POPULATE_USER_PROPERTY,
            $this,
            [
                'propertyNameSpace' => $propertyNameSpace,
                'data' => $data
            ],
            function ($result) {

                if ($result instanceof Result) {
                    return $result->isSuccess();
                }

                return false;
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        return new Result(null, Result::CODE_FAIL, 'No property found to populate.');
    }

    /**
     * @deprecated
     * getUserPropertyLinks
     * Get a link to an edit page for this user todo - write this
     *
     * @param UserInterface $user              user
     * @param string        $propertyNameSpace propertyNameSpace
     *
     * @return mixed
     */
    public function getUserPropertyLinks(
        UserInterface $user,
        $propertyNameSpace
    ) {
        $results = $this->getEventManager()->trigger(
            'getUserPropertyLinks',
            $this,
            [
                'user' => $user,
                'propertyNameSpace' => $propertyNameSpace
            ],
            function ($result) {

                if ($result instanceof Result) {
                    return $result->isSuccess();
                }

                return false;
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        return new Result(null, Result::CODE_FAIL, 'No property link found.');
    }

    /**
     * getUserPropertyIsAllowed
     * Check access for a user to a property
     * If no results returned todo - write this
     *
     * @param UserInterface $user              user
     * @param string        $propertyNameSpace propertyNameSpace
     *
     * @return mixed
     */
    public function getUserPropertyIsAllowed(
        UserInterface $user,
        $propertyNameSpace
    ) {
        $results = $this->getEventManager()->trigger(
            self::EVENT_GET_USER_PROPERTY_IS_ALLOWED,
            $this,
            [
                'user' => $user,
                'propertyNameSpace' => $propertyNameSpace
            ],
            function ($result) {

                if ($result instanceof Result) {
                    return $result->isSuccess();
                }

                return false;
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        return new Result(true, Result::CODE_FAIL, 'No Access property found.');
    }
}
