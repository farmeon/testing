<?php

namespace common\services\api\mytarget;

use common\repositories\TokenRepository;
use common\services\dependency\CurlRequest;
use common\services\dependency\CurlRequestConfiguration;

/**
 * Class MyTargetApiRequest
 * @package common\services\api\mytarget\v2
 */
abstract class MyTargetApiRequest
{

    protected $url;
    protected $params = [];
    private $tokenRepository;

    protected abstract function getAuthParams();

    /**
     * @param $url
     * @param $params
     * @return static
     */
    public static function create($url, $params = [])
    {
        return new static($url, $params);
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            "Authorization: Bearer {$this->getAccessToken()}",
            "Content-Type: application/json"
        ];
    }

    /**
     * @return string
     */
    protected function getAccessToken()
    {
        $this->tokenRepository = new TokenRepository();
        return $this->tokenRepository->getValueAlias(TokenRepository::ALIAS_ACCESS_TOKEN);
    }

    /**
     * @return string
     */
    protected function getRefreshToken()
    {
        $this->tokenRepository = new TokenRepository();
        return $this->tokenRepository->getValueAlias(TokenRepository::ALIAS_REFRESH_TOKEN);
    }

    /**
     * @param bool $auth
     * @return mixed
     */
    private function sendCurlRequest($auth = false, $headers)
    {

        if (!empty($auth) && !empty($headers)) {
            $headers = $this->getHeaders();
            $config = new CurlRequestConfiguration($this->url, $auth, $headers);
        } elseif (!empty($auth) && empty($headers)) {
            $config = new CurlRequestConfiguration($this->url, $auth, false);
        } elseif (!empty($headers)) {
            $headers = $this->getHeaders();
            $config = new CurlRequestConfiguration($this->url, false, $headers);
        }
        $request = new CurlRequest($config);

        return $request->sendRequest();
    }

    /**
     * @param bool $auth
     * @param $headers
     * @return bool|\ErrorException|mixed
     * @throws \Exception
     */
    public function send($auth = false, $headers)
    {

        $this->tokenRepository = new TokenRepository();

        $errorObject = new TokenErrors($this->tokenRepository);
        $result = $this->sendCurlRequest($auth, $headers);

        if ($result instanceof \stdClass) {
            if ($errorObject->checkErrors($result)) {
                $error = $errorObject->fixErrors($result);
                if ($error instanceof \ErrorException) {
                    return $error;
                } else {
                    return $this->sendCurlRequest($auth, $headers);
                }
            }
        }

        return $result;
    }

}