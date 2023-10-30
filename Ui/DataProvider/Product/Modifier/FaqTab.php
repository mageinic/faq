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

namespace MageINIC\Faq\Ui\DataProvider\Product\Modifier;

use Magento\Framework\Phrase;
use Magento\Ui\Component\Modal;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\Faq\Helper\Data as dataHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use MageINIC\Faq\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory as productFaqCollectionFactory;

/**
 * Class FaqTab
 */
class FaqTab extends AbstractModifier
{
    public const DATA_SCOPE = '';
    public const DATA_SCOPE_FAQ = 'faq';
    public const GROUP_FAQ = 'faq';

    /**
     * @var StoreManagerInterface
     */
    public StoreManagerInterface $_storeManager;

    /**
     * @var string
     */
    private static string $previousGroup = 'search-engine-optimization';

    /**
     * @var int
     */
    private static int $sortOrder = 150;

    /**
     * @var LocatorInterface
     */
    private LocatorInterface $locator;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var ProductLinkRepositoryInterface
     */
    protected ProductLinkRepositoryInterface $productLinkRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;
    /**
     * @var AttributeSetRepositoryInterface
     */
    protected AttributeSetRepositoryInterface $attributeSetRepository;

    /**
     * @var string
     */
    private $scopeName;

    /**
     * @var productFaqCollectionFactory
     */
    private productFaqCollectionFactory $productFaqCollection;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $productFaqRelationCollection;

    /**
     * @var string
     */
    private $scopePrefix;

    /**
     * FaqTab constructor.
     *
     * @param CollectionFactory                 $productFaqRelationCollection
     * @param productFaqCollectionFactory       $productFaqCollection
     * @param dataHelper                        $helper
     * @param UrlInterface                      $urlBuilder
     * @param LocatorInterface                  $locator
     * @param ProductLinkRepositoryInterface    $productLinkRepository
     * @param ProductRepositoryInterface        $productRepository
     * @param StoreManagerInterface             $_storeManager
     * @param AttributeSetRepositoryInterface   $attributeSetRepository
     * @param string                            $scopeName
     * @param string                            $scopePrefix
     */
    public function __construct(
        CollectionFactory               $productFaqRelationCollection,
        productFaqCollectionFactory     $productFaqCollection,
        dataHelper                      $helper,
        UrlInterface                    $urlBuilder,
        LocatorInterface                $locator,
        ProductLinkRepositoryInterface  $productLinkRepository,
        ProductRepositoryInterface      $productRepository,
        StoreManagerInterface           $_storeManager,
        AttributeSetRepositoryInterface $attributeSetRepository,
                                        $scopeName = '',
                                        $scopePrefix = ''
    ) {
        $this->productFaqRelationCollection = $productFaqRelationCollection;
        $this->productFaqCollection         = $productFaqCollection;
        $this->helper                       = $helper;
        $this->locator                      = $locator;
        $this->urlBuilder                   = $urlBuilder;
        $this->_storeManager                = $_storeManager;
        $this->productLinkRepository        = $productLinkRepository;
        $this->productRepository            = $productRepository;
        $this->attributeSetRepository       = $attributeSetRepository;
        $this->scopeName                    = $scopeName;
        $this->scopePrefix                  = $scopePrefix;
    }

    /**
     * @inheritdoc
     *
     * @since 101.0.0
     */
    public function modifyMeta(array $meta): array
    {
        $meta = array_replace_recursive(
            $meta,
            [
                static::GROUP_FAQ => [
                    'children' => [
                        $this->scopePrefix . static::DATA_SCOPE_FAQ => $this->getProductFaqFieldset(),
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('FAQs'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'sortOrder' =>
                                    $this->getNextGroupSortOrder(
                                        $meta,
                                        self::$previousGroup,
                                        self::$sortOrder
                                    ),
                            ],
                        ],

                    ],
                ],
            ]
        );

        return $meta;
    }

    /**
     * @return array
     */
    public function getProductFaqFieldset(): array
    {
        $content = __('Product FAQs Questions used to provide product related documents.');

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add FAQs Questions'),
                    $this->scopePrefix . static::DATA_SCOPE_FAQ
                ),
                'modal' => $this->getGenericModal(
                    __('Add FAQs Questions'),
                    $this->scopePrefix . static::DATA_SCOPE_FAQ
                ),
                static::DATA_SCOPE_FAQ => $this
                    ->getGrid($this->scopePrefix . static::DATA_SCOPE_FAQ),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('FAQs Questions'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 10,
                    ],
                ],
            ]
        ];
    }

    /**
     * Retrieve button set
     *
     * @param Phrase $content
     * @param Phrase $buttonTitle
     * @param string $scope
     * @return array
     * @since 101.0.0
     */
    protected function getButtonSet(Phrase $content, Phrase $buttonTitle, string $scope): array
    {
        $modalTarget = $this->scopeName . '.' . static::GROUP_FAQ . '.' . $scope . '.modal';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content' => $content,
                        'template' => 'ui/form/components/complex',
                    ],
                ],
            ],
            'children' => [
                'button_' . $scope => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' => $modalTarget,
                                        'actionName' => 'toggleModal',
                                    ],
                                    [
                                        'targetName' => $modalTarget . '.' . $scope . '_faq_listing',
                                        'actionName' => 'render',
                                    ]
                                ],
                                'title' => $buttonTitle,
                                'provider' => null,
                            ],
                        ],
                    ],

                ],
            ],
        ];
    }

    /**
     *
     * Prepares config for modal slide-out panel
     *
     * @param Phrase $title
     * @param string $scope
     * @return array
     * @since 101.0.0
     */
    public function getGenericModal(Phrase $title, string $scope)
    {
        $listingTarget = $scope . '_faq_listing';

        $modal = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'options' => [
                            'title' => $title,
                            'buttons' => [
                                [
                                    'text' => __('Cancel'),
                                    'actions' => [
                                        'closeModal'
                                    ]
                                ],
                                [
                                    'text' => __('Add Selected FAQs Questions'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = ' . 'faq_faq_listing',
                                            'actionName' => 'save'
                                        ],
                                        'closeModal'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                $listingTarget => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'insertListing',
                                'dataScope' => 'faq_faq_listing',
                                'externalProvider' => $listingTarget . '.' . $listingTarget . '_data_source',
                                'selectionsProvider' => $listingTarget . '.' .$listingTarget. '.faq_faq_columns.ids',                                'ns' => 'faq_faq_listing',
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'dataLinks' => [
                                    'imports' => false,
                                    'exports' => true
                                ],
                                'behaviourType' => 'simple',
                                'externalFilterMode' => true,
                                'imports' => [
                                    'productId' => '${ $.provider }:data.product.faq_id',
                                    'storeId' => '${ $.provider }:data.product.current_store_id',
                                    '__disableTmpl' => ['productId' => false, 'storeId' => false],
                                ],
                                'exports' => [
                                    'productId' => '${ $.externalProvider }:params.faq_id',
                                    'storeId' => '${ $.externalProvider }:params.current_store_id',
                                    '__disableTmpl' => ['productId' => false, 'storeId' => false],
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $modal;
    }

    /**
     * Retrieve grid
     *
     * @param string $scope
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @since 101.0.0
     */
    protected function getGrid(string $scope): array
    {
        $dataProvider = $scope . '_faq_listing';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__field-wide',
                        'componentType' => DynamicRows::NAME,
                        'label' => null,
                        'columnsHeader' => false,
                        'columnsHeaderAfterRender' => true,
                        'renderDefaultRecord' => false,
                        'template' => 'ui/dynamic-rows/templates/grid',
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
                        'addButton' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => 'data.links',
                        'deleteButtonLabel' => __('Remove'),
                        'dataProvider' => $dataProvider,
                        'map' => [
                            'id' => 'faq_id',
                            'title' => 'title',
                            'answer' => 'answer',
                            'status' => 'status',
                        ],
                        'links' => [
                            'insertData' => '${ $.provider }:${ $.dataProvider }',
                            '__disableTmpl' => ['insertData' => false],
                        ],
                        'sortOrder' => 2,
                    ]
                ]
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'container',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => $this->fillMeta(),
                ]
            ]
        ];
    }

    /**
     * Retrieve meta column
     *
     * @return array
     * @since 101.0.0
     */
    protected function fillMeta(): array
    {
        return [
            'id' => $this->getTextColumn('id', false, __('ID'), 0),
            'title' => $this->getTextColumn('title', false, __('Title'), 3),
            'answer' => $this->getTextColumn('answer', false, __('Answer'), 2),
            'status' => $this->getTextColumn('status', false, __('status'), 1),
            'actionDelete' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'additionalClasses' => 'data-grid-actions-cell',
                            'componentType' => 'actionDelete',
                            'dataType' => Text::NAME,
                            'label' => __('Actions'),
                            'sortOrder' => 90,
                            'fit' => true,
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Retrieve text column structure
     *
     * @param string $dataScope
     * @param bool $fit
     * @param Phrase $label
     * @param int $sortOrder
     * @return array
     * @since 101.0.0
     */
    protected function getTextColumn(string $dataScope, bool $fit, Phrase $label, int $sortOrder): array
    {
        $column = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/text',
                        'component' => 'Magento_Ui/js/form/element/text',
                        'dataType' => Text::NAME,
                        'dataScope' => $dataScope,
                        'fit' => $fit,
                        'label' => $label,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];

        return $column;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }
        foreach ($this->getDataScopes() as $dataScope) {
            $data[$productId]['links'][$dataScope] = [];
            $productAttachmentRelationCollection = $this->productFaqRelationCollection->create()
                ->addFieldToFilter('product_id', ['eq' => $productId]);

            foreach ($productAttachmentRelationCollection as $prodColl) {
                $data[$productId]['links'][$dataScope][] = $this->fillData($prodColl['faq_id'], $productId);
            }
        }
        return $data;
    }

    /**
     * @param $faqIds
     * @return array
     */
    public function fillData($faqIds)
    {
        $productAttachmentCollection = $this->productFaqCollection->create()
            ->addFieldToFilter('faq_id', ['eq' => $faqIds])
            ->getFirstItem();

        return [
            'id' => $productAttachmentCollection->getFaqId(),
            'answer' => strip_tags($productAttachmentCollection->getAnswer()),
            'title' => $productAttachmentCollection->getTitle(),
            'status' => $productAttachmentCollection->getStatus(),
        ];
    }

    /**
     * Retrieve all data scopes
     *
     * @return array
     */
    public function getDataScopes()
    {
        return [
            static::DATA_SCOPE_FAQ,
        ];
    }
}
