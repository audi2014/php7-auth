<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 11:50 AM
 */

namespace Audi2014\Auth\Scripts\LoginStrategy;


class LoginContainer implements LoginContainerInterface {
    private $data = [];
    public function __construct(array $data) {
        foreach ($data as $key => $v) {
            /* @var $v LoginStrategyInterface */
            $this->register($v);
        }
    }

    public function register(LoginStrategyInterface $v) {
        $this->data[$v->getType()] = $v;
        return $this;
    }
    public function  get($key):LoginStrategyInterface {
        return $this->data[$key];
    }
}