<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/20/19
 * Time: 4:52 PM
 */

namespace Audi2014\Auth\Session;


use Audi2014\Auth\AuthRequest\AuthConfigEntity;
use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\AuthException;
use Audi2014\Auth\ValidateExpiresInTrait;
use Audi2014\Crypto\CryptoInterface;

class SessionManager implements SessionManagerInterface {
    use validateExpiresInTrait;
    private $crypto;
    private $sessionRepo;
    private $sessionLifeCycle;

    public function __construct(
        CryptoInterface $crypto,
        SessionRepo $SessionRepo,
        SessionLifeCycleInterface $sessionLifeCycle
    ) {
        $this->crypto = $crypto;
        $this->sessionRepo = $SessionRepo;
        $this->sessionLifeCycle = $sessionLifeCycle;
    }


    /**
     * @param string $authToken
     * @param bool $requireEmailVerified
     * @return SessionEntity
     * @throws AuthException
     */
    public function authByAuthToken(string $authToken, $requireEmailVerified = true): SessionEntity {
        $session = $this->sessionRepo->fetchFirstByKeyValue('AuthToken', $authToken);
        if (!$session) {
            throw new AuthException("wrong AuthToken ", AuthException::UI_TOKEN);
        }
        if (($lifeTime = ($session->expiresIn + $session->updatedAt) - time()) < 0) {
            $end = date("Y-m-d H:i:s", $session->updatedAt + $session->expiresIn);
            $time = date("Y-m-d H:i:s", time());
            throw new AuthException("session token expire: $end - $time = $lifeTime", AuthException::UI_TOKEN);
        }
        if (
            $requireEmailVerified
            && $session->type == 'email'
            && !$session->isEmailVerified
        ) {
            throw new AuthException('email not verified', AuthException::UI_LOGIN_OK_BUT_NOT_VERIFIED);
        }
        $session->scToken = $this->createSelfContainedToken(
            $session
        );
        return $session;
    }

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
    ): SessionEntity {
        $session = new $sessionClass($this->parseSelfContainedToken($scToken));

        if (($lifeTime = ($session->expiresIn + $session->updatedAt) - time()) < 0) {
            $end = date("Y-m-d H:i:s", $session->updatedAt + $session->expiresIn);
            $time = date("Y-m-d H:i:s", time());
            throw new AuthException("session expire: $end - $time = $lifeTime", AuthException::UI_TOKEN);
        }
        if (
            $requireEmailVerified
            && $session->type == 'email'
            && !$session->isEmailVerified
        ) {
            throw new AuthException('email not verified', AuthException::UI_LOGIN_OK_BUT_NOT_VERIFIED);
        }
        return $session;

    }

    /**
     * @param string $authToken
     * @return int
     * @throws AuthException
     */
    public function logout(string $authToken): int {
        $count = $this->sessionRepo->deleteRowsByKeyValue('authToken', $authToken);
        if ($count < 1) {
            throw new AuthException("session not found", AuthException::UI_TOKEN);
        } else {
            return $count;
        }
    }

    /**
     * @param string $refreshToken
     * @param AuthConfigEntity $cfg
     * @return SessionEntity
     * @throws AuthException
     * @throws \Exception
     */
    public function refresh(string $refreshToken, AuthConfigEntity $cfg): SessionEntity {
        $oldSession = $this->sessionRepo->fetchFirstByKeyValue('refreshToken', $refreshToken);
        if (!$oldSession) {
            throw new AuthException("session was deleted", AuthException::UI_TOKEN);
        } else if (time() > $oldSession->deleteIn + $oldSession->updatedAt) {
            throw new AuthException("session was deleted (deleteIn)", AuthException::UI_TOKEN);
        }
        $new_authToken = $this->crypto->generateToken();
        $new_refreshToken = $this->crypto->generateToken();
        $this->sessionLifeCycle->willRefreshSession($cfg, $oldSession);
        $updateData = [
            'version' => $cfg->version ?? $oldSession->version,
            'platform' => $cfg->platform ?? $oldSession->platform,
            'pushToken' => $cfg->pushToken ?? $oldSession->pushToken,
            'authToken' => $new_authToken,
            'refreshToken' => $new_refreshToken,
            'expiresIn' => self::validateExpiresIn($cfg->expiresIn),
            'deleteIn' => self::validateDeleteIn($cfg->deleteIn),
        ];
        $this->sessionRepo->updateRowsByKeyValue(
            'refreshToken',
            $refreshToken,
            $updateData
        );
        $oldSession->setState($updateData);
        $oldSession->scToken = $this->createSelfContainedToken(
            $oldSession
        );
        $this->sessionLifeCycle->didRefreshSession($cfg, $oldSession);
        return $oldSession;

    }

    public function createSelfContainedToken(
        SessionEntity $session
    ): string {
        $data = (array)$session;
        $data['publicKey'] = $this->crypto->getPublicKey();
        return $this->crypto->encrypt(json_encode($data));
    }

    public function parseSelfContainedToken(
        string $s
    ): ?array {
        $s = $this->crypto->decrypt($s);
        if (!$s) return null;
        $data = \json_decode($s, true);
        $key = $data['publicKey'] ?? null;
        if ($this->crypto->getPublicKey() !== $key) {
            return null;
        }
        return $data;
    }


    /**
     * @param AuthRequestEntity $authRequestEntity
     * @param ProfileEntity $ProfileEntity
     * @param SessionEntity $sessionEntity
     * @throws \Exception
     */
    public function createSession(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        SessionEntity &$sessionEntity
    ): void {


        $sessionEntity->setState(
            array_merge(
                (array)$authRequestEntity,
                (array)$ProfileEntity,
                [
                    'isNewUser' => time() - $ProfileEntity->createdAt < 20,
                    'id' => null,
                    'email' => $ProfileEntity->getEmail(),
                    'profileId' => $ProfileEntity->id,
                    'authToken' => $this->crypto->generateToken(),
                    'refreshToken' => $this->crypto->generateToken(),
                    'expiresIn' => self::validateExpiresIn($authRequestEntity->expiresIn),
                    'deleteIn' => self::validateDeleteIn($authRequestEntity->deleteIn),
                    'updatedAt' => time(),
                    'createdAt' => time(),
                ]
            )

        );
        $this->sessionLifeCycle->willInsertSession($authRequestEntity, $ProfileEntity, $sessionEntity);
        $this->sessionRepo->insertSession(
            $sessionEntity
        );
        $this->sessionLifeCycle->didInsertSession($authRequestEntity, $ProfileEntity, $sessionEntity);


        if (!$sessionEntity->id) {
            throw new \Exception("can't create session");
        }
        $sessionEntity->scToken = $this->createSelfContainedToken(
            $sessionEntity
        );


    }
}