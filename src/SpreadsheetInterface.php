<?php

namespace BaseCardHero\Spreadsheet;

interface SpreadsheetInterface
{
    /**
     * Get the Google_Service_Sheets object.
     *
     * @return \Google_Service_Sheets
     */
    public function getService() : \Google_Service_Sheets;

    /**
     * Set the Google_Service_Sheets_Spreadsheet instance.
     *
     * @param \Google_Service_Sheets_Spreadsheet $spreadsheet
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function setSpreadsheet(\Google_Service_Sheets_Spreadsheet $spreadsheet) : SpreadsheetInterface;

    /**
     * Get Google_Service_Sheets_Spreadsheet object.
     *
     * @return \Google_Service_Sheets_Spreadsheet
     */
    public function getSpreadsheet() : ?\Google_Service_Sheets_Spreadsheet;

    /**
     * Get the spreadsheet id.
     *
     * @return string|null
     */
    public function getSpreadsheetId() : ?string;

    /**
     * Get the spreadsheet url.
     *
     * @return string|null
     */
    public function getSpreadsheetUrl() : ?string;

    /**
     * Create a spreadsheet and set the spreadsheet instance.
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function create() : SpreadsheetInterface;

    /**
     * Retrieve a Google_Service_Sheets_Spreadsheet from the REST API.
     *
     * @param string $spreadsheetId
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function retrieve(string $spreadsheetId) : SpreadsheetInterface;

    /**
     * Set the spreadsheet title.
     *
     * @param string $title
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function setTitle(string $title) : SpreadsheetInterface;

    /**
     * Set columns values by range.
     *
     * @param array $columns
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function setColumns(array $columns) : SpreadsheetInterface;

    /**
     * Clear multiple ranges.
     *
     * @param array $ranges
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function clearRanges(array $ranges) : SpreadsheetInterface;

    /**
     * Copy a sheet from a spreadsheet.
     *
     * @param string $spreadsheetId
     * @param int $sheetId
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function copySheetFrom(string $spreadsheetId, int $sheetId) : SpreadsheetInterface;

    /**
     * Delete a sheet from the spreadsheet.
     *
     * @param int $sheetId
     *
     * @return \BaseCardHero\Spreadsheet\SpreadsheetInterface
     */
    public function deleteSheet(int $sheetId) : SpreadsheetInterface;
}
