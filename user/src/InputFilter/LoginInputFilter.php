<?php

namespace RcmUser\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class LoginInputFilter
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\InputFilter
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class LoginInputFilter extends InputFilter
{
    /**
     * @var array
     */
    protected $filterConfig = [
        'username' => [
            'name' => 'username',
            'required' => true,
        ],
        'password' => [
            'name' => 'password',
            'required' => true,
        ],
    ];

    /**
     * Set data to use when validating and filtering
     * - We wait to build the fields until setData is called
     *
     * @param  array|Traversable $data
     *
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function setData($data)
    {
        $this->build($data);

        return parent::setData($data);
    }

    /**
     * build input filter from config
     *
     * @return void
     */
    protected function build($data)
    {
        $factory = $this->getFactory();

        foreach ($this->filterConfig as $field => $config) {
            $this->add(
                $factory->createInput(
                    $config
                )
            );
        }
    }
}
