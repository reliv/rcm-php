<?php


namespace Rcm\View\Helper;

use Zend\View\Helper\Placeholder\Container\AbstractContainer;


/**
 * Class HeadLink
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class HeadLink extends \Zend\View\Helper\HeadLink
{
    /**
     * @var bool only set values once
     */
    protected $rcmIsSet = false;

    /**
     * headLink() - View Helper Method
     *
     * Returns current object instance. Optionally, allows passing array of
     * values to build link.
     *
     * @param  array  $attributes
     * @param  string $placement
     *
     * @return HeadLink
     */
    public function __invoke(
        array $attributes = null,
        $placement = AbstractContainer::APPEND
    ) {

        $this->setRcmDefaultLink();

        return parent::__invoke($attributes, $placement);
    }

    /**
     * setRcmDefaultLink
     *
     * @return void
     */
    public function setRcmDefaultLink()
    {
        if ($this->rcmIsSet) {
            return;
        }

        $view = $this->getView();

        if (!empty($view->site)) {

            $favicon = $view->site->getFavIcon();

            //Add Favicon for site
            if (!empty($favicon)) {
                $item = $this->createData(
                    [
                        'rel' => 'shortcut icon',
                        'type' => 'image/vnd.microsoft.icon',
                        'href' => $view->basePath() . $favicon,
                    ]
                );
                $this->append($item);
            }

            $this->rcmIsSet = true;
        }
    }
}