<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 31/07/2018
 * Time: 10:18
 */

namespace Audi2014\Auth\Scripts\LoginStrategy;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\Profile\ProfileRepoPage;
use Audi2014\Auth\AuthException;
use Audi2014\Crypto\CryptoInterface;

class Email implements LoginStrategyInterface {


    private $ProfileRepo;
    private $crypto;

    public function __construct(
        ProfileRepoPage $ProfileRepo,
        CryptoInterface $crypto
    ) {
        $this->ProfileRepo = $ProfileRepo;
        $this->crypto = $crypto;
    }

    /**
     * @param AuthRequestEntity $authRequestEntity
     * @param ProfileEntity $ProfileEntity
     * @param bool $checkEmailVerified
     * @throws AuthException
     * @throws \Exception
     */
    function auth(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        bool $checkEmailVerified = false
    ): void {

        if (!$ProfileEntity->hasType($this->getType())) {
            throw new \Exception("this account does not registered as {$this->getType()}");
        } else if (empty($ProfileEntity->passwordHash)) {
            throw new AuthException(
                "account with email `{$ProfileEntity->getEmail()}` has empty password. use reset password",
                AuthException::UI_DB_PASSWORD_EMPTY
            );
        } else if ($checkEmailVerified && !$ProfileEntity->isEmailVerified) {
            throw new AuthException("email not Verified!", AuthException::UI_LOGIN_OK_BUT_NOT_VERIFIED);
        } else if (!$this->crypto->validatePassword(
            $authRequestEntity->password,
            $ProfileEntity->passwordHash
        )) {
            throw new AuthException("wrong password!", AuthException::UI_WRONG_PASSWORD);
        }
    }

    /**
     * @param AuthRequestEntity $authRequestEntity
     * @return ProfileEntity|null
     * @throws \Exception
     */
    public function getCredential(AuthRequestEntity $authRequestEntity): ?ProfileEntity {
        return $this->ProfileRepo->fetchFirstEmailAndType($authRequestEntity->email, $authRequestEntity->type);
    }

    /**
     * @return string
     */
    public function getType(): string {
        return AuthRequestEntity::TYPE_EMAIL;
    }
}