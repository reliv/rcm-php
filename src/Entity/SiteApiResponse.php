<?php
namespace RcmAdmin\Entity;

use Rcm\Entity\Site;

/**
 * Class SiteApiResponse
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SiteApiResponse extends Site
{
    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'siteId' => $this->getSiteId(),
            'domain' => $this->getDomain()->getDomainName(),
            'theme' => $this->getTheme(),
            'siteLayout' => $this->getSiteLayout(),
            'siteTitle' => $this->getSiteTitle(),
            'language' => $this->getLanguage()->getIso6392t(),
            'country' => $this->getCountry()->getIso3(),
            'status' => $this->getStatus(),
            'favIcon' => $this->getFavIcon(),
            'loginPage' => $this->getLoginPage(),
            'notAuthorizedPage' => $this->getNotAuthorizedPage(),
            'notFoundPage' => $this->getNotFoundPage(),
            //'supportedPageTypes' => $this->getSupportedPageTypes(),
        ];
    }
}
