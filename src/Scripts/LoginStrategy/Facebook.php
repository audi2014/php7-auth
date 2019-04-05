<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 31/08/2018
 * Time: 16:48
 */

namespace Audi2014\Auth\Scripts\LoginStrategy;

use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;
use Audi2014\Auth\Profile\ProfileRepoPage;
use Audi2014\Auth\AuthException;

class Facebook implements LoginStrategyInterface {
    private $ProfileRepo;
    private $api;

    public function __construct(
        ProfileRepoPage $ProfileRepo,
        \Audi2014\SimpleFbApi\ApiInterface $api
    ) {
        $this->ProfileRepo = $ProfileRepo;
        $this->api = $api;
    }

    /**
     * @param AuthRequestEntity $authRequestEntity
     * @return ProfileEntity|null
     * @throws AuthException
     * @throws \Exception
     */
    public function getCredential(AuthRequestEntity $authRequestEntity): ?ProfileEntity {

        try {
            $fbUser = $this->api->getFaceBookUserFromToken($authRequestEntity->OAuthToken);
        } catch (\Exception $e) {
            throw new AuthException("wrong fb token: {$e->getMessage()}", AuthException::UI_OAUTH_TOKEN);
        }
        if (empty($fbUser->email)) {
            throw new AuthException("empty email in fb account", AuthException::UI_OAUTH_EMAIL_EMPTY);
        } else {
            $existCredentialEntity = $this->ProfileRepo->fetchFirstEmailAndType($fbUser->email, $authRequestEntity->type);
            return $existCredentialEntity;
        }

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
        if (!$ProfileEntity->hasType($this->getType())) {
            throw new \Exception("this account does not registered as {$this->getType()}");
        }

    }
}