<?php

declare(strict_types=1);

namespace Picktech\Analytics\Data;

use Bitrix\Main\UI\PageNavigation;

final class Paginator extends PageNavigation
{
    /**
     * Setter for `allRecords` property
     * @param bool $allRecords
     * @return $this
     */
    public function allRecords(bool $allRecords = true): self
    {
        $this->allRecords = $allRecords;
        return $this;
    }
}
