<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 11:10
 */

namespace Audi2014\Auth\Credential;

use Audi2014\Repo\AbstractRepo;

class CredentialEmailRepo extends AbstractRepo implements CredentialEmailRepoInterface {

    public function getTable(): string {
        return '__auth__credential_email';
    }

    public function getFields(): array {
        return [
            '__auth__credential_email.*',
        ];
    }

    public function getJoins(): array {
        return [];
    }

    public function getGroupBy(): ?string {
        return '__auth__credential_email.profileId';
    }

    public function getOnDuplicateKeySql(): string {
        return '';
    }


    /**
     * @param $key
     * @param $value
     * @return CredentialEmailEntity
     */
    public function fetchFirstByKeyValue(string $key, $value): ?CredentialEmailEntity {
        return parent::fetchFirstByKeyValue($key, $value);
    }

    /**
     * @param CredentialEmailEntity $data
     * @return void
     * @throws \Exception
     */
    public function insertCredential(CredentialEmailEntity &$data): void {
        $data->createdAt = time();
        $data->updatedAt = time();
        $data->id = parent::insertRow($data);
    }


    function willInsertData(array $data): array {
        $data['updatedAt'] = time();
        $data['createdAt'] = time();
        return $data;
    }

    function willUpdateData(array $data): array {
        $data['updatedAt'] = time();
        return $data;
    }


    /**
     * @param string $email
     * @return CredentialEmailEntity|null
     */
    public function fetchFirstByEmail(string $email): ?CredentialEmailEntity {
        return $this->fetchFirstByKeyValue('email', $email);
    }

    /**
     * @param string $email
     * @param bool $value
     * @return int
     */
    public function setEmailVerifiedByEmail(string $email, bool $value = true): int {
        return $this->updateRowsByKeyValue('email', $email, [
            'isEmailVerified' => (int)$value,
        ]);
    }

    /**
     * @param string $email
     * @param string $hash
     * @return int
     */
    public function setNewPasswordHashByEmail(string $email, string $hash): int {
        return $this->updateRowsByKeyValue('email', $email, [
            'passwordHash' => $hash,
        ]);
    }

    protected function getEntityClass(): string {
        return CredentialEmailEntity::class;
    }
}