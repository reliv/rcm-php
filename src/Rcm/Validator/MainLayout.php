<?php
/**
 * Rcm Main Layout Validator
 *
 * This file contains the class definition for the Main Layout Validator
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Validator;

use Rcm\Service\LayoutManager;
use Zend\Validator\AbstractValidator;

/**
 * Rcm Main Layout Validator
 *
 * Rcm Main Layout Validator. This validator will verify that the Main layout
 * exists.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class MainLayout extends AbstractValidator
{
    const MAIN_LAYOUT = 'pageTemplate';

    protected $messageTemplates = array(
        self::MAIN_LAYOUT => "'%value%' is not a valid layout."
    );

    /** @var \Rcm\Service\LayoutManager  */
    protected $layoutManager;

    protected $siteId = null;

    /**
     * Constructor
     *
     * @param LayoutManager $layoutManager Rcm Layout Manager
     */
    public function __construct(LayoutManager $layoutManager)
    {
        $this->layoutManager = $layoutManager;

        parent::__construct();
    }

    /**
     * Set the site id to use for validation.  If none is passed then we will
     * validate against the current site id.
     *
     * @param integer $siteId Site Id
     *
     * @return void
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * Is the layout valid?
     *
     * @param string $value Page to validate
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if (!$this->layoutManager->isLayoutValid($value, $this->siteId)) {
            $this->error(self::MAIN_LAYOUT);
            return false;
        }

        return true;
    }
}