<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_Faq
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\Faq\Ui\DataProvider\Faq\Form\Modifier;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Faq Class AbstractModifier
 */
abstract class AbstractModifier implements ModifierInterface
{
    public const FORM_NAME = 'faq_faq_form';
    public const DATA_SOURCE_DEFAULT = 'faq';
    public const DATA_SCOPE_PRODUCT = 'data.faq';
    public const DEFAULT_GENERAL_PANEL = 'product-details';
    public const GENERAL_PANEL_ORDER = 10;
    public const CONTAINER_PREFIX = 'container_';
    public const META_CONFIG_PATH = '/arguments/data/config';

    /**
     * Retrieve next group sort order
     *
     * @param array $meta
     * @param array|string $groupCodes
     * @param int $defaultSortOrder
     * @param int $iteration
     * @return int
     * @since 101.0.0
     */
    protected function getNextGroupSortOrder(array $meta, $groupCodes, $defaultSortOrder, $iteration = 1): int
    {
        $groupCodes = (array)$groupCodes;
        foreach ($groupCodes as $groupCode) {
            if (isset($meta[$groupCode]['arguments']['data']['config']['sortOrder'])) {
                return $meta[$groupCode]['arguments']['data']['config']['sortOrder'] + $iteration;
            }
        }
        return $defaultSortOrder;
    }

    /**
     * Retrieve next attribute sort order
     *
     * @param array $meta
     * @param array|string $attributeCodes
     * @param int $defaultSortOrder
     * @param int $iteration
     * @return int
     * @since 101.0.0
     */
    protected function getNextAttributeSortOrder(array $meta, $attributeCodes, $defaultSortOrder, $iteration = 1): int
    {
        $attributeCodes = (array)$attributeCodes;
        foreach ($meta as $groupMeta) {
            $defaultSortOrder = $this->_getNextAttributeSortOrder(
                $groupMeta,
                $attributeCodes,
                $defaultSortOrder,
                $iteration
            );
        }
        return $defaultSortOrder;
    }

    /**
     * Retrieve next attribute sort order
     *
     * @param array $meta
     * @param array $attributeCodes
     * @param int $defaultSortOrder
     * @param int $iteration
     * @return mixed
     */
    private function _getNextAttributeSortOrder(array $meta, $attributeCodes, $defaultSortOrder, $iteration = 1)
    {
        if (isset($meta['children'])) {
            foreach ($meta['children'] as $attributeCode => $attributeMeta) {
                if ($this->startsWith($attributeCode, self::CONTAINER_PREFIX)) {
                    $defaultSortOrder = $this->_getNextAttributeSortOrder(
                        $attributeMeta,
                        $attributeCodes,
                        $defaultSortOrder,
                        $iteration
                    );
                } elseif (in_array($attributeCode, $attributeCodes)
                    && isset($attributeMeta['arguments']['data']['config']['sortOrder'])
                ) {
                    $defaultSortOrder = $attributeMeta['arguments']['data']['config']['sortOrder'] + $iteration;
                }
            }
        }
        return $defaultSortOrder;
    }

    /**
     * Search backwards starting from haystack length characters from the end
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     * @since 101.0.0
     */
    protected function startsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * Return name of first panel (general panel)
     *
     * @param array $meta
     * @return string
     * @since 101.0.0
     */
    protected function getGeneralPanelName(array $meta)
    {
        if (!$meta) {
            return null;
        }
        if (isset($meta[self::DEFAULT_GENERAL_PANEL])) {
            return self::DEFAULT_GENERAL_PANEL;
        }
        return $this->getFirstPanelCode($meta);
    }

    /**
     * Retrieve first panel name
     *
     * @param array $meta
     * @return string|null
     * @since 101.0.0
     */
    protected function getFirstPanelCode(array $meta): ?string
    {
        $min = null;
        $name = null;
        foreach ($meta as $fieldSetName => $fieldSetMeta) {
            if (isset($fieldSetMeta['arguments']['data']['config']['sortOrder'])
                && (null === $min || $fieldSetMeta['arguments']['data']['config']['sortOrder'] <= $min)
            ) {
                $min = $fieldSetMeta['arguments']['data']['config']['sortOrder'];
                $name = $fieldSetName;
            }
        }
        return $name;
    }

    /**
     * Get group code by field
     *
     * @param array $meta
     * @param string $field
     * @return string|bool
     * @since 101.0.0
     */
    protected function getGroupCodeByField(array $meta, $field): bool|string
    {
        foreach ($meta as $groupCode => $groupData) {
            if (isset($groupData['children'][$field])
                || isset($groupData['children'][static::CONTAINER_PREFIX . $field])
            ) {
                return $groupCode;
            }
        }

        return false;
    }

    /**
     * Format price to have only two decimals after delimiter
     *
     * @param mixed $value
     * @return string
     */
    protected function formatPrice($value): string
    {
        return $value !== null ? number_format(
            (float)$value,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            '.',
            ''
        ) : '';
    }

    /**
     * Strip excessive decimal digits from weight number
     *
     * @param mixed $value
     * @return float|string
     */
    protected function formatWeight($value): float|string
    {
        return (float)$value;
    }
}
