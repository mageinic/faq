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

namespace MageINIC\Faq\Block\Category;

use MageINIC\Faq\Model\ResourceModel\Category\Collection;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Block\BlockInterface;
use MageINIC\Faq\Helper\Data;
use MageINIC\Faq\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;
use MageINIC\Faq\Model\ResourceModel\Faq\Collection as ResourceModelFaqCollection;

/**
 * Faq Class CategoryList
 */
class CategoryList extends Template implements BlockInterface
{
    /**
     * @var ResourceConnection
     */
    public ResourceConnection $resource;

    /**
     * @var StoreManagerInterface
     */
    public StoreManagerInterface $storeManager;

    /**
     * @var UrlInterface
     */
    public UrlInterface $urlInterface;

    /**
     * @var CategoryCollectionFactory
     */
    private CategoryCollectionFactory $categoryCollectionFactory;

    /**
     * @var Data
     */
    private Data $helperData;

    /**
     * @var FaqCollectionFactory
     */
    private FaqCollectionFactory $faqCollectionFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param UrlInterface $urlInterface
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Data $helperData
     * @param FaqCollectionFactory $faqCollectionFactory
     * @param ResourceConnection $resource
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context                   $context,
        UrlInterface              $urlInterface,
        CategoryCollectionFactory $categoryCollectionFactory,
        Data                      $helperData,
        FaqCollectionFactory      $faqCollectionFactory,
        ResourceConnection        $resource,
        StoreManagerInterface     $storeManager,
        array $data = []
    ) {
        $this->urlInterface              = $urlInterface;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->helperData                = $helperData;
        $this->faqCollectionFactory      = $faqCollectionFactory;
        $this->resource                  = $resource;
        $this->storeManager              = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Form Action
     *
     * @param mixed $result
     * @return string
     */
    public function formAction(mixed $result): string
    {
        return $this->urlInterface->getUrl($result, ['_secure' => true]);
    }

    /**
     * Get List Of Category
     *
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getListOfCategory(): Collection
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('status', '1');
        $collection->addStoreFilter($this->storeManager->getStore());
        return $collection;
    }

    /**
     * Get Faq Ques Count
     *
     * @param mixed $cat_id
     * @return ResourceModelFaqCollection
     */
    public function getFaqQuesCount(mixed $cat_id): ResourceModelFaqCollection
    {
        $collection = $this->faqCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('category_id', ['eq' => $cat_id]);
        $collection->addFieldToFilter('status', ['eq' => 1]);
        return $collection;
    }

    /**
     * Get Image Path
     *
     * @param string $img
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImagePath(string $img): string
    {
        return $this->helperData->getCategoryImageUrl('faq/image/' . $img);
    }

    /**
     * Enable Extension
     *
     * @return mixed
     */
    public function isEnableExtension(): mixed
    {
        return $this->helperData->getFaqEnable();
    }
}
