<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/22/19
 * Time: 4:17 PM
 */

namespace Audi2014\Auth\Profile;


use Audi2014\RequestQuery\AbstractRequestQueryPage;

class ProfileQuery extends AbstractRequestQueryPage {

    protected $or = false;

    /**
     * @param bool $or
     * @return ProfileQuery
     */
    public function setOrForConditions(bool $or): ProfileQuery {
        $this->or = $or;
        return $this;
    }

    protected function getConditionGroup(string $key,
                                         string &$groupId,
                                         bool &$orForConditions,
                                         bool &$orForGroup,
                                         bool &$isHaving,
                                         bool &$orNullConditionMode): void {
        $orForConditions = $this->or;
    }

    protected function getNullKeys(): array {
        return [];
    }

    protected function getEqKeys(): array {
        return [
            'emailEmail' => 'ae.email',
            'fbEmail' => 'afb.email',
        ];
    }

    protected function getInKeys(): array {
        return [];
    }

    protected function getGthEqKeys(): array {
        return [];
    }

    protected function getGthKeys(): array {
        return [];
    }

    protected function getLthKeys(): array {
        return [];
    }

    protected function getLthEqKeys(): array {
        return [];
    }

    protected function getLikeKeys(): array {
        return [];
    }
}