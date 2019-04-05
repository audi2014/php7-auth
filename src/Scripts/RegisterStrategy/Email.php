<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 31/07/2018
 * Time: 11:04
 */

namespace Audi2014\Auth\Scripts\RegisterStrategy;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Credential\CredentialEmailEntity;
use Audi2014\Auth\Credential\CredentialEmailRepo;
use Audi2014\Auth\Profile\ProfileRepoPage;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\AuthException;
use Audi2014\Crypto\CryptoInterface;

class Email implements RegisterStrategyInterface {

    private $CredentialEmailRepo;
    private $ProfileRepo;
    private $crypto;
    private $registerLifeCycle;

    public function __construct(
        CredentialEmailRepo $CredentialEmailRepo,
        ProfileRepoPage $ProfileRepo,
        RegisterLifeCycleInterface $registerLifeCycle,
        CryptoInterface $crypto
    ) {
        $this->CredentialEmailRepo = $CredentialEmailRepo;
        $this->ProfileRepo = $ProfileRepo;
        $this->crypto = $crypto;
        $this->registerLifeCycle = $registerLifeCycle;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return AuthRequestEntity::TYPE_EMAIL;
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

        try {
            $this->ProfileRepo->getPdo()->beginTransaction();

            $existCredential = $this->CredentialEmailRepo->fetchFirstByKeyValue('email', $authRequestEntity->email);

            if (!empty($existCredential)) {
                if ($existCredential->isEmailVerified) {
                    throw new AuthException(
                        "This email already registered",
                        AuthException::UI_EMAIL_ALREADY_REGISTERED_IS_VERIFIED
                    );
                } else {
                    throw new AuthException(
                        "This email already registered but not verified",
                        AuthException::UI_EMAIL_ALREADY_REGISTERED_NOT_VERIFIED
                    );
                }
            }

            $ProfileEntity->isEmailVerified = $checkEmailVerified;
            $ProfileEntity->emailEmail = $authRequestEntity->email;



            $existProfile = $this->ProfileRepo->fetchFirstByEmailAndAnyType($authRequestEntity->email);
            if ($existProfile) {
                $ProfileEntity->setState((array)$existProfile);
            } else {
                $this->ProfileRepo->insertProfile($ProfileEntity);
            }

            $emailCred = new CredentialEmailEntity([
                'passwordHash' => $this->crypto->createHash($authRequestEntity->password),
                'isEmailVerified' => $ProfileEntity->isEmailVerified,
                'email' => $authRequestEntity->email,
                'profileId' => $ProfileEntity->id,
            ]);

            $this->registerLifeCycle->willInsertCredential(
                $authRequestEntity,
                $ProfileEntity,
                $checkEmailVerified
            );
            $this->CredentialEmailRepo->insertCredential($emailCred);
            $this->registerLifeCycle->didInsertCredential(
                $authRequestEntity,
                $ProfileEntity,
                $checkEmailVerified
            );
            $this->ProfileRepo->getPdo()->commit();
        } catch (\Exception $e) {
            $this->ProfileRepo->getPdo()->rollBack();
            throw $e;
        }
    }
}