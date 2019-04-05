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

class LoginAny implements AuthInterface {

    private $loginContainer;

    /**
     * RegisterController constructor.
     *
     * @param LoginContainerInterface $loginContainer
     */
    public function __construct(LoginContainerInterface $loginContainer) {
        $this->loginContainer = $loginContainer;
    }

    /**
     * @param AuthRequestEntity $authRequestEntity
     * @param ProfileEntity $ProfileEntity
     * @param bool $checkEmailVerified
     * @throws AuthException
     * @throws \Audi2014\Auth\AuthException
     */
    function auth(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        bool $checkEmailVerified = false
    ): void {

        $strategy = $this->loginContainer->get($authRequestEntity->type);


        $existProfile = $strategy->getCredential($authRequestEntity);
        if (!$existProfile) {
            throw new AuthException(
                "account not found",
                AuthException::UI_ACCOUNT_NOT_REGISTERED
            );
        } else {
            $ProfileEntity->setState((array)$existProfile);
        }

        $strategy->auth(
            $authRequestEntity,
            $ProfileEntity,
            $checkEmailVerified
        );
    }
}