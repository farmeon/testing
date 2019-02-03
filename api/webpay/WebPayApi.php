<?php

namespace common\services\api\webpay;

use Yii;
use Exception;
use common\models\Order;


class WebPayApi
{
    private $url;
    private $tid;
    private $login;
    private $password;


    public function __construct($tid, $url = false)
    {
        if(!empty($url)) {
            $this->url = $url;
        }else{
            $this->url = Yii::$app->params['pay_site_api']['main'];
        }
        $this->tid = $tid;
        $this->login = Yii::$app->params['account_info']['login'];
        $this->password = Yii::$app->params['account_info']['password'];
    }

    /**
     * @return  object
     * @throws \yii\base\InvalidConfigException
     */
    public function getResultTransaction(){

        $content = $this->getContentByFindTransaction();

        try {
            $curl = curl_init ($this->url);
            curl_setopt ($curl, CURLOPT_HEADER, 0);
            curl_setopt ($curl, CURLOPT_POST, 1);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, $content);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $response = curl_exec ($curl);

            if ($response === FALSE)
                throw new Exception(curl_error($ch), curl_errno($ch));

            curl_close ($curl);

        } catch (Exception $e) {
            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        }

        $object = $this->parseResponseAnswer($response);

        return $this->checkPaymentTransaction($object);

    }

    /**
     * @return string
     */
    private function getContentByFindTransaction(){
        $postdata = '*API=&API_XML_REQUEST='.urlencode("<?xml version='1.0' encoding='ISO-8859-1'?>
                <wsb_api_request>
                    <command>get_transaction</command>
                    <authorization>
                        <username>$this->login</username>
                        <password>$this->password</password>
                    </authorization>
                    <fields>
                        <transaction_id>$this->tid</transaction_id>
                    </fields>
                </wsb_api_request>
                ");

        return $postdata;
    }

    /**
     * @param $response
     * @return \SimpleXMLElement
     */
    private function parseResponseAnswer($response)
    {
        $result = new \SimpleXMLElement($response);

        return $result;
    }

    /**
     * @param \SimpleXMLElement $object
     * @return bool|\SimpleXMLElement
     */
    private function checkPaymentTransaction(\SimpleXMLElement $object)
    {
        if($object->status == Order::STATUS_SUCCESS)
            return $object;

        return false;
    }



}