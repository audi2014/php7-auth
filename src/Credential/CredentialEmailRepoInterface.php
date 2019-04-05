<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/26/19
 * Time: 1:36 PM
 */

namespace Audi2014\Auth\Credential;

interface CredentialEmailRepoInterface {
    /**
     * @param CredentialEmailEntity $data
     * @return void
     */
    public function insertCredential(CredentialEmailEntity &$data): void;


    /**
     * @param string $email
     * @return CredentialEmailEntity|null
     */
    public function fetchFirstByEmail(string $email): ?CredentialEmailEntity;

    /**
     * @param string $email
     * @param bool $value
     * @return int
     */
    public function setEmailVerifiedByEmail(string $email, bool $value = true): int;

    /**
     * @param string $email
     * @param string $hash
     * @return int
     */
    public function setNewPasswordHashByEmail(string $email, string $hash): int;
}