<?php

class EnsureHttps
{

    /**
     * Redirects to https version of current url is not already https
     */
    public function ensureHttps()
    {
        if (!$this->isHttps()) {
            $this->redirectHttps();
        }
    }

    /**
     * Redirect to the current page but on https
     */
    public function redirectHttps()
    {
        $url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $url);
        exit();
    }

    /**
     * returns if https or not
     * @return bool
     */
    public function isHttps()
    {
        return (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : null) == 'on';
    }

//    /**
//     * PHPUNIT Test
//     */
//    function testisHttps()
//    {
//        $_SERVER['HTTPS'] = 'on';
//        $this->assertTrue($this->basePluginController->isHttps());
//
//        $_SERVER['HTTPS'] = 'off';
//        $this->assertFalse($this->basePluginController->isHttps());
//
//        unset($_SERVER['HTTPS']);
//        $this->assertFalse($this->basePluginController->isHttps());
//    }
}