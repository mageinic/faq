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

namespace MageINIC\Faq\Model;

use Magento\Cms\Model\ResourceModel\Page\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\Faq\Api\CategoryRepositoryInterface;
use MageINIC\Faq\Api\Data;
use MageINIC\Faq\Api\Data\CategoryInterface;
use MageINIC\Faq\Model\ResourceModel\Category as ResourceCategory;
use MageINIC\Faq\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * Faq Class CategoryRepository
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var ResourceCategory
     */
    protected $resource;

    /**
     * @var FaqFactory
     */
    protected $categoryFactory;

    /**
     * @var CategoryCollectionFactory
     */
    protected CategoryCollectionFactory $categoryCollectionFactory;

    /**
     * @var Data\CategorySearchResultsInterfaceFactory
     */
    protected Data\CategorySearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected DataObjectHelper $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected DataObjectProcessor $dataObjectProcessor;

    /**
     * @var Data\CategoryInterfaceFactory
     */
    protected Data\CategoryInterfaceFactory $dataPageFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private ?CollectionProcessorInterface $collectionProcessor;

    /**
     * CategoryRepository constructor.
     *
     * @param ResourceCategory $resource
     * @param CategoryFactory $categoryFactory
     * @param Data\CategoryInterfaceFactory $dataPageFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Data\CategorySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        ResourceCategory                            $resource,
        CategoryFactory                             $categoryFactory,
        Data\CategoryInterfaceFactory               $dataPageFactory,
        CategoryCollectionFactory                   $categoryCollectionFactory,
        Data\CategorySearchResultsInterfaceFactory  $searchResultsFactory,
        DataObjectHelper                            $dataObjectHelper,
        DataObjectProcessor                         $dataObjectProcessor,
        StoreManagerInterface                       $storeManager,
        CollectionProcessorInterface                $collectionProcessor
    ) {
        $this->resource                         = $resource;
        $this->categoryFactory                  = $categoryFactory;
        $this->categoryCollectionFactory        = $categoryCollectionFactory;
        $this->searchResultsFactory             = $searchResultsFactory;
        $this->dataObjectHelper                 = $dataObjectHelper;
        $this->dataPageFactory                  = $dataPageFactory;
        $this->dataObjectProcessor              = $dataObjectProcessor;
        $this->storeManager                     = $storeManager;
        $this->collectionProcessor              = $collectionProcessor;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $Id): CategoryInterface
    {
        $category = $this->categoryFactory->create();
        $category->load($Id);
        if (!$category->getId()) {
            throw new NoSuchEntityException(__('The Category with the "%1" ID doesn\'t exist.', $Id));
        }
        return $category;
    }

    /**
     * @inheritdoc
     */
    public function save(CategoryInterface $category): CategoryInterface
    {
        if (empty($category->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $category->setStoreId($storeId);
        }
        try {
            $this->resource->save($category);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the page: %1', $exception->getMessage()),
                $exception
            );
        }
        return $category;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->categoryCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var Data\CategorySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function delete(CategoryInterface $category): bool
    {
        try {
            $this->resource->delete($category);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the page: %1', $exception->getMessage()));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($Id): bool
    {
        return $this->delete($this->getById($Id));
    }
}
