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

namespace MageINIC\Faq\Ui\Component\Listing\Column;

use Magento\Catalog\Helper\Image;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Faq Class ImageIcon
 */
class ImageIcon extends Column
{
    public const NAME = 'image';
    public const ALT_FIELD = 'name';

    /**
     * @var StoreManagerInterface
     */
    public StoreManagerInterface $storeManager;

    /**
     * @var Repository
     */
    public Repository $repository;

    /**
     * @var ScopeConfigInterface
     */
    public ScopeConfigInterface $scopeConfig;

    /**
     * @var Image
     */
    public Image $imageHelper;

    /**
     * @var UrlInterface
     */
    public UrlInterface $urlBuilder;

    /**
     * ImageIcon constructor.
     *
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param Repository            $repository
     * @param Image                 $imageHelper
     * @param UrlInterface          $urlBuilder
     * @param ScopeConfigInterface  $scopeConfig
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ContextInterface         $context,
        UiComponentFactory       $uiComponentFactory,
        StoreManagerInterface    $storeManager,
        Repository               $repository,
        Image                    $imageHelper,
        UrlInterface             $urlBuilder,
        ScopeConfigInterface     $scopeConfig,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager     = $storeManager;
        $this->repository       = $repository;
        $this->scopeConfig      = $scopeConfig;
        $this->imageHelper      = $imageHelper;
        $this->urlBuilder       = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $storeScope = ScopeInterface::SCOPE_STORE;
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                if ($item[$fieldName] != '') {
                    $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                        . 'MageINIC/faq/image/' . $item[$fieldName];
                } else {
                    $url = $this->imageHelper->getDefaultPlaceholderUrl('image');
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'faq/category/edit',
                    ['category_id' => $item['category_id']]
                );
                $item[$fieldName . '_orig_src'] = $url;
            }
        }
        return $dataSource;
    }

    /**
     * Get Alt
     *
     * @param mixed $row
     * @return null
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
