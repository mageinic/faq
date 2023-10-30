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

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Faq Class Faq
 */
class Faq extends AbstractModifier
{
    /**
     * @var StoreManagerInterface
     */
    public StoreManagerInterface $storeManager;

    /**
     * @var UrlInterface
     */
    public UrlInterface $urlBuilder;

    /**
     * Faq constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface          $urlBuilder
     * @param string                $scopeName
     * @param string                $scopePrefix
     */
    public function __construct(
        StoreManagerInterface   $storeManager,
        UrlInterface            $urlBuilder,
        $scopeName = '',
        $scopePrefix = ''
    ) {
        $this->storeManager  =   $storeManager;
        $this->urlBuilder    =   $urlBuilder;
    }

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
        $meta['test_fieldset_name'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'isTemplate' => false,
                        'componentType' => 'modal',
                        'options' => [
                            'title' => __('New Item'),
                        ],
                        'imports' => [
                            'state' => '!index=insert_item_form:responseStatus'
                        ],
                    ],
                ],
            ]
        ];

        return $meta;
    }

    /**
     * Modify Data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        return $data;
    }
}
