<?php

namespace Rcm\View\Model;

use Traversable;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\JsonModel;

/**
 * @deprecated Use rcm-api-lib controller::getApiResponse
 * Class ApiJsonModel
 *
 * Includes a wrapper for a standard return format for client
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiJsonModel extends JsonModel
{
    /**
     * @var int
     */
    protected $code = 1;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param null      $variables
     * @param mixed|int $code    - 0 for success
     * @param string    $message - General public message for client
     * @param array     $errors  - Example - Pass the messages from input validator
     * @param null      $options
     */
    public function __construct($variables = null, $code = 0, $message = 'OK', $errors = [], $options = null)
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
        $this->message = (string)$message;
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
     * @param mixed  $value
     *
     * @return void
     */
    public function setError($name, $value)
    {
        $name = (string)$name;
        $this->errors[$name] = $value;
    }

    /**
     * serialize
     *
     * @return string
     */
    public function serialize()
    {
        $result = [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'errors' => $this->getErrors()
        ];

        $result['data'] = $this->getVariables();

        if ($result['data'] instanceof Traversable) {
            $result['data'] = ArrayUtils::iteratorToArray($result['data']);
        }

        if (null !== $this->jsonpCallback) {
            return $this->jsonpCallback . '(' . Json::encode($result) . ');';
        }

        return Json::encode($result);
    }

    /**
     * @param array|\ArrayAccess|Traversable $variables
     * @param bool                           $overwrite
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function setVariables($variables, $overwrite = false)
    {
        if (is_array($variables) || $variables instanceof Traversable) {
            return parent::setVariables($variables, $overwrite);
        }

        if ($variables instanceof \JsonSerializable) {
            $variables = $variables->jsonSerialize();

            return parent::setVariables($variables, $overwrite);
        }

        if (is_object($variables) && method_exists($variables, 'toArray')) {
            $variables = $variables->toArray();

            return parent::setVariables($variables, $overwrite);
        }

        if (is_object($variables) && method_exists($variables, '_toArray')) {
            $variables = $variables->_toArray();

            return parent::setVariables($variables, $overwrite);
        }

        if (is_object($variables) && method_exists($variables, '__toArray')) {
            $variables = $variables->__toArray();

            return parent::setVariables($variables, $overwrite);
        }

        return parent::setVariables($variables, $overwrite);
    }
}
