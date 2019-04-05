<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 30/07/2018
 * Time: 15:44
 */

namespace Audi2014\Auth;


trait ValidateExpiresInTrait {
    /**
     * @param $v
     * @return int
     * @throws \Exception
     */
    public static function validateExpiresIn($v) {
        $v = (int)$v;
        if($v && $v >= 60) {
            if($v > 60*60*24*365) {
                throw new \Exception("Max ExpiresIn = 1 Year = 60*60*24*365 = 31536000. ExpiresIn = $v");
            } else {
                return $v;
            }
        } else {
            throw new \Exception("Min ExpiresIn = 60 ExpiresIn = {$v}");
        }
    }

    /**
     * @param $v
     * @return int
     * @throws \Exception
     */
    public static function validateDeleteIn($v) {
        $v = (int)$v;
        if($v && $v >= 60) {
            if($v > 60*60*24*365*5) {
                throw new \Exception("Max DeleteIn = 5 Years = 60*60*24*365*5 = 157680000. DeleteIn = $v");
            } else {
                return $v;
            }
        } else {
            throw new \Exception("Min DeleteIn = 60 DeleteIn = {$v}");
        }
    }
}