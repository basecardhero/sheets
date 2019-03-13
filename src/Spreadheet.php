<?php

namespace BaseCardHero\Spreadsheet;

class Spreadsheet implements SpreadsheetInterface
{
    /**
     * @var \Google_Service_Sheets
     */
    protected $service;

    /**
     * @var \Google_Service_Sheets_Spreadsheet
     */
    protected $spreadsheet;

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
     * Set the Google_Service_Sheets_Spreadsheet instance.
     *
     * @param \Google_Service_Sheets_Spreadsheet $spreadsheet
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function setSpreadsheet(\Google_Service_Sheets_Spreadsheet $spreadsheet) : SpreadsheetInterface
    {
        $this->spreadsheet = $spreadsheet;

        return $this;
    }

    /**
     * Get Google_Service_Sheets_Spreadsheet object.
     *
     * @return \Google_Service_Sheets_Spreadsheet
     */
    public function getSpreadsheet() : ?\Google_Service_Sheets_Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * Get the spreadsheet id.
     *
     * @return string|null
     */
    public function getSpreadsheetId() : ?string
    {
        return ($this->getSpreadsheet())
            ? $this->getSpreadsheet()->getSpreadsheetId()
            : null;
    }

    /**
     * Get the spreadsheet url.
     *
     * @return string|null
     */
    public function getSpreadsheetUrl() : ?string
    {
        if ($this->getSpreadsheetId()) {
            return sprintf('https://docs.google.com/spreadsheets/d/%s/edit', $this->getSpreadsheetId());
        }
    }

    /**
     * Create a Google Sheet.
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function create() : SpreadsheetInterface
    {
        $this->spreadsheet = $this->service->spreadsheets->create(
            new \Google_Service_Sheets_Spreadsheet(),
            ['fields' => 'spreadsheetId']
        );

        return $this;
    }

    /**
     * Retrieve a Google_Service_Sheets_Spreadsheet from the REST API.
     *
     * @param string $spreadsheetId
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function retrieve(string $spreadsheetId) : SpreadsheetInterface
    {
        $this->spreadsheet = $this->service->spreadsheets->get($spreadsheetId);

        return $this;
    }

    /**
     * Set the spreadsheet title.
     *
     * @param string $title
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function setTitle(string $title) : SpreadsheetInterface
    {
        $this->service->spreadsheets->batchUpdate(
            $this->spreadsheet->spreadsheetId,
            new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => [
                    new \Google_Service_Sheets_Request([
                        'updateSpreadsheetProperties' => [
                            'fields' => 'title',
                            'properties' => [
                                'title' => $title
                            ]
                        ]
                    ])
                ]
            ])
        );

        return $this;
    }

    /**
     * Set columns values by range.
     *
     * Example;
     * $columns = [
     *     [
     *          'range' => 'A1:A',
     *          'values' => [0, 1, 2, 3]
     *     ],
     *     [
     *         'range' => 'B1:B',
     *         'values' => ['a', 'b', 'c', 'd']
     *     ],
     * ];
     *
     * @param array $columns
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function setColumns(array $columns) : SpreadsheetInterface
    {
        $data = array_map(function ($column) {
            return new \Google_Service_Sheets_ValueRange([
                'range' => $column['range'],
                'values' => \prepare_spreadsheet_values($column['values'])
            ]);
        }, $columns);

        $this->service->spreadsheets_values->batchUpdate(
            $this->spreadsheet->spreadsheetId,
            new \Google_Service_Sheets_BatchUpdateValuesRequest([
                'valueInputOption' => 'RAW',
                'data' => $data
            ])
        );

        return $this;
    }

    /**
     * Clear multiple ranges.
     *
     * @param array $ranges
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function clearRanges(array $ranges) : SpreadsheetInterface
    {
        $this->service->spreadsheets_values->batchClear(
            $this->spreadsheet->spreadsheetId,
            new \Google_Service_Sheets_BatchClearValuesRequest([
                'ranges' => $ranges
            ])
        );

        return $this;
    }

    /**
     * Copy a sheet from a spreadsheet.
     *
     * @param string $spreadsheetId
     * @param int $sheetId
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function copySheetFrom(string $spreadsheetId, int $sheetId) : SpreadsheetInterface
    {
        $this->service->spreadsheets_sheets->copyTo(
            $spreadsheetId,
            $sheetId,
            new \Google_Service_Sheets_CopySheetToAnotherSpreadsheetRequest([
                'destinationSpreadsheetId' => $this->spreadsheet->getSpreadsheetId()
            ])
        );

        return $this;
    }

    /**
     * Delete a sheet from the spreadsheet.
     *
     * @param int $sheetId
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function deleteSheet(int $sheetId) : SpreadsheetInterface
    {
        $this->service->spreadsheets->batchUpdate(
            $this->spreadsheet->spreadsheetId,
            new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => [
                    new \Google_Service_Sheets_Request([
                        'deleteSheet' => [
                            'sheetId' => $sheetId
                        ]
                    ])
                ]
            ])
        );

        return $this;
    }
}
