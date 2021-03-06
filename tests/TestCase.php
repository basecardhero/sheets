<?php

namespace BaseCardHero\Spreadsheet\Tests;

use \Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * Create a Google_Service_Sheets_Spreadsheet instance.
     *
     * @param string $spreadsheetId Optional spreadsheet id.
     *
     * @return \Google_Service_Sheets_Spreadsheet
     */
    protected function createSpreadsheet($spreadsheetId = null)
    {
        $spreadsheetId = $spreadsheetId ?? md5(mt_rand());

        return new \Google_Service_Sheets_Spreadsheet(['spreadsheetId' => $spreadsheetId]);
    }

    /**
     * Create a spy instance.
     *
     * @param string $abstract The class name. Default stdClass.
     * @param array $parameters Optional constructor parameters.
     *
     * @return \Mockery\MockInterface
     */
    protected function spy($abstract = \stdClass::class, $parameters = [])
    {
        return Mockery::spy($abstract, $parameters);
    }

    /**
     * Create a partial mock instance.
     *
     * @param string $abstract The class name. Default stdClass.
     * @param array $parameters Optional constructor parameters.
     *
     * @return \Mockery\MockInterface
     */
    protected function partial($abstract = \stdClass::class, $parameters = [])
    {
        return $this->mock($abstract, $parameters)->makePartial();
    }

    /**
     * Create a mock instance.
     *
     * @param string $abstract The class name. Default stdClass.
     * @param array $parameters Optional constructor parameters.
     *
     * @return \Mockery\MockInterface
     */
    protected function mock($abstract = \stdClass::class, $parameters = [])
    {
        return Mockery::mock($abstract, $parameters);
    }
}
