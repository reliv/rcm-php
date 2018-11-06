<?php

namespace Rcm\ImmutableHistory\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\ImmutableHistory\Acl\AclConstants;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetAllChangeLogEventSentencesForDateRange;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogByDateRangeComposite;
use RcmUser\Api\Acl\IsAllowed;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zrcms\CoreApplication\Api\ChangeLog\GetHumanReadableChangeLogByDateRange;

/**
 * This outputs the change log.
 *
 * Supports JSON, CSV, and HTML-table output depending "content-type" query param.
 *
 * Class ChangeLogHtml
 *
 * @package Zrcms\HttpChangeLog\Controller
 */
class ChangeLogListController implements MiddlewareInterface
{
    protected $getHumanReadableChangeLogByDateRange;

    protected $defaultNumberOfDays = 30;
    protected $isAllowed;

    /**
     * @param GetHumanReadableChangeLogByDateRange $getHumanReadableChangeLogByDateRange
     */
    public function __construct(
        GetAllChangeLogEventSentencesForDateRange $getHumanReadableChangeLogByDateRange,
        IsAllowed $isAllowed
    ) {
        $this->getHumanReadableChangeLogByDateRange = $getHumanReadableChangeLogByDateRange;
        $this->isAllowed = $isAllowed;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return \Psr\Http\Message\ResponseInterface|HtmlResponse|Response\JsonResponse
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if (!$this->isAllowed->__invoke($request, AclConstants::CONTENT_CHANGE_LOG, AclConstants::READ)) {
            $loginUrl = '/login?redirect=' . urlencode($request->getUri()->getPath()
                    . '?' . http_build_query($request->getQueryParams()));
            $response = new HtmlResponse('Access denied. Try <a href="' . $loginUrl . '">logging in</a>.');

            return $response;
        }

        $queryParams = $request->getQueryParams();
        $days = filter_var(
            isset($queryParams['days']) ? $queryParams['days'] : $this->defaultNumberOfDays,
            FILTER_VALIDATE_INT
        );

        if (!$days) {
            return new HtmlResponse('400 Bad Request - Invalid "days" param', 400);
        }

        $greaterThanYear = new \DateTime();
        $greaterThanYear = $greaterThanYear->sub(new \DateInterval('P' . $days . 'D'));
        $lessThanYear = new \DateTime();

        $humanReadableEvents = $this->getHumanReadableChangeLogByDateRange->__invoke($greaterThanYear, $lessThanYear);

        $description = 'Content change log events for ' . $days . ' days'
            . ' from ' . $greaterThanYear->format('c') . ' to ' . $lessThanYear->format('c')
            . '. Anything inside parentheses is from current lookups, is for convenience only,  and is not guaranteed to be historically accurate.';

        $contentType = isset($queryParams['content-type'])
            ? html_entity_decode($queryParams['content-type'])
            : 'application/json';

        switch ($contentType) {
            case 'text/html':
                return $this->makeHtmlResponse($description, $humanReadableEvents);
                break;
            case 'text/csv':
                return $this->makeCsvResponse($description, $humanReadableEvents);
                break;
            default:
                //Default which returns "application/json"
                return $this->makeJsonResponse($description, $humanReadableEvents);
        }
    }

    /**
     * @param $description
     * @param $humanReadableEvents
     *
     * @return JsonResponse
     */
    protected function makeJsonResponse($description, $humanReadableEvents)
    {
        return new JsonResponse(['listDescription' => $description, 'events' => $humanReadableEvents]);
    }

    /**
     * @param $description
     * @param $humanReadableEvents
     *
     * @return HtmlResponse
     */
    protected function makeCsvResponse($description, $humanReadableEvents)
    {
        $arrayToCsvLine = function (array $values) {
            $line = '';

            $values = array_map(
                function ($v) {
                    return '"' . str_replace('"', '""', $v) . '"';
                },
                $values
            );

            $line .= implode(',', $values);

            return $line . "\n";
        };


        $body = $arrayToCsvLine(['Date', $description]);
        foreach ($humanReadableEvents as $changeLogItem) {
            $body .= $arrayToCsvLine([$changeLogItem['date'], $changeLogItem['description']]);
        }

        return new HtmlResponse($body, 200, ['content-type' => 'text/csv']);
    }

    /**
     *
     * @param $description
     * @param $humanReadableEvents
     *
     * @return HtmlResponse
     */
    protected function makeHtmlResponse($description, $humanReadableEvents)
    {
        $html = '<html class="container-fluid">';
        $html .= '<link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" ';
        $html .= 'media="screen" rel="stylesheet" type="text/css">';
        $html .= '<a href="?days=365&content-type=text%2Fcsv">Download CSV file for last 365 days</a>';
        $html .= '<table class="table table-sm">';
        $html .= '<tr><th>Date</th>';
        $html .= '<th>' . $description . '</th>';
        $html .= '</tr>';
        foreach ($humanReadableEvents as $changeLogItem) {
            $html .= '<tr><td class="text-nowrap">'
                . $changeLogItem['date']
                . '</td><td>'
                . $changeLogItem['description'];
            '</td></tr>';
        }
        $html .= '</table>';
        $html .= '</html>';

        return new HtmlResponse($html);
    }
}
