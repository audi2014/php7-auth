<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 31/08/2018
 * Time: 17:22
 */

namespace Audi2014\Auth\Scripts\RegisterStrategy;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Credential\CredentialFbEntity;
use Audi2014\Auth\Credential\CredentialFbRepo;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\Profile\ProfileRepoPage;
use Audi2014\Auth\AuthException;

class Facebook implements RegisterStrategyInterface {

    private $CredentialFbRepo;
    private $ProfileRepo;
    private $api;
    private $lifeCycle;

    public function __construct(
        CredentialFbRepo $CredentialFbRepo,
        ProfileRepoPage $ProfileRepo,
        RegisterLifeCycleInterface $lifeCycle,
        \Audi2014\SimpleFbApi\ApiInterface $api
    ) {
        $this->CredentialFbRepo = $CredentialFbRepo;
        $this->ProfileRepo = $ProfileRepo;
        $this->api = $api;
        $this->lifeCycle = $lifeCycle;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return AuthRequestEntity::TYPE_FB;
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

            try {
                $OAuthUser = $this->api->getFaceBookUserFromToken($authRequestEntity->OAuthToken);
            } catch (\Exception $e) {
                throw new AuthException("wrong fb token: {$e->getMessage()}", AuthException::UI_OAUTH_TOKEN);
            }
            if (empty($OAuthUser->email)) {
                throw new AuthException("empty email in fb account", AuthException::UI_OAUTH_EMAIL_EMPTY);
            }

            $existCredential = $this->CredentialFbRepo->fetchFirstByKeyValue('email', $OAuthUser->email);
            if ($existCredential) {
                throw new AuthException(
                    "This email already registered",
                    AuthException::UI_EMAIL_ALREADY_REGISTERED
                );
            }

            $ProfileEntity->fbEmail = $OAuthUser->email;

            $existProfile = $this->ProfileRepo->fetchFirstByEmailAndAnyType($OAuthUser->email);
            if ($existProfile) {
                $ProfileEntity->setState((array)$existProfile);
            } else {
                $this->ProfileRepo->insertProfile($ProfileEntity);
            }

            $fbCred = new CredentialFbEntity([
                'data' => json_encode($OAuthUser),
                'email' => $OAuthUser->email,
                'profileId' => $ProfileEntity->id,
            ]);

            $this->lifeCycle->willInsertCredential(
                $authRequestEntity,
                $ProfileEntity,
                $checkEmailVerified
            );

            $this->CredentialFbRepo->insertCredential($fbCred);

            $this->lifeCycle->didInsertCredential(
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