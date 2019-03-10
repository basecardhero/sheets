<?php

namespace BaseCardHero\Sheets\Tests;

use BaseCardHero\Sheets\Spreadsheet;
use BaseCardHero\Sheets\Tests\TestCase;
use BaseCardHero\Sheets\SpreadsheetInterface;

class SpreadsheetTest extends TestCase
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
     * @var \BaseCardHero\Sheets\Spreadsheet
     */
    protected $spreadsheet;

    /**
     * Override of parent::setUp().
     */
    public function setUp()
    {
        parent::setUp();

        $spreadsheet = $this->createSpreadsheet();

        $this->client = new \Google_Client();
        $this->service = new \Google_Service_Sheets($this->client);
        $this->spreadsheet = $this->partial(Spreadsheet::class, [$this->service]);
        $this->spreadsheet->setSpreadsheet($spreadsheet);
    }

    /** @test */
    public function it_implements_SheetInterface()
    {
        $this->assertInstanceOf(SpreadsheetInterface::class, $this->spreadsheet);
    }

    /** @test */
    public function getService_will_return_the_Google_Service_Sheets()
    {
        $this->assertEquals($this->service, $this->spreadsheet->getService());
    }

    /** @test */
    public function getSpreadsheet_will_return_the_Google_Service_Sheets_Spreadsheet_instance()
    {
        $this->spreadsheet = $this->partial(Spreadsheet::class, [$this->service]);
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet();

        $this->assertNull($this->spreadsheet->getSpreadsheetId());

        $spreadsheet = $this->createSpreadsheet();
        $this->spreadsheet->setSpreadsheet($spreadsheet);

        $this->assertEquals($spreadsheet->getSpreadsheetId(), $this->spreadsheet->getSpreadsheetId());
    }

    public function getSpreadsheetId_will_return_the_spreadsheet_id()
    {
        $this->spreadsheet = $this->partial(Spreadsheet::class, [$this->service]);

        $this->assertNull($this->spreadsheet->getSpreadsheetId());

        $this->spreadsheet->setSpreadsheet($spreadsheet);

        $this->assertEquals($spreadsheet, $this->spreadsheet->getSpreadsheet());
    }

    /** @test */
    public function create_will_create_and_set_the_Google_Service_Sheets_Spreadsheet_instance()
    {
        $this->service->spreadsheets = $this->mock();
        $this->service->spreadsheets->shouldReceive()
            ->create(\Mockery::type(\Google_Service_Sheets_Spreadsheet::class), ['fields' => 'spreadsheetId'])
            ->andReturn(new \Google_Service_Sheets_Spreadsheet());

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->create());
        $this->assertInstanceOf(\Google_Service_Sheets_Spreadsheet::class, $this->spreadsheet->getSpreadsheet());
    }

    /** @test */
    public function retrieve_will_retrieve_a_Google_Service_Sheets_Spreadsheet_and_set_the_instance()
    {
        $spreadsheet = $this->createSpreadsheet();
        $this->service->spreadsheets = $this->mock();
        $this->service->spreadsheets->shouldReceive()
            ->get($spreadsheet->spreadsheetId)
            ->andReturn($spreadsheet);

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->retrieve($spreadsheet->spreadsheetId));
        $this->assertEquals($spreadsheet, $this->spreadsheet->getSpreadsheet());
    }

    /** @test */
    public function setTitle_will_add_a_Google_Service_Sheets_Request_to_the_stack()
    {
        $title = 'My Title';

        $this->service->spreadsheets = $this->mock();
        $this->service->spreadsheets->shouldReceive('batchUpdate')
            ->withArgs(function($id, $request) use ($title) {
                $this->assertEquals($this->spreadsheet->getSpreadsheetId(), $id);
                $this->assertEquals($title, $request->getRequests()[0]->updateSpreadsheetProperties->properties->title);

                return true;
            })->once();

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->setTitle($title));
    }

    /** @test */
    public function setColumns_will_add_a_Google_Service_Sheets_ValueRange_to_the_stack()
    {
        $columns = [
            [
                'range' => 'A1:A',
                'values' => [0, 1, 2, 3]
            ],
            [
                'range' => 'B1:B',
                'values' => ['a', 'b', 'c', 'd']
            ],
        ];

        $this->service->spreadsheets_values = $this->mock();
        $this->service->spreadsheets_values->shouldReceive('batchUpdate')
            ->withArgs(function($spreadsheetId, $request) {
                $this->assertEquals($spreadsheetId, $this->spreadsheet->getSpreadsheetId());
                $this->assertEquals('A1:A', $request->data[0]->range);
                $this->assertEquals([[0], [1], [2], [3]], $request->data[0]->values);
                $this->assertEquals('B1:B', $request->data[1]->range);
                $this->assertEquals([['a'], ['b'], ['c'], ['d']], $request->data[1]->values);

                return true;
            })->once();

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->setColumns($columns));
    }

    /** @test */
    public function clearRanges_will_add_array_of_ranges_to_the_collection()
    {
        $this->service->spreadsheets_values = $this->mock();
        $this->service->spreadsheets_values->shouldReceive('batchClear')
            ->withArgs(function($spreadsheetId, $request) {
                $this->assertEquals($spreadsheetId, $this->spreadsheet->getSpreadsheetId());
                $this->assertEquals('A1:A', $request->ranges[0]);
                $this->assertEquals('B1:B', $request->ranges[1]);

                return true;
            })->once();

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->clearRanges(['A1:A', 'B1:B']));
    }

    /** @test */
    public function copySheetFrom_will_add_a_spreadsheetId_and_sheetId_to_the_collection()
    {
        $sourceSpreadsheetId = md5(mt_rand());

        $this->service->spreadsheets_sheets = $this->mock();
        $this->service->spreadsheets_sheets->shouldReceive('copyTo')
            ->withArgs(function($spreadsheetId, $sheetId, $request) use ($sourceSpreadsheetId) {
                $this->assertEquals($sourceSpreadsheetId, $spreadsheetId);
                $this->assertEquals(1, $sheetId);
                $this->assertEquals($this->spreadsheet->getSpreadsheetId(), $request['destinationSpreadsheetId']);

                return true;
            })->once();

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->copySheetFrom($sourceSpreadsheetId, 1));
    }

    /** @test */
    public function deleteSheet_will_add_a_Google_Service_Sheets_Request_to_the_stack()
    {
        $this->service->spreadsheets = $this->mock();
        $this->service->spreadsheets->shouldReceive('batchUpdate')
            ->withArgs(function($id, $request) {
                $this->assertEquals($this->spreadsheet->getSpreadsheetId(), $id);
                $this->assertEquals(1, $request->getRequests()[0]->deleteSheet->sheetId);

                return true;
            })->once();

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->deleteSheet(1));
    }
}
