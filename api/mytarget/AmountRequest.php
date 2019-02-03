<?php


namespace common\services\api\mytarget;

use yii\helpers\Json;

/**
 * Class AmountRequest
 * @package common\services\api\mytarget
 */
class AmountRequest extends MyTargetApiRequest
{
    /**
     * AmountRequest constructor.
     * @param $url
     * @param $amount
     */
    public function __construct($url, $params)
    {
        $this->url = $url;
        $this->params['amount'] = $params['amount'];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidArgumentException
     */
    public function getAuthParams()
    {
        $array = [
            'amount' => $this->params['amount']
        ];

        return Json::encode($array);
    }
}