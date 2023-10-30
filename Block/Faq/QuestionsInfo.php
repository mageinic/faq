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

use MageINIC\Faq\Helper\Data;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template;
use MageINIC\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;

/**
 * Faq Class QuestionsInfo
 */
class QuestionsInfo extends Template implements BlockInterface
{
    /**
     * @var FaqCollectionFactory
     */
    public FaqCollectionFactory $faqCollectionFactory;

    /**
     * @var Data
     */
    public Data $helper;

    /**
     * @var View
     */
    private View $faqview;

    /**
     * QuestionsInfo constructor.
     *
     * @param Template\Context $context
     * @param FaqCollectionFactory $faqCollectionFactory
     * @param View $faqview
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context     $context,
        FaqCollectionFactory $faqCollectionFactory,
        View                 $faqview,
        Data                 $helper,
        array                $data = []
    ) {
        $this->faqCollectionFactory  =  $faqCollectionFactory;
        $this->helper          =  $helper;
        $this->faqview = $faqview;
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
     * Get Ques Ans Collection
     *
     * @return array
     */
    public function getQuesAnsCollection(): array
    {
        $collection = $this->faqCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('status', '1');
        $isLoggedIn = (bool)$this->faqview->getCurrentCustomer();
        if (!$isLoggedIn) {
            $collection->addFieldToFilter('visibility', ['neq' => '1']);
        }
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
