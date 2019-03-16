<?php

namespace BaseCardHero\Spreadsheet\Tests;

use BaseCardHero\Spreadsheet\Spreadsheet;
use BaseCardHero\Spreadsheet\Tests\TestCase;
use BaseCardHero\Spreadsheet\SpreadsheetInterface;

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
     * @var \BaseCardHero\Spreadsheet\Spreadsheet
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

    /** @test */
    public function getSpreadsheetId_will_return_the_spreadsheet_id()
    {
        $spreadsheet = $this->createSpreadsheet();

        $this->spreadsheet = $this->partial(Spreadsheet::class, [$this->service]);
        $this->assertNull($this->spreadsheet->getSpreadsheetId());

        $this->spreadsheet->setSpreadsheet($spreadsheet);
        $this->assertEquals($spreadsheet->getSpreadsheetId(), $this->spreadsheet->getSpreadsheetId());
    }

    /** @test */
    public function getSpreadsheetUrl_will_return_the_spreadsheet_url()
    {
        $spreadsheet = $this->createSpreadsheet();

        $this->spreadsheet = $this->partial(Spreadsheet::class, [$this->service]);
        $this->assertNull($this->spreadsheet->getSpreadsheetId());

        $this->spreadsheet->setSpreadsheet($spreadsheet);
        $this->assertEquals(
            sprintf('https://docs.google.com/spreadsheets/d/%s/edit', $spreadsheet->getSpreadsheetId()),
            $this->spreadsheet->getSpreadsheetUrl()
        );
    }

    /** @test */
    public function create_will_create_a_create_request_to_create_a_blank_spreadsheet()
    {
        $this->service->spreadsheets = $this->mock();
        $this->service->spreadsheets->shouldReceive()
            ->create(\Mockery::type(\Google_Service_Sheets_Spreadsheet::class), ['fields' => 'spreadsheetId'])
            ->andReturn(new \Google_Service_Sheets_Spreadsheet());

        $this->assertEquals($this->spreadsheet, $this->spreadsheet->create());
        $this->assertInstanceOf(\Google_Service_Sheets_Spreadsheet::class, $this->spreadsheet->getSpreadsheet());
    }

    /** @test */
    public function retrieve_will_create_a_get_request_for_the_given_spreadsheet_id()
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
    public function setTitle_will_create_a_batch_update_request_to_set_the_title()
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
    public function setColumns_will_create_a_batch_update_request_to_set_values()
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
    public function clearRanges_will_create_a_batch_clear_request_to_clear_a_range()
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
    public function copySheetFrom_will_create_a_request_to_copy_a_sheet()
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
    public function deleteSheet_will_create_a_batch_update_request_to_delete_a_sheet()
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
