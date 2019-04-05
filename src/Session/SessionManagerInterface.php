<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 12:11 PM
 */

namespace Audi2014\Auth\Session;

use Audi2014\Auth\AuthRequest\AuthConfigEntity;
use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\AuthException;

interface SessionManagerInterface {
//    public function createSelfContainedToken(SessionEntity $session): string;

//    public function parseSelfContainedToken(string $s): ?array;


    public function createSession(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        SessionEntity &$sessionEntity
    ): void;

    /**
     * @param string $scToken
     * @param string $sessionClass
     * @param bool $requireEmailVerified
     * @return SessionEntity
     * @throws AuthException
     */
    public function authByScToken(
        string $scToken,
        $sessionClass = SessionEntity::class,
        $requireEmailVerified = true
    ): SessionEntity;

    /**
     * @param string $authToken
     * @param bool $requireEmailVerified
     * @return SessionEntity
     * @throws AuthException
     */
    public function authByAuthToken(string $authToken, $requireEmailVerified = true): SessionEntity;

    /**
     * @param string $refreshToken
     * @param AuthConfigEntity $cfg
     * @return SessionEntity
     * @throws AuthException
     * @throws \Exception
     */
    public function refresh(string $refreshToken, AuthConfigEntity $cfg): SessionEntity;

    /**
     * @param string $authToken
     * @return int
     * @throws AuthException
     */
    public function logout(string $authToken): int;
}