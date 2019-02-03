<?php

namespace common\services\api\mytarget;

use yii\helpers\Json;

/**
 * Class ManagerRequest
 * @package common\services\api\mytarget\v2
 */
class ManagerRequest extends MyTargetApiRequest
{
    /**
     * ManagerRequest constructor.
     * @param $url
     * @param $params
     */
    public function __construct($url, $params)
    {
        $this->url = $url;
        $this->params['name'] = $params['name'];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidArgumentException
     */
    public function getAuthParams()
    {

        $array = [
            'user' => [
                'username' => $this->params['name'],
                'additional_info' => [
                    'client_name' => $this->params['name']
                ]
            ]
        ];

        return Json::encode($array);
    }

}