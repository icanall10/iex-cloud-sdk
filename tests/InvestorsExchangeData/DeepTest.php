<?php

namespace Digitonic\IexCloudSdk\Tests\InvestorsExchangeData;

use Digitonic\IexCloudSdk\Exceptions\WrongData;
use Digitonic\IexCloudSdk\Facades\InvestorsExchangeData\Deep;
use Digitonic\IexCloudSdk\Tests\BaseTestCase;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

class DeepTest extends BaseTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new Response(200, [], '{"symbol": "AAPL","marketPercent": 0,"volume": 291434,"lastSalePrice": 227.98,"lastSaleSize": 72,"lastSaleTime": 1642407647509,"lastUpdated": 1583532710963,"bids": [],"asks": [],"systemEvent": {"systemEvent": "C","timestamp": 1606460974818},"securityEvent": {"securityEvent": "tklMseeoarC","timestamp": 1612946408324},"trades": [{"price": 225.7,"size": 73,"tradeId": 837666780,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1632966084545},{"price": 229.09,"size": 31,"tradeId": 846519139,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1604075048416},{"price": 229.95,"size": 51,"tradeId": 855950242,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1638109672093},{"price": 234.672,"size": 21,"tradeId": 859632145,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1570512915938},{"price": 234.24,"size": 104,"tradeId": 857306367,"isISO": false,"isOddLot": false,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1641935289906}],"tradeBreaks": []}');

        $this->client = $this->setupMockedClient($this->response);
    }

    /** @test */
    public function it_should_fail_without_a_symbol()
    {
        $deep = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep($this->client);

        $this->expectException(WrongData::class);

        $deep->get();
    }

    /** @test */
    public function it_can_query_the_deep_endpoint()
    {
        $deep = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep($this->client);

        $response = $deep->setSymbol('aapl')->get();

        $this->assertInstanceOf(Collection::class, $response);

        $response = $response->toArray();
        $this->assertCount(13, $response);
        $this->assertEquals('AAPL', $response['symbol']);
        $this->assertEquals(227.98, $response['lastSalePrice']);
        $this->assertCount(5, $response['trades']);
    }

    /** @test */
    public function it_can_call_the_facade()
    {
        $this->setConfig();

        Deep::shouldReceive('setSymbol')
            ->once()
            ->andReturnSelf();

        Deep::setSymbol('aapl');
    }
}
