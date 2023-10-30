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

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use MageINIC\Faq\Helper\Data;
use MageINIC\Faq\Model\FaqFactory;
use MageINIC\Faq\Model\FaqFactory as FaqModel;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory;

/**
 * Faq Class View
 */
class View extends Template implements BlockInterface
{
    /**
     * @var SessionFactory
     */
    private SessionFactory $sessionFactory;

    /**
     * @var UrlInterface
     */
    public UrlInterface $urlInterface;

    /**
     * @var FaqFactory
     */
    public FaqModel $faqFactory;

    /**
     * @var ResourceConnection
     */
    public ResourceConnection $resource;

    /**
     * @var CollectionFactory
     */
    public CollectionFactory $collectionFactory;

    /**
     * @var Data
     */
    public Data $helper;

    /**
     * @var FaqModel
     */
    public FaqModel $faqModel;

    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * View constructor.
     *
     * @param Template\Context $context
     * @param UrlInterface $urlInterface
     * @param FaqModel $faqFactory
     * @param Registry $registry
     * @param ResourceConnection $resource
     * @param CollectionFactory $collectionFactory
     * @param Data $helper
     * @param SessionFactory $sessionFactory
     * @param FaqModel $faqModel
     * @param array $data
     */
    public function __construct(
        Template\Context    $context,
        UrlInterface        $urlInterface,
        FaqFactory          $faqFactory,
        Registry            $registry,
        ResourceConnection  $resource,
        CollectionFactory   $collectionFactory,
        Data                $helper,
        SessionFactory      $sessionFactory,
        FaqModel            $faqModel,
        array $data = []
    ) {
        $this->urlInterface      = $urlInterface;
        $this->faqFactory        = $faqFactory;
        $this->registry          = $registry;
        $this->resource          = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->helper      = $helper;
        $this->sessionFactory    = $sessionFactory;
        $this->faqModel          = $faqModel;
        parent::__construct($context, $data);
    }

    /**
     * Set Form Action
     *
     * @param mixed $url
     * @return string
     */
    public function setFormAction(mixed $url): string
    {
        return $this->urlInterface->getUrl($url);
    }

    /**
     * Get Faq Questions Display
     *
     * @return mixed
     */
    public function getFaqQuestionsDisplay(): mixed
    {
        $faqDisplay = $this->helper->getFaqQuestionsDisplay();
        return $faqDisplay;
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
     * Get Current Product
     *
     * @return mixed
     */
    public function getCurrentProduct(): mixed
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Get Faq Collection
     *
     * @param mixed $productId
     * @return array
     */
    public function getFaqCollection($productId): array
    {
        $faqCollection = $this->collectionFactory->create();
        $faqCollection->addFieldToFilter('status', '1');
        $faqCollection->addFieldToFilter('product_id', $productId);
        $isLoggedIn = (bool)$this->getCurrentCustomer();
        if (!$isLoggedIn) {
            $faqCollection->addFieldToFilter('visibility', ['neq' => '1']);
        }
        $faqCollection->setPageSize($this->getFaqQuestionsDisplay());
        $second_table_name = $this->resource->getTableName('mageinic_faq_product_relation');
        $faqCollection->getSelect()->joinLeft(
            ['second' => $second_table_name],
            'main_table.faq_id = second.faq_id'
        );
        $arr = [];
        foreach ($faqCollection as $collection) {
            $arr[] = [
                'status' => $collection['status'],
                'visibility' => $collection['visibility'],
                'title' => $collection['title'],
                'answer' => $collection['answer'],
                'sender_name' => $collection['sender_name'],
                'sender_email' => $collection['sender_email'],
                'created_date' => $collection['created_date'],
                'updated_date' => $collection['updated_date']
            ];
        }
        return $arr;
    }

    /**
     * Set More Information
     *
     * @param mixed $url
     * @return string
     */
    public function setMoreInformation(mixed $url): string
    {
        return $this->urlInterface->getUrl($url);
    }

    /**
     * Get Current Customer
     *
     * @return Customer|string
     */
    public function getCurrentCustomer(): Customer|string
    {
        $sessionData = $this->sessionFactory->create();
        if ($sessionData->isLoggedIn()) {
            return $sessionData->getCustomer();
        } else {
            return '';
        }
    }

    /**
     * Is Logged In Customer
     *
     * @return array
     */
    public function isLoggedInCustomer(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', '1');
        $arr = [];
        foreach ($collection as $faqCollection) {
            $arr[] = [
                'status' => $faqCollection['status'],
                'visibility' => $faqCollection['visibility'],
                'title' => $faqCollection['title'],
                'answer' => $faqCollection['answer'],
                'sender_name' => $faqCollection['sender_name'],
                'sender_email' => $faqCollection['sender_email'],
                'created_date' => $faqCollection['created_date'],
                'updated_date' => $faqCollection['updated_date']
            ];
        }
        return $arr;
    }
}
