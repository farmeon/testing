<?php

namespace common\services\api\currency;

/**
 * Class CurrencyUSD
 * @package common\services\api
 */
class CurrencyUSD
{
    const DEFAULT_URL = 'http://www.nbrb.by/API/ExRates/Rates/';

    private $id = 145;
    private $url = self::DEFAULT_URL;
    private $period = 0;

    /**
     * @param $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param $period
     */
    public function setPeriod(int $period)
    {
        $this->period = $period;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPeriod(): int
    {
        return $this->period;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUrlForUSD(): string
    {
        return self::DEFAULT_URL . $this->id . '?' . $this->period;
    }

}