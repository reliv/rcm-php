<?php

namespace Rcm\Filter;

use Traversable;
use Zend\Filter\AbstractFilter;

/**
 * Class Ignore
 *
 * Strip any value and replace it.
 * Used primarily to prevent values from getting into APIs
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Filter
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Ignore extends AbstractFilter
{
    /**
     * @var array
     */
    protected $options = array(
        'replacementValue' => null,
    );

    /**
     * Constructor
     *
     * @param string|array|Traversable $typeOrOptions OPTIONAL
     */
    public function __construct($typeOrOptions = null)
    {
        if ($typeOrOptions !== null) {
            if ($typeOrOptions instanceof Traversable) {
                $typeOrOptions = iterator_to_array($typeOrOptions);
            }

            if (is_array($typeOrOptions)) {
                if (isset($typeOrOptions['replacementValue'])) {
                    $this->setOptions($typeOrOptions);
                }
            }
        }
    }

    /**
     * setReplacementValue
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setReplacementValue($value = null){
        $this->options['replacementValue'] = $value;
        return $this;
    }

    /**
     * getReplacementValue
     *
     * @return mixed
     */
    public function getReplacementValue()
    {
        return $this->options['replacementValue'] ;
    }

    /**
     * Defined by Zend\Filter\FilterInterface
     *
     * Returns null representation of $value, if value is empty and matches
     * types that should be considered null.
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        $replacementValue = $this->getReplacementValue();

        return $replacementValue;
    }
}
