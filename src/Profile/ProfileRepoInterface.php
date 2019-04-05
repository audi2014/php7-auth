<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/26/19
 * Time: 1:50 PM
 */

namespace Audi2014\Auth\Profile;

interface ProfileRepoInterface {
    /**
     * @param ProfileEntity $data
     * @return void
     */
    public function insertProfile(ProfileEntity &$data): void;

    /**
     * @param string $email
     * @param string $type
     * @return ProfileEntity
     * @throws \Exception
     */
    public function fetchFirstEmailAndType(string $email, string $type): ?ProfileEntity;

    /**
     * @param string $email
     * @return ProfileEntity|null
     * @throws \Audi2014\RequestQuery\QueryException
     */
    public function fetchFirstByEmailAndAnyType(string $email): ?ProfileEntity;
}