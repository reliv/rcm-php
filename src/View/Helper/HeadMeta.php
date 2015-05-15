<?php

namespace Rcm\View\Helper;

use Rcm\Entity\Page;
use Zend\View\Helper\Placeholder\Container\AbstractContainer;


/**
 * Class HeadMeta
 *
 * HeadMeta over-ride ZF2
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
class HeadMeta extends \Zend\View\Helper\HeadMeta
{

    /**
     * @var bool only set values once
     */
    protected $rcmIsSet = false;

    /**
     * Retrieve object instance; optionally add meta tag
     *
     * @param  string $content
     * @param  string $keyValue
     * @param  string $keyType
     * @param  array  $modifiers
     * @param  string $placement
     *
     * @return HeadMeta
     */
    public function __invoke(
        $content = null,
        $keyValue = null,
        $keyType = 'name',
        $modifiers = array(),
        $placement = AbstractContainer::APPEND
    ) {
        $this->setRcmDefaultMeta();

        return parent::__invoke(
            $content,
            $keyValue,
            $keyType,
            $modifiers,
            $placement
        );
    }

    /**
     * setRcmDefaultMeta
     *
     * @return void
     */
    public function setRcmDefaultMeta()
    {
        if ($this->rcmIsSet) {
            return;
        }

        $view = $this->getView();

        if (!empty($view->page)) {

            $description = $view->page->getDescription();

            if (!empty($description)) {
                $this->setName('description', strip_tags($description));
            }

            $keywords = $view->page->getKeywords();

            if (!empty($keywords)) {
                $this->setName('keywords', strip_tags($keywords));
            }

            $this->rcmIsSet = true;
        }
    }
}
