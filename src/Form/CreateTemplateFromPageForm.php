<?php

namespace RcmAdmin\Form;

use Rcm\Repository\Page;
use Rcm\Validator\Page as PageValidator;
use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Create Template from Page Zend Form Definition
 *
 * Create Template from Page Zend Form Definition
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class CreateTemplateFromPageForm extends Form implements ElementInterface
{

    /** @var \Rcm\Repository\Page */
    protected $pageRepo;

    /** @var \Rcm\Validator\Page */
    protected $pageValidator;

    /**
     * @var array
     */
    protected $safeValidatorServices = [
        'pageValidator' => null,
    ];


    /**
     * Constructor
     *
     * @param Page          $pageRepo      Rcm Page Repository
     * @param PageValidator $pageValidator Zend Page Validator
     */
    public function __construct(
        Page $pageRepo,
        PageValidator $pageValidator
    ) {
        $this->pageRepo = $pageRepo;
        $this->pageValidator = $pageValidator;

        $this->buildSafeValidators();

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function buildSafeValidators()
    {
        $this->safeValidatorServices['pageValidator'] = clone($this->pageValidator);
    }

    /**
     * Initialize the form
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function init()
    {
        $filter = new InputFilter();

        $this->add(
            [
                'name' => 'template-name',
                'options' => [
                    'label' => 'Template Name',
                ],
                'type' => 'text',

            ]
        );

        $filter->add(
            [
                'name' => 'template-name',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '-_',
                        ]
                    ],
                ],
                'validators' => [
                    $this->safeValidatorServices['pageValidator'],
                ],
            ]
        );
    }

    /**
     * Is Valid method for the new page form.  Adds a validation group
     * depending on if it's a new page or a copy of a template.
     *
     * @return bool
     */
    public function isValid()
    {
        $this->setValidationGroup(
            [
                'template-name'
            ]
        );

        return parent::isValid();
    }
}
