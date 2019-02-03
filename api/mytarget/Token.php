<?php


namespace common\services\api\mytarget;


use Exception;
use common\repositories\TokenRepository;
use yii\base\ErrorException;

/**
 * Class Token
 * @package common\services\api\mytarget
 */
class Token
{
    private $tokenRequest;
    private $tokenRepository;

    /**
     * Token constructor.
     * @param TokenRequest $tokenRequest
     */
    public function __construct(TokenRequest $tokenRequest)
    {
        $this->tokenRequest = $tokenRequest;
        $this->tokenRepository = new TokenRepository();
    }

    /**
     * @throws Exception
     */
    public function refreshToken()
    {
        $result = $this->tokenRequest->send($this->tokenRequest->getAuthParams(), false);
        try {
            $this->setAccessToken($result->access_token);
            $this->setRefreshToken($result->refresh_token);
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * @return bool|\ErrorException
     * @throws Exception
     */
    public function setTokens()
    {
        $result = $this->tokenRequest->send($this->tokenRequest->getAuthParams(), false);

        if (!isset($result->access_token)) {
            return new \ErrorException('Превышен лимит запросов. Свяжитесь с тех. поддержкой');
        }

        $this->setAccessToken($result->access_token);
        $this->setRefreshToken($result->refresh_token);
        $this->setTokenType($result->token_type);
        $this->setTokensLeft($result->tokens_left);

        return true;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->tokenRepository->getValueAlias(TokenRepository::ALIAS_ACCESS_TOKEN);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setAccessToken($value)
    {
        return $this->tokenRepository->setValueAlias(TokenRepository::ALIAS_ACCESS_TOKEN, $value);
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->tokenRepository->getValueAlias(TokenRepository::ALIAS_REFRESH_TOKEN);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setRefreshToken($value)
    {
        return $this->tokenRepository->setValueAlias(TokenRepository::ALIAS_REFRESH_TOKEN, $value);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setTokenType($value)
    {
        return $this->tokenRepository->setValueAlias(TokenRepository::ALIAS_TOKEN_TYPE, $value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setTokensLeft($value)
    {
        return $this->tokenRepository->setValueAlias(TokenRepository::ALIAS_TOKENS_LEFT, $value);
    }

}