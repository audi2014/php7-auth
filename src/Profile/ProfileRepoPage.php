<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 11:10
 */

namespace Audi2014\Auth\Profile;

use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\RepoRequestQuery\AbstractRepoRequestQuery;

class ProfileRepoPage extends AbstractRepoRequestQuery implements ProfileRepoInterface {
    public function getTable(): string {
        return '__auth__profile';
    }

    public function getFields(): array {
        return [
            '__auth__profile.*',
            'ae.isEmailVerified as isEmailVerified',
            'ae.passwordHash as passwordHash',
            'ae.email as emailEmail',
            'afb.email as fbEmail',
        ];
    }

    public function getJoins(): array {
        return [
            'left join __auth__credential_email ae on ae.profileId = __auth__profile.id',
            'left join __auth__credential_fb afb on afb.profileId = __auth__profile.id',
        ];
    }

    public function getGroupBy(): ?string {
        return '__auth__profile.id';
    }

    public function getOnDuplicateKeySql(): string {
        return '';
    }


    /**
     * @param ProfileEntity $data
     * @return void
     * @throws \Exception
     */
    public function insertProfile(ProfileEntity &$data): void {
        $data->createdAt = time();
        $data->updatedAt = time();
        $data->id = parent::insertRow(
            new ProfileEntityInsert((array)$data)
        );
    }

    function willInsertData(array $data): array {
        unset($data['email']);
        unset($data['isEmailVerified']);
        unset($data['passwordHash']);

        $data['updatedAt'] = time();
        $data['createdAt'] = time();
        return $data;
    }

    function willUpdateData(array $data): array {
        unset($data['email']);
        unset($data['isEmailVerified']);
        unset($data['passwordHash']);

        $data['updatedAt'] = time();
        return $data;
    }

    public function mapKeySelector(string $whereKey): string {
        switch ($whereKey) {
            case 'fbEmail':
                return 'afb.email';
            case 'emailEmail':
                return 'ae.email';
            default :
                return $whereKey;
        }
    }

    /**
     * @param string $email
     * @param string $type
     * @return ProfileEntity
     * @throws \Exception
     */
    public function fetchFirstEmailAndType(string $email, string $type): ?ProfileEntity {
        if ($type === AuthRequestEntity::TYPE_FB) {
            return parent::fetchFirstByKeyValue('fbEmail', $email);
        }
        if ($type === AuthRequestEntity::TYPE_EMAIL) {
            return parent::fetchFirstByKeyValue('emailEmail', $email);
        }
        throw  new \Exception("bad profile authtype");
    }

    /**
     * @param string $email
     * @return ProfileEntity|null
     * @throws \Audi2014\RequestQuery\QueryException
     */
    public function fetchFirstByEmailAndAnyType(string $email): ?ProfileEntity {
        $q = new ProfileQuery();
        $q->setOrForConditions(true);
        $q->initFromArray([
            'fbEmail' => $email,
            'emailEmail' => $email,
        ]);
        $q->setOffset(0);
        $q->setCount(1);
        $q->build();
        $array = parent::fetchQueryPageItems($q);
        if (empty($array)) return null;
        else return reset($array);
    }


    protected function getEntityClass(): string {
        return ProfileEntity::class;
    }
}