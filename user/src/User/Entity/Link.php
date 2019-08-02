<?php

namespace RcmUser\User\Entity;

use RcmUser\Exception\RcmUserException;

/**
 * Class Link
 *
 * Link
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Link implements \JsonSerializable
{
    /**
     * @var string $title Link title
     */
    public $title = '';
    /**
     * @var string $type Used to determine type of link
     */
    public $type = ''; //'create, read, update, delete'
    /**
     * @var string $help Help text
     */
    public $help = '';
    /**
     * @var string $url Valid url
     */
    public $url = '';

    /**
     * setHelp
     *
     * @param string $help help info
     *
     * @return void
     */
    public function setHelp($help)
    {
        $this->help = $help;
    }

    /**
     * getHelp
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * setTitle
     *
     * @param string $title title of link
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * getTitle
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * setType
     *
     * @param string $type type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setUrl
     *
     * @param string $url URL
     *
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * getUrl
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this;
    }

    /**
     * populate
     *
     * @param array|Link $data data to populate this object with
     *
     * @return void
     * @throws RcmUserException
     */
    public function populate($data = [])
    {
        if ($data instanceof Link) {
            $this->setType($data->getType());
            $this->setTitle($data->getTitle());
            $this->setUrl($data->getUrl());
            $this->setHelp($data->getHelp());

            return;
        }

        if (is_array($data)) {
            if (isset($data['type'])) {
                $this->setType($data['type']);
            }
            if (isset($data['title'])) {
                $this->setTitle($data['title']);
            }
            if (isset($data['url'])) {
                $this->setUrl($data['url']);
            }
            if (isset($data['help'])) {
                $this->setHelp($data['help']);
            }

            return;
        }

        throw new RcmUserException('Link data could not be populated, date format not supported');
    }
}
