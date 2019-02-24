<?php

namespace BaseCardHero\Sheets;

interface SheetsInterface
{
    /**
     * Get the Google_Service_Sheets object.
     *
     * @return \Google_Service_Sheets
     */
    public function getService() : \Google_Service_Sheets;

    /**
     * Creat a Google Sheet.
     *
     * @param string $title
     *
     * @return string The Google Sheet Id.
     */
    public function create(string $title) : string;
}
