<?php

namespace Rcm\View\Model;

use Zend\View\Model\JsonModel;
use Traversable;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;
/**
 * Class ApiJsonModel
 *
 * Includes a wrapper for a standard return format for client
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class ApiJsonModel extends JsonModel {

    protected $code = 1;

    protected $message = '';

    protected $errors = array();

    /**
     * @param null   $variables
     * @param null   $options
     * @param int    $code - 0 for success
     * @param string $message - General public message for client
     * @param array  $errors - Example - Pass the messages from input validator
     */
    public function __construct($variables = null, $options = null, $code = 0, $message = '', $errors = array())
    {
        $this->setCode($code);
        $this->setMessage($message);
        $this->setErrors($errors);
        parent::__construct($variables, $options);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = (string) $message;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * setErrors
     *
     * @param array $errors
     *
     * @return void
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * setError
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function setError($name, $value)
    {
        $name = (string) $name;
        $this->errors[$name] = $value;
    }

    /**
     * serialize
     *
     * @return string
     */
    public function serialize()
    {
        $result = array(
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'errors' => $this->getErrors()
        );

        $result['data'] = $this->getVariables();

        if ($result['data'] instanceof Traversable) {
            $result['data'] = ArrayUtils::iteratorToArray($result['data']);
        }

        if (null !== $this->jsonpCallback) {
            return $this->jsonpCallback.'('.Json::encode($result).');';
        }

        return Json::encode($result);
    }
} 