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

use Magento\Framework\Api\SearchCriteriaInterface;
use MageINIC\Faq\Api\Data;
use MageINIC\Faq\Api\Data\FaqInterface;
use MageINIC\Faq\Api\FaqRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use MageINIC\Faq\Model\ResourceModel\Faq as ResourceFaq;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\Faq\Model\FaqFactory as ModelFaqFactory;
use MageINIC\Faq\Api\Data\FaqSearchResultsInterfaceFactory;

/**
 * Class PageRepository
 */
class FaqRepository implements FaqRepositoryInterface
{
    /**
     * @var ResourceFaq
     */
    protected ResourceFaq $resource;

    /**
     * @var FaqFactory
     */
    protected FaqFactory $faqFactory;

    /**
     * @var FaqCollectionFactory
     */
    protected FaqCollectionFactory $faqCollectionFactory;

    /**
     * @var FaqSearchResultsInterfaceFactory
     */
    protected FaqSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected DataObjectHelper $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected DataObjectProcessor $dataObjectProcessor;

    /**
     * @var Data\FaqInterfaceFactory
     */
    protected Data\FaqInterfaceFactory $dataPageFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private ?CollectionProcessorInterface $collectionProcessor;

    /**
     * @var FaqSearchResultsInterfaceFactory
     */
    private FaqSearchResultsInterfaceFactory $faqSearchResultsInterfaceFactory;

    /**
     * @param ResourceFaq $resource
     * @param FaqFactory $faqFactory
     * @param Data\FaqInterfaceFactory $dataPageFactory
     * @param FaqCollectionFactory $faqCollectionFactory
     * @param FaqSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param FaqSearchResultsInterfaceFactory $faqSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceFaq                             $resource,
        FaqFactory                              $faqFactory,
        Data\FaqInterfaceFactory                $dataPageFactory,
        FaqCollectionFactory                    $faqCollectionFactory,
        Data\FaqSearchResultsInterfaceFactory   $searchResultsFactory,
        DataObjectHelper                        $dataObjectHelper,
        DataObjectProcessor                     $dataObjectProcessor,
        StoreManagerInterface                   $storeManager,
        FaqSearchResultsInterfaceFactory        $faqSearchResultsInterfaceFactory,
        CollectionProcessorInterface            $collectionProcessor
    ) {
        $this->resource                         = $resource;
        $this->faqFactory                       = $faqFactory;
        $this->faqCollectionFactory             = $faqCollectionFactory;
        $this->searchResultsFactory             = $searchResultsFactory;
        $this->dataObjectHelper                 = $dataObjectHelper;
        $this->dataPageFactory                  = $dataPageFactory;
        $this->dataObjectProcessor              = $dataObjectProcessor;
        $this->storeManager                     = $storeManager;
        $this->faqSearchResultsInterfaceFactory = $faqSearchResultsInterfaceFactory;
        $this->collectionProcessor              = $collectionProcessor;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $Id): FaqInterface
    {
        $faq = $this->faqFactory->create();
        $this->resource->load($faq, $Id);
        if (!$faq->getId()) {
            throw new NoSuchEntityException(__('Faq with id "%1" does not exist.', $Id));
        }
        return $faq;
    }

    /**
     * @inheritdoc
     */
    public function save(FaqInterface $faq): FaqInterface
    {
        if (empty($faq->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $faq->setStoreId($storeId);
        }
        try {
            $this->resource->save($faq);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the faq: %1', $exception->getMessage()),
                $exception
            );
        }
        return $faq;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->faqCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->faqSearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function delete(FaqInterface $faq): bool
    {
        try {
            $this->resource->delete($faq);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the faq: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id): bool
    {
        return $this->delete($this->getById($id));
    }
}
