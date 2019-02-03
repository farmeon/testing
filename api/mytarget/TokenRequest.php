<?php

namespace common\services\api\mytarget;

use Yii;

/**
 * Class TokenRequest
 * @package common\services\api\mytarget\v2
 */
class TokenRequest extends MyTargetApiRequest
{
    /**
     * TokenRequest constructor.
     * @param $url
     * @param $grantType
     * @param bool $refresh
     */
    public function __construct($url, $grantType, $refresh = false)
    {
        $this->url = $url;
        $this->params['grant_type'] = $grantType;
        $this->params['refresh'] = $refresh;
    }

    /**
     * @return array
     */
    public function getAuthParams()
    {
        $array = [
            'grant_type' => $this->params['grant_type'],
            'client_id' => Yii::$app->params['myTarget']['clientID'],
            'client_secret' => Yii::$app->params['myTarget']['clientSecret'],
        ];

        if (!empty($this->params['refresh'])) {
            $array['refresh_token'] = $this->getRefreshToken();
        }

        return $array;
    }
}