<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 3/18/19
 * Time: 10:18 AM
 */

namespace Audi2014\Auth\Scripts;


use Audi2014\Auth\AuthException;
use Audi2014\Auth\ChangePassword\ChangePasswordEntity;
use Audi2014\Auth\Credential\CredentialEmailRepo;
use Audi2014\Crypto\CryptoInterface;

class ChangePassword {

//    private $schema;
//    private $entityClass;
    private $repo;
    private $crypto;

    public function __construct(
        CredentialEmailRepo $credentialEmailRepo,
        CryptoInterface $crypto
//        ChangePasswordSchema $schema,
//        string $entityClass
    ) {
        $this->repo = $credentialEmailRepo;
        $this->crypto = $crypto;
//        $this->schema = $schema;
//        $this->entityClass = $entityClass;
    }

    /**
     * @param int $profileId
     * @param ChangePasswordEntity $entity
     * @throws \Exception
     */
    public function changePasswordByProfileId(int $profileId, ChangePasswordEntity $entity): void {

//        $entity = new $this->entityClass($this->schema->map($data));


        $cred = $this->repo->fetchFirstByKeyValue('profileId', $profileId);
        if (!$cred || !$cred->email) {
            throw new \Exception("this account does not have email");
        } else if (!$this->crypto->validatePassword($entity->oldPassword, $cred->passwordHash)) {
            throw new AuthException("wrong password", AuthException::UI_WRONG_PASSWORD);
        } else {
            $this->repo->setNewPasswordHashByEmail($cred->email, $this->crypto->createHash($entity->newPassword));
            return;
        }

    }
}