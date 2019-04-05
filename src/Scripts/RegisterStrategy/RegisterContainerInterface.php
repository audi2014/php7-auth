<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 11:56 AM
 */

namespace Audi2014\Auth\Scripts\RegisterStrategy;


interface RegisterContainerInterface {
    public function register(RegisterStrategyInterface $v);

    public function get($key): RegisterStrategyInterface;
}