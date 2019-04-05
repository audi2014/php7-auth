<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/22/19
 * Time: 1:23 PM
 */

namespace Audi2014\Auth;


use Psr\Container\ContainerInterface;

abstract class abstractDefaultKeysDI extends abstractDI {

    protected static function buildSessionRepo(
        ContainerInterface $container, \PDO $pdo
    ): Session\SessionRepo {
        return new Session\SessionRepo($pdo);
    }

    public static function key_LifeCycle_Session(): string {
        return 'key_LifeCycle_Session';
    }

    public static function key_Scripts_LoginAny(): string {
        return 'key_Scripts_LoginAny';
    }

    public static function key_Scripts_VerifyEmail(): string {
        return 'key_Scripts_VerifyEmail';
    }

    public static function key_Scripts_RegisterAny(): string {
        return 'key_Scripts_RegisterAny';
    }

    public static function key_Scripts_LoginOrRegister(): string {
        return 'key_Scripts_LoginOrRegister';
    }

    public static function key_Repo_Profile(): string {
        return 'key_Repo_Profile';
    }

    public static function key_Repo_Fb(): string {
        return 'key_Repo_Fb';
    }

    public static function key_Repo_Email(): string {
        return 'key_Repo_Email';
    }

    public static function key_Repo_Session(): string {
        return 'key_Repo_Session';
    }

    public static function key_Manager_Session(): string {
        return 'key_Manager_Session';
    }

    public static function key_Container_Login(): string {
        return 'key_Container_Login';
    }

    public static function key_Container_Register(): string {
        return 'key_Container_Register';
    }

    public static function key_LifeCycle_Register(): string {
        return 'key_LifeCycle_Register';
    }

    public static function key_FbApi(): string {
        return 'key_FbApi';
    }

    public static function key_Scripts_ChangePassword(): string {
        return 'key_Scripts_ChangePassword';
    }
}