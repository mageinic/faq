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

namespace MageINIC\Faq\Block\Faq;

use MageINIC\Faq\Model\ResourceModel\Faq\Collection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Block\BlockInterface;
use MageINIC\Faq\Helper\Data;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory;

/**
 * Faq Class Index
 */
class Index extends Template implements BlockInterface
{
    /**
     * @var CollectionFactory
     */
    public CollectionFactory $collectionFactory;

    /**
     * @var Data
     */
    public Data $helper;

    /**
     * @var StoreManagerInterface
     */
    public StoreManagerInterface $storeManager;

    /**
     * @var View
     */
    private View $faqview;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Data $helper
     * @param StoreManagerInterface $storeManager
     * @param View $faqview
     * @param array $data
     */
    public function __construct(
        Context               $context,
        CollectionFactory     $collectionFactory,
        Data                  $helper,
        StoreManagerInterface $storeManager,
        View                  $faqview,
        array                 $data = []
    ) {
        $this->helper = $helper;
        $this->faqview = $faqview;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Get Faq Module Enable
     *
     * @return mixed
     */
    public function isExtensionEnable(): mixed
    {
        return $this->helper->getFaqEnable();
    }

    /**
     * Get Latest Faq Collection
     *
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getLatestFaqCollection(): Collection
    {
        $data = $this->collectionFactory->create();
        $data->addFieldToSelect('*');
        $data->addFieldToFilter('most_frequently', '0');
        $data->addStoreFilter($this->storeManager->getStore());
        $isLoggedIn = (bool)$this->faqview->getCurrentCustomer();
        if (!$isLoggedIn) {
            $data->addFieldToFilter('visibility', ['neq' => '1']);
        }
        return $data;
    }

    /**
     * Get Faq
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getFaq(): array
    {
        return $this->getFaqQuestion(1);
    }

    /**
     * Get Faq Question
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getFaqQuestion(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('status', '1');
        $collection->addFieldToFilter('most_frequently', '1');
        $isLoggedIn = (bool)$this->faqview->getCurrentCustomer();
        if (!$isLoggedIn) {
            $collection->addFieldToFilter('visibility', ['neq' => '1']);
        }
        $collection->addStoreFilter($this->storeManager->getStore());
        $arr = [];
        foreach ($collection as $faqCollection) {
            $arr[] = [
                'faq_id' => $faqCollection['faq_id'],
                'status' => $faqCollection['status'],
                'title' => $faqCollection['title'],
                'answer' => $faqCollection['answer'],
                'sender_name' => $faqCollection['sender_name'],
                'created_date' => $faqCollection['created_date']
            ];
        }
        return $arr;
    }

    /**
     * Get Current Faq Question
     *
     * @return Collection
     */
    public function getCurrentFaqQuestion(): Collection
    {
        $id = $this->getRequest()->getParam('faq_id');
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('faq_id', ['eq' => $id]);
        return $collection;
    }
}
