<?php

namespace common\services\api\mytarget;

use yii\helpers\Json;

/**
 * Class ClientRequest
 * @package common\services\api\mytarget
 */
class ClientRequest extends MyTargetApiRequest
{
    /**
     * ClientRequest constructor.
     * @param $url
     * @param $params
     */
    public function __construct($url, $params)
    {
        $this->url = $url;

        if (!empty($params['name']) && !empty($params['description'])) {
            $this->params['name'] = $params['name'];
            $this->params['description'] = $params['description'];
        }
        if (!empty($params['client_id'])) {
            $this->params['client_id'] = $params['client_id'];
        }
        if (!empty($params['access_type'])) {
            $this->params['access_type'] = $params['access_type'];
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidArgumentException
     */
    public function getAuthParams()
    {
        if (isset($this->params['client_id'])) {
            $array = [
                'access_type' => 'readonly',
                'user' => [
                    'id' => $this->params['client_id']
                ]
            ];
        } elseif (isset($this->params['access_type'])) {
            $array = [
                'access_type' => 'full_access'
            ];
        } else {
            $array = [
                'access_type' => 'full_access',
                'user' => [
                    'additional_info' => [
                        'client_name' => $this->params['name'],
                        'client_info' => $this->params['description']
                    ]
                ]
            ];
        }

        return Json::encode($array);
    }

}