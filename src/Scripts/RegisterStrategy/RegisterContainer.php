<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 11:50 AM
 */

namespace Audi2014\Auth\Scripts\RegisterStrategy;


class RegisterContainer implements RegisterContainerInterface {
    private $data = [];
    public function __construct(array $data) {
        foreach ($data as $key => $v) {
            $this->register($v);
        }
    }
    public function register(RegisterStrategyInterface $v) {
        $this->data[$v->getType()] = $v;
        return $this;
    }
    public function  get($key):RegisterStrategyInterface {
        return $this->data[$key];
    }
}