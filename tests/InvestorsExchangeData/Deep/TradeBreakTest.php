<?php

namespace Digitonic\IexCloudSdk\Tests\InvestorsExchangeData\Deep;

use Digitonic\IexCloudSdk\Exceptions\WrongData;
use Digitonic\IexCloudSdk\Facades\InvestorsExchangeData\Deep\TradeBreak;
use Digitonic\IexCloudSdk\Tests\BaseTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

class TradeBreakTest extends BaseTestCase
{
    /**
     * @var Response
     */
    private $response;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new Response(200, [], '{"SNAP": [{"price": 156.1,"size": 100,"tradeId": 517341294,"isISO": false,"isOddLot": false,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1494619192003}]}');
    }

    /** @test */
    public function it_should_fail_without_a_symbol()
    {
        $mock = new MockHandler([$this->response]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $iexApi = new \Digitonic\IexCloudSdk\Client($client);

        $tradeBreak = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep\TradeBreak($iexApi);

        $this->expectException(WrongData::class);

        $tradeBreak->send();
    }

    /** @test */
    public function it_should_fail_when_last_is_greater_than_500()
    {
        $mock = new MockHandler([$this->response]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $iexApi = new \Digitonic\IexCloudSdk\Client($client);

        $tradeBreak = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep\TradeBreak($iexApi);

        $this->expectException(WrongData::class);

        $tradeBreak->setLast(1000)->setSymbols('SNAP')->send();
    }

    /** @test */
    public function it_can_query_the_deep_trade_break_endpoint()
    {
        $mock = new MockHandler([$this->response]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $iexApi = new \Digitonic\IexCloudSdk\Client($client);

        $tradeBreak = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep\TradeBreak($iexApi);

        $response = $tradeBreak->setLast(100)->setSymbols('SNAP')->send();

        $this->assertInstanceOf(Collection::class, $response);

        $response = $response->toArray();
        $this->assertCount(1, $response);
        $this->assertCount(9, (array) $response['SNAP'][0]);
    }

    /** @test */
    public function it_can_call_the_facade()
    {
        $this->app['config']->set('iex-cloud-sdk.base_url', 'https://cloud.iexapis.com/v1');
        $this->app['config']->set('iex-cloud-sdk.secret_key', 'KxDMt9GNVgu6fJUOG0UjH3d4kjZPTxFiXd5RnPhUD8Qz1Q2esNVIFfqmrqRD');
        $this->app['config']->set('iex-cloud-sdk.public_key', 'KxDMt9GNVgu6fJUOG0UjH3d4kjZPTxFiXd5RnPhUD8Qz1Q2esNVIFfqmrqRD');

        TradeBreak::shouldReceive('setSymbol')
            ->once()
            ->andReturnSelf();

        TradeBreak::setSymbol('SNAP');
    }
}
