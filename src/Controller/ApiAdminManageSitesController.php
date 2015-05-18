<?php
/**
 * SitesApiController.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\Controller;

use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use Rcm\View\Model\RcmJsonModel;
use RcmAdmin\Entity\SiteApiRequest;
use RcmAdmin\Entity\SiteApiResponse;
use RcmAdmin\Entity\SiteResponse;
use RcmAdmin\InputFilter\SiteInputFilter;
use Zend\View\Model\JsonModel;

/**
 * ApiAdminManageSitesController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @method boolean rcmIsAllowed($resourceId, $privilege = null, $providerId = 'Rcm\Acl\ResourceProvider')
 */
class ApiAdminManageSitesController extends ApiAdminBaseController
{

    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        $sitesObjects = $siteRepo->findAll();

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            $sites[] = $this->buildSiteApiResponse($site);
        }

        return new ApiJsonModel($sites, 0, 'Success');
    }

    /**\
     * get
     *
     * @param mixed $id
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        // get default site data - kinda hacky, but keeps us to one controller
        if ($id == 'default') {
            $data = $this->getDefaultSiteSettings();

            $site = new Site();

            $site->populate($data);

            $result = $this->buildSiteApiResponse($site);

            return new ApiJsonModel($result, 0, 'Success');
        }

        // get current site data - kinda hacky, but keeps us to one controller
        if ($id == 'current') {
            $site = $this->getCurrentSite();

            $result = $this->buildSiteApiResponse($site);

            return new ApiJsonModel($result, 0, 'Success');
        }

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $this->getEntityManager()->getRepository('\Rcm\Entity\Site');

        try {
            $site = $siteRepo->find($id);
        } catch (\Exception $e) {
            return new ApiJsonModel(
                null,
                1,
                "Failed to find site by id ({$id})"
            );
        }

        $result = $this->buildSiteApiResponse($site);

        return new ApiJsonModel($result, 0, 'Success');
    }

    /**
     * update @todo - allow update of all properties and filter input
     *
     * @param mixed $siteId
     * @param mixed $data
     *
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function update($siteId, $data)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        if (!is_array($data)) {
            throw new \Exception('Invalid data format');
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        if (!$siteRepo->isValidSiteId($siteId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $this->getResponse();
        }

        /** @var \Rcm\Entity\Site $site */
        $site = $siteRepo->findOneBy(['siteId' => $siteId]);

        if ($data['status'] == 'D') {
            $site->setStatus('D');
        }
        if ($data['status'] == 'A') {
            $site->setStatus('A');
        }

        $entityManager->persist($site);
        $entityManager->flush();

        $data = $this->buildSiteApiResponse($site);

        return new JsonModel($data);
    }

    /**
     * create - Create a site
     *
     * @param array $data - see buildSiteApiResponse()
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        /* ACCESS CHECK */
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }
        /* */

        $inputFilter = new SiteInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new ApiJsonModel(
                [],
                1,
                'Some values are missing or invalid.',
                $inputFilter->getMessages()
            );
        }

        $data = $inputFilter->getValues();

        try {
            $data = $this->prepareNewSiteData($data);

            /** @var \Rcm\Repository\Domain $domainRepo */
            $domainRepo = $this->getEntityManager()->getRepository(
                '\Rcm\Entity\Domain'
            );

            $data['domain'] = $domainRepo->createDomain($data['domain']);

        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = new Site();

        $newSite->populate($data);
        // make sure we don't have a siteId
        $newSite->setSiteId(null);

        $author = $this->getCurrentAuthor();

        $pageRepo->createPages(
            $newSite,
            $this->getDefaultSitePageSettings($author),
            true,
            false
        );

        try {
            $entityManager->persist($newSite);

            $entityManager->flush();
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        try {
            $this->createPagePlugins($newSite, $this->getDefaultSitePageSettings($author), false);

            $entityManager->flush();
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        $siteApiResponse = $this->buildSiteApiResponse($newSite);

        return new ApiJsonModel($siteApiResponse, 0, 'Success');
    }

    /**
     * buildSiteApiResponse
     *
     * @param Site $site
     *
     * @return SiteApiResponse
     */
    protected function buildSiteApiResponse(Site $site)
    {
        $siteApiResponse = new SiteApiResponse();

        $siteApiResponse->populateFromObject($site);

        return $siteApiResponse;
    }

    //Model?//

    /**
     * Prepare Request Data
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     * @throws \Rcm\Repository\DomainNotFoundException
     */
    protected function prepareNewSiteData($data)
    {
        $data = $this->prepareDefaultValues($data);

        $entitymanager = $this->getEntityManager();

        // Site Id
        if (empty($data['siteId'])) {
            $data['siteId'] = null;
        }

        // Language
        if (empty($data['language'])) {
            throw new \Exception('Language is required to create new site.');
        }

        if (!empty($data['language'])) {
            /** @var \Rcm\Repository\Language $languageRepo */
            $languageRepo = $entitymanager->getRepository('\Rcm\Entity\Language');

            $data['language'] = $languageRepo->getLanguageByString(
                $data['language'],
                'iso639_2t'
            );
        } else {
            throw new \Exception(
                'Language format (iso639_2t) required to create new site.'
            );
        }

        if (!$data['language'] instanceof Language) {
            throw new \Exception('Language could not be found.');
        }

        // Country
        if (empty($data['country'])) {
            throw new \Exception('Country is required to create new site.');
        }

        if (!empty($data['country'])) {
            /** @var \Rcm\Repository\Country $countryRepo */
            $countryRepo = $entitymanager->getRepository('\Rcm\Entity\Country');

            $data['country'] = $countryRepo->getCountryByString(
                $data['country'],
                'iso3'
            );
        } else {
            throw new \Exception(
                'Country format (iso3) required to create new site.'
            );
        }

        if (!$data['country'] instanceof Country) {
            throw new \Exception('Country could not be found.');
        }

        return $data;
    }


    /**
     * prepareDefaultValues
     *
     * @param array $data
     *
     * @return array
     */
    protected function prepareDefaultValues($data)
    {
        $defaults = $this->getDefaultSiteSettings();

        foreach ($defaults as $key => $value) {
            if (empty($data[$key])) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * getDefaultSiteSettings
     *
     * @return array
     */
    public function getDefaultSiteSettings()
    {
        $config = $this->getConfig();

        $myConfig = $config['rcmAdmin']['defaultSiteSettings'];

        return $myConfig;
    }

    /**
     * getDefaultSiteSettings
     *
     * @return array
     */
    public function getDefaultSitePageSettings($author)
    {
        $myConfig = $this->getDefaultSiteSettings();

        $pagesData = $myConfig['pages'];

        // Set the author for each
        foreach ($pagesData as $key => $pageData) {
            $pagesData[$key]['author'] = $author;
        }

        return $pagesData;
    }

    /**
     * createPagePlugins
     *
     * @param Site  $site
     * @param array $pagesData
     * @param bool  $doFlush
     *
     * @return void
     * @throws \Exception
     */
    protected function createPagePlugins(Site $site, $pagesData = [], $doFlush = true)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $entityManager->getRepository('\Rcm\Entity\PluginInstance');

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $entityManager->getRepository('\Rcm\Entity\PluginWrapper');

        foreach ($pagesData as $pageName => $pageData) {
            if (empty($pageData['plugins'])) {
                continue;
            }

            $page = $pageRepo->getPageByName($site, $pageData['name']);

            if (!empty($page)) {
                $pageRevison = $page->getPublishedRevision();

                if (empty($pageRevison)) {
                    throw new \Exception("Could not find published revision for page {$page->getPageId()}");
                }

                foreach ($pageData['plugins'] as $pluginData) {
                    $pluginInstance = $pluginInstanceRepo->createPluginInstance(
                        $pluginData,
                        $site,
                        false
                    );

                    $pluginData['pluginInstanceId'] = $pluginInstance->getInstanceId();

                    $wrapper = $pluginWrapperRepo->savePluginWrapper($pluginData, $site);

                    $pageRevison->addPluginWrapper($wrapper);

                    $entityManager->persist($pageRevison);
                }
            }
        }

        if ($doFlush) {
            $entityManager->flush();
        }
    }
}
