<?php
namespace RcmTest\Base;

interface PluginTestCaseInterface
{
    public function createDefaultSiteInstance();
    public function getDefaultCountry();
    public function getDefaultLanguage();
}