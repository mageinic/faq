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

use MageINIC\Faq\Api\Data\CategoryInterface;
use MageINIC\Faq\Helper\Data;
use MageINIC\Faq\Model\ResourceModel\Faq\Collection;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\Faq\Model\Category;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory;
use MageINIC\Faq\Api\CategoryRepositoryInterface;
use MageINIC\Faq\Block\Faq\View as FaqView;

/**
 * Faq Class View
 */
class View extends Template
{
    /**
     * @var CollectionFactory
     */
    public CollectionFactory $collectionFactory;

    /**
     * @var Category
     */
    protected Category $category;

    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var FaqView
     */
    private FaqView $faqview;

    /**
     * @var Data
     */
    private Data $helper;

    /**
     * View constructor.
     *
     * @param Template\Context $context
     * @param Category $category
     * @param CollectionFactory $collectionFactory
     * @param ResourceConnection $resourceConnection
     * @param StoreManagerInterface $storeManager
     * @param ManagerInterface $messageManager
     * @param FaqView $faqview
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context            $context,
        Category                    $category,
        CollectionFactory           $collectionFactory,
        ResourceConnection          $resourceConnection,
        StoreManagerInterface       $storeManager,
        ManagerInterface            $messageManager,
        FaqView                     $faqview,
        CategoryRepositoryInterface $categoryRepository,
        Data                        $helper,
        array                       $data = []
    ) {
        $this->category = $category;
        $this->collectionFactory = $collectionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->categoryRepository = $categoryRepository;
        $this->faqview = $faqview;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Prepare Layout
     *
     * @return View
     * @throws LocalizedException
     */
    protected function _prepareLayout(): View
    {
        $this->pageConfig->getTitle()->set($this->getCurrentCategory()->getCategoryName());
        $this->pageConfig->setKeywords($this->getCurrentCategory()->getMetaKeywords());
        $this->pageConfig->setDescription($this->getCurrentCategory()->getMetaDescription());

        return parent::_prepareLayout();
    }

    /**
     * Get Current Category
     *
     * @return CategoryInterface
     * @throws LocalizedException
     */
    public function getCurrentCategory(): CategoryInterface
    {
        $category_id = $this->getRequest()->getParam('id');
        $category = $this->categoryRepository->getById($category_id);
        return $category;
    }

    /**
     * Get Category Faq Question
     *
     * @return Collection
     */
    public function getCategoryFaqQuestion(): Collection
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $collection = $this->collectionFactory->create();
            $collection->addFieldToSelect('*');
            $collection->addFieldToFilter('category_id', ['eq' => $id]);
            $collection->addFieldToFilter('status', ['eq' => 1]);
            $isLoggedIn = (bool)$this->faqview->getCurrentCustomer();
            if (!$isLoggedIn) {
                $collection->addFieldToFilter('visibility', ['neq' => '1']);
            }
            $second_table_name = $this->resourceConnection->getTableName('mageinic_faq_store');
            $collection->getSelect()->joinLeft(
                ['second' => $second_table_name],
                'main_table.faq_id = second.faq_id'
            );
            $collection->addStoreFilter($this->storeManager->getStore()->getId());
            return $collection;
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
    }

    /**
     * Enable Extension
     *
     * @return mixed
     */
    public function isEnableExtension(): mixed
    {
        return $this->helper->getFaqEnable();
    }
}
