<?php

namespace Rcm\View\Model;

use Zend\View\Model\JsonModel;
use Traversable;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;
/**
 * Class ApiJsonModel
 *
 * Includes a wrapper for a standard return format
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

    /**
     * @param null   $variables
     * @param null   $options
     * @param int    $code
     * @param string $message
     */
    public function __construct($variables = null, $options = null, $code = 1, $message = '')
    {
        $this->setCode($code);
        $this->setMessage($message);
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
        $this->message = $message;
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
            'message' => $this->getMessage()
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