<?php

namespace BaseCardHero\Sheets\Tests\Unit;

use BaseCardHero\Sheets\Tests\TestCase;
use BaseCardHero\Sheets\Sheets;
use BaseCardHero\Sheets\SheetsInterface;

class SheetsTest extends TestCase
{
    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @var \Google_Service_Sheets
     */
    protected $service;

    /**
     * @var \BaseCardHero\Sheets\Sheets
     */
    protected $sheets;

    /**
     * Override of parent::setUp().
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = new \Google_Client();
        $this->service = new \Google_Service_Sheets($this->client);
        $this->sheets = new Sheets($this->service);
    }

    /** @test */
    public function SheetsSerivce_impliments_SheetsInterface()
    {
        $this->assertInstanceOf(SheetsInterface::class, $this->sheets);
    }

    /** @test */
    public function getService_will_return_the_Google_Service_Sheets()
    {
        $this->assertEquals($this->service, $this->sheets->getService());
    }

    /** @test */
    public function create_will_return_the_spreadsheet_id()
    {
        $title = 'My New Sheet';
        $sheetId = md5(mt_rand());
        $response = new \stdClass();
        $response->spreadsheetId = $sheetId;

        $this->service->spreadsheets = \Mockery::mock();
        $this->service->spreadsheets->shouldReceive('create')
            ->withArgs(function ($spreadsheet, $args) use ($title) {
                if ($title !== $spreadsheet['properties']['title']) {
                    return false;
                }

                if (['fields' => 'spreadsheetId'] !== $args) {
                    return false;
                }

                return true;
            })
            ->andReturn($response);

        $this->assertEquals($sheetId, $this->sheets->create($title));
    }
}
