<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 14:52
 */

namespace Audi2014\Auth\Session;

use Audi2014\Repo\AbstractRepo;

class SessionRepo extends AbstractRepo {
    public function getTable(): string {
        return '__auth__session';
    }

    public function getFields(): array {
        return [
            '__auth__session.*',
            'ae.isEmailVerified as isEmailVerified',
            'ae.passwordHash as passwordHash',
            'ae.email as emailEmail',
            'afb.email as fbEmail',
        ];
    }

    public function getJoins(): array {
        return [
            'left join __auth__credential_email ae on ae.profileId = __auth__session.profileId',
            'left join __auth__credential_fb afb on afb.profileId = __auth__session.profileId',
        ];
    }

    public function getGroupBy(): ?string {
        return '__auth__session.profileId';
    }

    public function getOnDuplicateKeySql(): string {
        return '';
    }


    /**
     * @param SessionEntity $data
     * @return void
     * @throws \Exception
     */
    public function insertSession(SessionEntity &$data): void {
        $data->createdAt = time();
        $data->updatedAt = time();
        $data->id = parent::insertRow(
            new SessionEntityInsert((array)$data)
        );
    }

    public function fetchFirstByKeyValue(string $key, $value): ?SessionEntity {
        return parent::fetchFirstByKeyValue($key, $value);
    }

    function willInsertData(array $data): array {

        $data['updatedAt'] = time();
        $data['createdAt'] = time();
        return $data;
    }

    function willUpdateData(array $data): array {
        unset($data['email']);
        unset($data['isEmailVerified']);
        unset($data['scToken']);
        unset($data['isNewUser']);

        $data['updatedAt'] = time();
        return $data;
    }

    public function getEntityClass(): string {
        return SessionEntity::class;
    }
}