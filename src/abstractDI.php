<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/22/19
 * Time: 1:23 PM
 */

namespace Audi2014\Auth;


use Audi2014\Auth\Session\SessionLifeCycleInterface;
use Psr\Container\ContainerInterface;
use \Audi2014\Auth\Scripts\RegisterStrategy\RegisterLifeCycleInterface;
use \Audi2014\Crypto\CryptoInterface;
use \Audi2014\SimpleFbApi\ApiInterface;

abstract class abstractDI {
    abstract public static function key_Scripts_ChangePassword(): string;

    abstract public static function key_Scripts_LoginAny(): string;

    abstract public static function key_Scripts_RegisterAny(): string;

    abstract public static function key_Scripts_LoginOrRegister(): string;

    abstract public static function key_Repo_Profile(): string;

    abstract public static function key_Repo_Fb(): string;

    abstract public static function key_Repo_Email(): string;

    abstract public static function key_Repo_Session(): string;

    abstract public static function key_Manager_Session(): string;

    abstract public static function key_Container_Login(): string;

    abstract public static function key_Container_Register(): string;

    abstract public static function key_LifeCycle_Register(): string;

    abstract public static function key_LifeCycle_Session(): string;

    abstract public static function key_FbApi(): string;

    abstract public static function getFbApi(ContainerInterface $container): ApiInterface;

    abstract public static function getRegisterLifeCycle(ContainerInterface $container): RegisterLifeCycleInterface;

    abstract public static function getSessionLifeCycle(ContainerInterface $container): SessionLifeCycleInterface;

    abstract public static function getCrypto(ContainerInterface $container): CryptoInterface;

    abstract public static function getPdo(ContainerInterface $container, string $repoClass): \PDO;

    public static function register(
        ContainerInterface $container
    ) {

        $container[static::key_Repo_Profile()] = function ($container) {
            return static::buildProfileRepo($container, static::getPdo($container, static::key_Repo_Profile()));
        };
        $container[static::key_Repo_Session()] = function ($container) {
            return static::buildSessionRepo($container, static::getPdo($container, static::key_Repo_Session()));
        };
        $container[static::key_Repo_Email()] = function ($container) {
            return static::buildCredentialEmailRepo($container, static::getPdo($container, static::key_Repo_Email()));
        };
        $container[static::key_Repo_Fb()] = function ($container) {
            return static::buildCredentialFbRepo($container, static::getPdo($container, static::key_Repo_Fb()));
        };

        $container[static::key_Manager_Session()] = function ($container) {
            return new Session\SessionManager(
                static::getCrypto($container),
                $container[static::key_Repo_Session()],
                $container[static::key_LifeCycle_Session()]
            );
        };

        $container[static::key_LifeCycle_Register()] = function ($container) {
            return static::getRegisterLifeCycle($container);
        };
        $container[static::key_LifeCycle_Session()] = function ($container) {
            return static::getSessionLifeCycle($container);
        };

        $container[static::key_Container_Login()] = function ($container) {
            return new Scripts\LoginStrategy\LoginContainer([
                new Scripts\LoginStrategy\Email(
                    $container[static::key_Repo_Profile()],
                    static::getCrypto($container)
                ),
                new Scripts\LoginStrategy\Facebook(
                    $container[static::key_Repo_Profile()],
                    static::getFbApi($container)
                )
            ]);
        };

        $container[static::key_Container_Register()] = function ($container) {
            return new Scripts\RegisterStrategy\RegisterContainer([

                new Scripts\RegisterStrategy\Email(
                    $container[static::key_Repo_Email()],
                    $container[static::key_Repo_Profile()],
                    $container[static::key_LifeCycle_Register()],
                    static::getCrypto($container)
                ),
                new Scripts\RegisterStrategy\Facebook(
                    $container[static::key_Repo_Fb()],
                    $container[static::key_Repo_Profile()],
                    $container[static::key_LifeCycle_Register()],
                    static::getFbApi($container)
                )
            ]);
        };


        $container[static::key_Scripts_RegisterAny()] = function ($container) {
            return new Scripts\RegisterAny(
                $container[static::key_Container_Register()]
            );
        };
        $container[static::key_Scripts_LoginAny()] = function ($container) {
            return new Scripts\LoginAny(
                $container[static::key_Container_Login()]
            );
        };


        $container[static::key_Scripts_LoginOrRegister()] = function ($container) {
            return new Scripts\LoginOrRegister(
                $container[static::key_Container_Login()],
                $container[static::key_Container_Register()]
            );
        };


        $container[static::key_Scripts_ChangePassword()] = function ($container) {
            return new Scripts\ChangePassword(
                $container[static::key_Repo_Email()],
                static::getCrypto($container)
            );
        };
    }


    protected static function buildProfileRepo(
        ContainerInterface $container, \PDO $pdo
    ): Profile\ProfileRepoPage {
        return new Profile\ProfileRepoPage($pdo);
    }

    protected static function buildSessionRepo(
        ContainerInterface $container, \PDO $pdo
    ): Session\SessionRepo {
        return new Session\SessionRepo($pdo);
    }

    protected static function buildCredentialEmailRepo(
        ContainerInterface $container, \PDO $pdo
    ): Credential\CredentialEmailRepo {
        return new Credential\CredentialEmailRepo($pdo);
    }

    protected static function buildCredentialFbRepo(
        ContainerInterface $container, \PDO $pdo
    ): Credential\CredentialFbRepo {
        return new Credential\CredentialFbRepo($pdo);
    }


}