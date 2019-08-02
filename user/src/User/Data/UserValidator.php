<?php

namespace RcmUser\User\Data;

use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Result;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class UserValidator
 *
 * UserValidator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserValidator implements UserValidatorInterface
{
    /**
     * @var array $userInputFilterConfig
     */
    protected $userInputFilterConfig;

    /**
     * @var InputFilterInterface $userInputFilter
     */
    protected $userInputFilter;

    /**
     * @var Factory $userInputFilterFactory
     */
    protected $userInputFilterFactory;

    /**
     * @var array $validatableFields
     */
    protected $validatableFields
        = [
            'username',
            'password',
            'state',
            'email',
            'name',
        ];

    /**
     * __construct
     *
     * @param Factory              $userInputFilterFactory InputFilterFactory
     * @param InputFilterInterface $userInputFilter        InputFilterClass
     * @param array                $userInputFilterConfig  Config array
     */
    public function __construct(
        Factory $userInputFilterFactory,
        InputFilterInterface $userInputFilter,
        $userInputFilterConfig = []
    ) {
        $this->setUserInputFilterFactory($userInputFilterFactory);
        $this->setUserInputFilter($userInputFilter);
        $this->setUserInputFilterConfig($userInputFilterConfig);
    }

    /**
     * setUserInputFilterConfig
     *
     * @param array $userInputFilterConfig userInputFilterConfig from module.config
     *
     * @return void
     */
    public function setUserInputFilterConfig($userInputFilterConfig)
    {
        $this->userInputFilterConfig = $userInputFilterConfig;
    }

    /**
     * getUserInputFilterConfig
     *
     * @return array
     */
    public function getUserInputFilterConfig()
    {
        return $this->userInputFilterConfig;
    }

    /**
     * setUserInputFilterClass
     *
     * @param InputFilterInterface $userInputFilter userInputFilter
     *
     * @return void
     */
    public function setUserInputFilter(
        InputFilterInterface $userInputFilter
    ) {
        $this->userInputFilter = $userInputFilter;
    }

    /**
     * getUserInputFilter
     *
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function getUserInputFilter()
    {
        return $this->userInputFilter;
    }

    /**
     * setUserInputFilterFactory
     *
     * @param Factory $userInputFilterFactory userInputFilterFactory
     *
     * @return void
     */
    public function setUserInputFilterFactory(Factory $userInputFilterFactory)
    {
        $this->userInputFilterFactory = $userInputFilterFactory;
    }

    /**
     * getUserInputFilterFactory
     *
     * @return Factory
     */
    public function getUserInputFilterFactory()
    {
        return $this->userInputFilterFactory;
    }

    /**
     * validateUpdateUser
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     * @param UserInterface $existingUser existingUser
     *
     * @return Result
     */
    public function validateUpdateUser(
        UserInterface $requestUser,
        UserInterface $responseUser,
        UserInterface $existingUser
    ) {
        $inputFilter = $this->getUserInputFilter();
        $factory = $this->getUserInputFilterFactory();

        $inputs = $this->getUserInputFilterConfig();

        foreach ($this->validatableFields as $field) {
            $getMethod = 'get' . ucfirst($field);

            if ($requestUser->$getMethod() !== $existingUser->$getMethod()
                && isset($inputs[$field])
            ) {
                $inputFilter->add(
                    $factory->createInput($inputs[$field]),
                    $field
                );
            }
        }

        $validateResult = $this->validateUser(
            $requestUser,
            $inputFilter
        );

        $responseUser->populate($validateResult->getUser());

        $validateResult->setData($responseUser);

        return $validateResult;
    }

    /**
     * validateCreateUser
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return Result
     */
    public function validateCreateUser(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        $inputFilter = $this->getUserInputFilter();
        $factory = $this->getUserInputFilterFactory();

        $inputs = $this->getUserInputFilterConfig();

        foreach ($this->validatableFields as $field) {
            if (isset($inputs[$field])
            ) {
                $inputFilter->add(
                    $factory->createInput($inputs[$field]),
                    $field
                );
            }
        }

        $validateResult = $this->validateUser(
            $requestUser,
            $inputFilter
        );

        $responseUser->populate($validateResult->getUser());

        $validateResult->setData($responseUser);

        return $validateResult;
    }

    /**
     * validateUser
     *
     * @param UserInterface        $requestUser requestUser
     * @param InputFilterInterface $inputFilter inputFilter
     *
     * @return Result
     */
    public function validateUser(
        UserInterface $requestUser,
        InputFilterInterface $inputFilter
    ) {
        $validUser = new User();
        $validUser->populate($requestUser);
        $inputFilter->setData($validUser);

        if ($inputFilter->isValid()) {
            $validUser->populate($inputFilter->getValues());

            return new Result($validUser);
        } else {
            $result
                = new Result($validUser, Result::CODE_FAIL, 'User input not valid');

            foreach ($inputFilter->getInvalidInput() as $error) {
                $msg = $error->getName() . ': ';

                $errs = $error->getMessages();

                foreach ($errs as $key => $val) {
                    $result->setMessage(
                        $msg .= "$val ({$key})"
                    );
                }
            }

            return $result;
        }
    }
}
