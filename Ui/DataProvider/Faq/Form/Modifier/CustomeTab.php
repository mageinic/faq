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

use Magento\Ui\Component\Modal;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\MultiSelect;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions;

/**
 * Faq Class CustomeTab
 */
class CustomeTab extends AbstractModifier
{
    public const CUSTOM_MODAL_LINK = 'custom_modal_link';
    public const CUSTOM_MODAL_INDEX = 'custom_modal';
    public const CUSTOM_MODAL_CONTENT = 'content';
    public const CUSTOM_MODAL_FIELDSET = 'fieldset';
    public const CONTAINER_HEADER_NAME = 'header';
    public const FIELD_NAME_1 = 'field1';
    public const FIELD_NAME_2 = 'field2';
    public const FIELD_NAME_3 = 'field3';

    /**
     * @var LocatorInterface
     */
    protected LocatorInterface $locator;

    /**
     * @var ArrayManager
     */
    protected ArrayManager $arrayManager;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * CustomeTab constructor.
     *
     * @param LocatorInterface  $locator
     * @param ArrayManager      $arrayManager
     * @param UrlInterface      $urlBuilder
     */
    public function __construct(
        LocatorInterface    $locator,
        ArrayManager        $arrayManager,
        UrlInterface        $urlBuilder
    ) {
        $this->locator      = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder   = $urlBuilder;
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

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
        $this->meta = $meta;
        $this->addCustomModal();

        return $this->meta;
    }

    /**
     * Add Custom Modal
     *
     * @return void
     */
    protected function addCustomModal(): void
    {
        $this->meta = array_merge_recursive($this->meta, [static::CUSTOM_MODAL_INDEX => $this->getModalConfig()]);
    }

    /**
     * Get Modal Config
     *
     * @return array
     */
    protected function getModalConfig(): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.faq_faq_data_source',
                        'ns' => static::FORM_NAME,
                        'options' => [
                            'title' => __('Modal Title'),
                            'buttons' => [
                                [
                                    'text' => __('Save'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = faq_faq_form',
                                            'actionName' => 'save',
                                        ],
                                        'closeModal',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                static::CUSTOM_MODAL_CONTENT => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'container',
                                'dataScope' => 'data.product',
                                'externalProvider' => 'data.faq_faq_data_source',
                                'ns' => static::FORM_NAME,
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'behaviourType' => 'edit',
                                'externalFilterMode' => true,
                                'currentProductId' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        static::CUSTOM_MODAL_FIELDSET => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label' => __('Fieldset'),
                                        'componentType' => Fieldset::NAME,
                                        'dataScope' => 'custom_data',
                                        'collapsible' => true,
                                        'sortOrder' => 10,
                                        'opened' => true,
                                    ],
                                ],
                            ],
                            'children' => [
                                static::CONTAINER_HEADER_NAME => $this->getHeaderContainerConfig(10),
                                static::FIELD_NAME_1 => $this->getFirstFieldConfig(20),
                                static::FIELD_NAME_2 => $this->getSecondFieldConfig(30),
                                static::FIELD_NAME_3 => $this->getThirdFieldConfig(40),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for header container
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getHeaderContainerConfig(int $sortOrder): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => null,
                        'formElement' => Container::NAME,
                        'componentType' => Container::NAME,
                        'template' => 'ui/form/components/complex',
                        'sortOrder' => $sortOrder,
                        'content' => __('You can write any text here'),
                    ],
                ],
            ],
            'children' => [],
        ];
    }

    /**
     * Get First Field Config
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getFirstFieldConfig(int $sortOrder): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Example Text Field'),
                        'formElement' => Field::NAME,
                        'componentType' => Input::NAME,
                        'dataScope' => static::FIELD_NAME_1,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Second Field Config
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getSecondFieldConfig($sortOrder): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Product Options Select'),
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'dataScope' => static::FIELD_NAME_2,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'options' => '',
                        'visible' => true,
                        'disabled' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Third Field Config
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getThirdFieldConfig(int $sortOrder): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Product Options Multiselect'),
                        'componentType' => Field::NAME,
                        'formElement' => MultiSelect::NAME,
                        'dataScope' => static::FIELD_NAME_3,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'options' => '',
                        'visible' => true,
                        'disabled' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get all product options as an option array:
     *
     * @param int $sortOrder
     * @return array
     */
    protected function addCustomModalLink(int $sortOrder): array
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                CustomOptions::GROUP_CUSTOM_OPTIONS_NAME => [
                    'children' => [
                        CustomOptions::CONTAINER_HEADER_NAME => [
                            'children' => [
                                static::CUSTOM_MODAL_LINK => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'title' => __('Open Custom Modal'),
                                                'formElement' => Container::NAME,
                                                'componentType' => Container::NAME,
                                                'component' => 'Magento_Ui/js/form/components/button',
                                                'actions' => [
                                                    [
                                                        'targetName' => 'ns=' . static::FORM_NAME . ', index='
                                                            . static::CUSTOM_MODAL_INDEX,
                                                        'actionName' => 'openModal',
                                                    ],
                                                ],
                                                'displayAsLink' => false,
                                                'sortOrder' => $sortOrder,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
