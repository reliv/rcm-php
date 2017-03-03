<?php

namespace Rcm\Page\PageData;

/**
 * Interface RequestPage
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface RequestPage
{
    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * getType
     *
     * @return string
     */
    public function getType();

    /**
     * getRevision
     *
     * @return int
     */
    public function getRevision();
}
