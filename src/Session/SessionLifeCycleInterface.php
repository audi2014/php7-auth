<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 1:37 PM
 */

namespace Audi2014\Auth\Session;


use Audi2014\Auth\AuthRequest\AuthConfigEntity;
use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;

interface SessionLifeCycleInterface {


    function willInsertSession(AuthRequestEntity $authRequestEntity,
                               ProfileEntity &$ProfileEntity,
                               SessionEntity &$sessionEntity
    ): void;

    function didInsertSession(AuthRequestEntity $authRequestEntity,
                              ProfileEntity &$ProfileEntity,
                              SessionEntity &$sessionEntity
    ): void;

    function willRefreshSession(AuthConfigEntity $authConfigEntity,
                               SessionEntity &$sessionEntity
    ): void;

    function didRefreshSession(AuthConfigEntity $authConfigEntity,
                              SessionEntity &$sessionEntity
    ): void;



}