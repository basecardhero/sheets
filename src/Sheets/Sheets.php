<?php

namespace BaseCardHero\Sheets;

class Sheets implements SheetsInterface
{
    /**
     * @var \Google_Service_Sheets
     */
    protected $service;

    /**
     * @param \Google_Service_Sheets $service
     */
    public function __construct(\Google_Service_Sheets $service)
    {
        $this->service = $service;
    }

    /**
     * Get the Google_Service_Sheets object.
     *
     * @return \Google_Service_Sheets
     */
    public function getService() : \Google_Service_Sheets
    {
        return $this->service;
    }

    /**
     * Create a Google Sheet.
     *
     * @param string $title
     *
     * @return string The Google Sheet id.
     */
    public function create(string $title) : string
    {
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $title,
            ]
        ]);

        $response = $this->service->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId',
        ]);

        return $response->spreadsheetId;
    }
}
