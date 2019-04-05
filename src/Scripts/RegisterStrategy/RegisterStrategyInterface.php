<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 31/07/2018
 * Time: 10:37
 */

namespace Audi2014\Auth\Scripts\RegisterStrategy;


use Audi2014\Auth\Scripts\AuthInterface;

interface RegisterStrategyInterface extends AuthInterface {


    /**
     * @return string
     */
    public function getType(): string;


}