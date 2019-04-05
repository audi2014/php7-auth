<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 31/07/2018
 * Time: 10:37
 */

namespace Audi2014\Auth\Scripts\LoginStrategy;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\AuthException;
use Audi2014\Auth\Scripts\AuthInterface;

interface LoginStrategyInterface extends AuthInterface {
    /**
     * @param AuthRequestEntity $authRequestEntity
     * @return ProfileEntity|null
     * @throws AuthException
     */
    public function getCredential(AuthRequestEntity $authRequestEntity): ?ProfileEntity;

    /**
     * @return string
     */
    public function getType(): string;


}