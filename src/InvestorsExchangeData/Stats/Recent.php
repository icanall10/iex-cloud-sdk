<?php

namespace Digitonic\IexCloudSdk\InvestorsExchangeData\Stats;

use Digitonic\IexCloudSdk\Contracts\IEXCloud;
use Digitonic\IexCloudSdk\Requests\BaseGet;

class Recent extends BaseGet
{
    const ENDPOINT = 'stats/recent';

    /**
     * Create constructor.
     *
     * @param  IEXCloud  $api
     */
    public function __construct(IEXCloud $api)
    {
        parent::__construct($api);
    }

    /**
     * @return string
     */
    protected function getFullEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
