<?php

namespace Rcm\Block\Config;

/**
 * @GammaRelease
 * Class ConfigFields
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https =>//github.com/jerv13
 */
class ConfigFields
{
    const NAME = 'name';
    const DIRECTORY = 'directory';
    const CATEGORY = 'category';
    const LABEL = 'label';
    const DESCRIPTION = 'description';
    const RENDERER = 'renderer';
    const DATA_PROVIDER = 'data-provider';
    const ICON = 'icon';
    const EDITOR = 'editor';
    const CACHE = 'cache';
    const FIELDS = 'fields';
    const CONFIG = 'defaultConfig';

    protected $fields
        = [
            self::NAME => '',
            self::DIRECTORY => null,
            self::CATEGORY => null,
            self::LABEL => null,
            self::DESCRIPTION => null,
            self::RENDERER => null,
            self::DATA_PROVIDER => null,
            self::ICON => null,
            self::CACHE => false,
            self::FIELDS => [],
            self::CONFIG => [],
        ];

    protected $bcFields
        = [
            'name' => self::NAME,
            'directory' => self::DIRECTORY,
            'type' => self::CATEGORY,
            'display' => self::LABEL,
            'tooltip' => self::DESCRIPTION,
            'icon' => self::ICON,
            'canCache' => self::CACHE,
            'defaultInstanceConfig' => self::CONFIG,
        ];

    /**
     * prepare
     *
     * @param array $config
     *
     * @return array
     */
    public function prepare(array $config)
    {
        $new = array_merge($this->fields, $config);

        if (empty($new[self::CONFIG])) {
            $new[self::CONFIG] = $this->fieldsToConfig($new[self::FIELDS]);
        }

        return $new;
    }

    /**
     * fieldsToConfig
     *
     * @param array $fields
     *
     * @return array
     */
    protected function fieldsToConfig(array $fields)
    {
        $config = [];
        foreach ($fields as $field) {
            $config[$field['name']] = $field['default'];
        }

        return $config;
    }

    /**
     * getFieldBc
     *
     * @param string $fieldName
     *
     * @return mixed|null
     */
    public function getFieldBc($fieldName)
    {
        if (array_key_exists($fieldName, $this->bcFields)) {
            return $this->bcFields[$fieldName];
        }

        return $fieldName;
    }

    /**
     * convertBc
     *
     * @param string $name
     * @param array  $old
     *
     * @return array
     */
    public function convertBc($name, array $old)
    {
        $new = $this->fields;

        $new[self::NAME] = $name;

        foreach ($old as $fieldName => $value) {
            $newFieldName = $this->getFieldBc($fieldName);
            $new[$newFieldName] = $value;
        }

        if (empty($new[self::RENDERER])) {
            $new[self::RENDERER] = 'rcm-plugin-bc';
        }

        if (empty($new[self::EDITOR])) {
            $new[self::EDITOR] = 'rcm-plugin-bc';
        }

        return $new;
    }
}
