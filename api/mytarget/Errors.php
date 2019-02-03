<?php

namespace common\services\api\mytarget;

/**
 * Class Errors
 * @package common\services\api\mytarget\v2
 */
class Errors
{
    /**
     * @param $stdClass
     * @return bool
     */
    public function checkErrors($stdClass)
    {
        return property_exists($stdClass, 'error');
    }
}