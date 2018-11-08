<?php

namespace Rcm\ImmutableHistory\Http;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class CsvResponse extends Response\HtmlResponse implements ResponseInterface
{
    protected function arrayToCsv(array $array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);

        return ob_get_clean();
    }

    public function __construct(array $csvData, $statusCode = 200)
    {
        parent::__construct($this->arrayToCsv($csvData), $statusCode, ['content-type' => 'text/csv']);
    }
}
