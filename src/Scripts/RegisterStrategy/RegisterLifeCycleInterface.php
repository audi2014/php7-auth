<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 1:37 PM
 */

namespace Audi2014\Auth\Scripts\RegisterStrategy;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;

interface RegisterLifeCycleInterface {


    function willInsertCredential(AuthRequestEntity $authRequestEntity,
                                  ProfileEntity &$ProfileEntity,
                                  bool $setEmailVerified = false
    ): void;

    function didInsertCredential(AuthRequestEntity $authRequestEntity,
                                 ProfileEntity &$ProfileEntity,
                                 bool $setEmailVerified = false
    ): void;



}