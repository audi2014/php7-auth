<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 14:04
 */

namespace Audi2014\Auth\Scripts;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\Scripts\RegisterStrategy\RegisterContainerInterface;

class RegisterAny implements AuthInterface {
    private $registerContainer;

    /**
     * RegisterController constructor.
     *
     * @param RegisterContainerInterface $registerContainer
     */
    public function __construct(
        RegisterContainerInterface $registerContainer
    ) {
        $this->registerContainer = $registerContainer;
    }


    /**
     * @param AuthRequestEntity $authRequestEntity
     * @param ProfileEntity $ProfileEntity
     * @param bool $checkEmailVerified
     * @throws \Exception
     */
    function auth(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        bool $checkEmailVerified = false
    ): void {

        $strategy = $this->registerContainer->get($authRequestEntity->type);

        $strategy->auth(
            $authRequestEntity,
            $ProfileEntity,
            $checkEmailVerified
        );


    }
}