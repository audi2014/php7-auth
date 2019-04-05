<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 11:56 AM
 */

namespace Audi2014\Auth\Scripts\LoginStrategy;

interface LoginContainerInterface {
    public function register(LoginStrategyInterface $v);

    public function get($key): LoginStrategyInterface;
}