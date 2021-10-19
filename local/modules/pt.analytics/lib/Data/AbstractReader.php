<?php

declare(strict_types=1);

namespace Picktech\Analytics\Data;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Main\Web\Uri;

abstract class AbstractReader
{
    /**
     * @var string
     */
    protected $lang;

    /**
     * @param iterable $items
     * @param array $valueFields
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    final protected function findValueByLanguage(iterable $items, array $valueFields, $defaultValue = null)
    {
        $value = $defaultValue;

        $valueField = $valueFields[$this->lang];
        foreach ($items as $item) {
            if (is_array($item[$valueField])
                && ($item[$valueField]['TEXT'] !== '' || $item[$valueField]['HTML'] !== '')) {
                $value = $item[$valueField]['TEXT'] ?: $item[$valueField]['HTML'];
                break;
            }

            if (!empty($item[$valueField])) {
                $value = $item[$valueField];
                break;
            }
        }

        return $value;
    }

    final protected function createUrl(string $url): Uri
    {
        $url = str_replace('\\', '/', $url);

        while (strpos($url, '//') !== false) {
            $url = str_replace('//', '/', $url);
        }

        if (strpos($url, 'http') !== 0) {
            $url = '/' . ltrim($url, '/\\');
        }

        return new Uri($url);
    }



    final protected function createMetaInfoForIblockElement(int $iblockId, int $elementId): MetaInfoDto
    {
        $values = new ElementValues($iblockId, $elementId);
        $meta = new MetaInfoDto();
        $meta->title = $values->getValue('ELEMENT_META_TITLE');
        $meta->keywords = $values->getValue('ELEMENT_META_KEYWORDS');
        $meta->description = $values->getValue('ELEMENT_META_DESCRIPTION');
        return $meta;
    }
}
