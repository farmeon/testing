<?php

namespace common\services\api\mytarget;

use Yii;
use common\repositories\TokenRepository;

/**
 * Class TokenErrors
 * @package common\services\api\mytarget
 */
class TokenErrors extends Errors
{
    private $tokenRepository;
    private $url;

    const TOKEN_EXPIRED = 'Access token is expired';
    const TOKEN_UNKNOWN = 'Unknown access token';
    const TOKEN_EXCEEDED = 'Active access token limit reached. Please contact support_target@corp.my.com and provide your client id in the message.';

    /**
     * TokenErrors constructor.
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
        $this->url = Yii::$app->params['api_url']['token'];
    }

    /**
     * @param $stdClass
     * @return bool|\ErrorException
     * @throws \Exception
     */
    public function fixErrors($stdClass)
    {

        if (isset($stdClass->error->message) && $stdClass->error->message === self::TOKEN_EXPIRED) {
            return $this->fixTokenExpiredError();
        }
        if (isset($stdClass->error->message) && $stdClass->error->message === self::TOKEN_UNKNOWN) {
            return $this->fixUnknownTokenError();
        }
        if (isset($stdClass->error) && isset($stdClass->error_description) && $stdClass->error_description === self::TOKEN_EXCEEDED) {
            return new \ErrorException('Превышен лимит запросов. Свяжитесь со тех. поддержкой');
        }
        return $stdClass;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function fixTokenExpiredError()
    {
        $tokenRequest = new TokenRequest($this->url, 'refresh_token', true);
        $token = new Token($tokenRequest);
        $token->refreshToken();

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function fixUnknownTokenError()
    {
        $tokenRequest = new TokenRequest($this->url, 'client_credentials');
        $token = new Token($tokenRequest);
        return $token->setTokens();
    }
}