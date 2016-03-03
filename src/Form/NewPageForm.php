<?php

namespace RcmAdmin\Form;

use Rcm\Entity\Site;
use Rcm\Repository\Page;
use Rcm\Validator\Page as PageValidator;
use Rcm\Service\LayoutManager;
use Rcm\Validator\MainLayout;
use Rcm\Validator\PageTemplate;
use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * New Page Zend Form Definition
 *
 * New Page Zend Form Definition
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class NewPageForm extends Form implements ElementInterface
{
    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \Rcm\Repository\Page */
    protected $pageRepo;

    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    /** @var \Rcm\Validator\MainLayout */
    protected $layoutValidator;

    /** @var \Rcm\Validator\Page */
    protected $pageValidator;

    /** @var  \Rcm\Validator\PageTemplate */
    protected $templateValidator;

    /**
     * Constructor
     *
     * @param Site          $currentSite       Rcm Site
     * @param Page          $pageRepo          Rcm Page Repository
     * @param LayoutManager $layoutManager     Rcm Page Manager
     * @param MainLayout    $layoutValidator   Zend Layout Validator
     * @param PageValidator $pageValidator     Zend Page Validator
     * @param PageTemplate  $templateValidator Zend Page Template Validator
     */
    public function __construct(
        Site          $currentSite,
        Page          $pageRepo,
        LayoutManager $layoutManager,
        MainLayout    $layoutValidator,
        PageValidator $pageValidator,
        PageTemplate  $templateValidator
    ) {
        $this->currentSite       = $currentSite;
        $this->pageRepo          = $pageRepo;
        $this->layoutManager     = $layoutManager;
        $this->layoutValidator   = $layoutValidator;
        $this->pageValidator     = $pageValidator;
        $this->templateValidator = $templateValidator;

        parent::__construct();
    }

    /**
     * Initialize the form
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function init()
    {
        $pageList = $this->pageRepo->getAllPageIdsAndNamesBySiteThenType($this->currentSite->getSiteId(), 't');

        $pageList['blank'] = 'Blank Page (Experts Only)';

        $filter = new InputFilter();

        $this->add(
            [
                'name' => 'url',
                'options' => [
                    'label' => 'Page Url',
                ],
                'type' => 'text',

            ]
        );

        $filter->add(
            [
                'name' => 'url',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    [
                        'name' => 'StringTrim',
                        'options' => [
                            'charlist' => '-_',
                        ]
                    ],
                ],
                'validators' => [
                    $this->pageValidator,
                ],
            ]
        );

        $this->add(
            [
                'name' => 'title',
                'options' => [
                    'label' => 'Page Title',
                ],
                'type' => 'text',
            ]
        );

        $filter->add(
            [
                'name' => 'title',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => '\Zend\I18n\Validator\Alnum',
                        'options' => [
                            'allowWhiteSpace' => true,
                        ]
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name' => 'page-template',
                'options' => [
                    'label' => 'Page Template',
                    'value_options' => $pageList,
                ],
                'type' => 'Zend\Form\Element\Select',
            ]
        );

        $filter->add(
            [
                'name' => 'page-template',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    $this->templateValidator,
                ],
            ]
        );


        $this->add(
            [
                'name' => 'main-layout',
                'options' => [
                    'label' => 'Main Layout',
                    'layouts' => $this->layoutManager->getSiteThemeLayoutsConfig($this->currentSite->getTheme()),
                ],
                'type' => 'mainLayout',
            ]
        );

        $filter->add(
            [
                'name' => 'main-layout',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    $this->layoutValidator,
                ],
            ]
        );

        $this->setInputFilter($filter);
    }

    /**
     * Is Valid method for the new page form.  Adds a validation group
     * depending on if it's a new page or a copy of a template.
     *
     * @return bool
     */
    public function isValid()
    {
        if ($this->get('page-template')->getValue() == 'blank') {
            $this->setValidationGroup(
                [
                    'url',
                    'title',
                    'main-layout',
                ]
            );
        } else {
            $this->setValidationGroup(
                [
                    'url',
                    'title',
                    'page-template',
                ]
            );
        }
        return parent::isValid();
    }
}
