<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 14:04
 */

namespace Audi2014\Auth\Scripts;


use Audi2014\Auth\AuthException;
use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\Scripts\LoginStrategy\LoginContainerInterface;
use Audi2014\Auth\Scripts\RegisterStrategy\RegisterContainerInterface;

class LoginOrRegister implements AuthInterface {
    private $loginContainer;
    private $registerContainer;


    public function __construct(
        LoginContainerInterface $loginContainer,
        RegisterContainerInterface $registerContainer

    ) {
        $this->loginContainer = $loginContainer;
        $this->registerContainer = $registerContainer;
    }


    /**
     * @param AuthRequestEntity $authRequestEntity
     * @param ProfileEntity $ProfileEntity
     * @param bool $checkEmailVerified
     * @throws AuthException
     */
    function auth(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        bool $checkEmailVerified = false
    ): void {

        $login = $this->loginContainer->get($authRequestEntity->type);

        $existProfile = $login->getCredential($authRequestEntity);
        if (!$existProfile) {
            $register = $this->registerContainer->get($authRequestEntity->type);
            $register->auth(
                $authRequestEntity,
                $ProfileEntity,
                $checkEmailVerified
            );
        } else {
            $ProfileEntity->setState((array)$existProfile);
            $login->auth(
                $authRequestEntity,
                $ProfileEntity,
                $checkEmailVerified
            );
        }


    }
}