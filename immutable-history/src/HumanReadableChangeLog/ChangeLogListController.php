<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\ImmutableHistory\Acl\AclConstants;
use Rcm\ImmutableHistory\Http\CsvResponse;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetAllSortedChangeLogEventsByDateRange;
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
        GetAllSortedChangeLogEventsByDateRange $getHumanReadableChangeLogByDateRange,
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
            . '. Columns with "CURRENT" refer to values that were present at the time this report was generated'
            . ', NOT at the time the event took place.';

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
    protected function makeCsvResponse($description, $events)
    {
        return new CsvResponse(array_merge([[$description]], $this->changeLogEventsToTableArray($events)));
    }

    protected function changeLogEventsToTableArray(array $events): array
    {
        return array_merge(
            [
                [
                    'Date',
                    'User ID',
                    'User CURRENT name',
                    'Resource type',
                    'Resource parent CURRENT location',
                    'Resource location',
                    'How the resource was changed',
                    'Resource location data',
                    'Version ID'
                ]
            ],
            array_map(
                function (ChangeLogEvent $event) {
                    return [
                        $event->getDate()->format('c'),
                        $event->getUserId(),
                        $event->getUserDescription(),
                        $event->getResourceTypeDescription(),
                        $event->getParentCurrentLocationDescription(),
                        $event->getResourceLocationDescription(),
                        $event->getActionDescription(),
                        json_encode($event->getResourceLocatorArray()),
                        $event->getVersionId()
                    ];
                },
                $events
            )
        );
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
        $eventsTable = $this->changeLogEventsToTableArray($humanReadableEvents);

        $html = '<html class="container-fluid">';
        $html .= '<link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" ';
        $html .= 'media="screen" rel="stylesheet" type="text/css">';
        $html .= '<a href="?days=365&content-type=text%2Fcsv">Download CSV file for last 365 days</a>';
        $html .= '<p>' . $description . '</p>';
        $html .= '<table class="table table-sm">';
        foreach ($eventsTable as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td class="text-nowrap">' . $cell . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '</html>';

        return new HtmlResponse($html);
    }
}
