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

namespace MageINIC\Faq\Controller\Faq;

use MageINIC\Faq\Model\FaqFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use MageINIC\Faq\Model\ResourceModel\Faq;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use MageINIC\Faq\Api\FaqRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Faq Class FaqPopupForm
 */
class FaqPopupForm extends Action
{
    /**
     * @var JsonFactory
     */
    public JsonFactory $resultJsonFactory;

    /**
     * @var FaqFactory
     */
    public FaqFactory $faqFactory;

    /**
     * @var FaqRepositoryInterface
     */
    public FaqRepositoryInterface $faqRepository;

    /**
     * @var Faq
     */
    public Faq $faq;

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * FaqPopupForm constructor.
     *
     * @param Context                   $context
     * @param PageFactory               $resultPageFactory
     * @param JsonFactory               $resultJsonFactory
     * @param FaqFactory                $faqFactory
     * @param Faq                       $faq
     * @param FaqRepositoryInterface    $faqRepository
     */
    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        FaqFactory  $faqFactory,
        Faq         $faq,
        FaqRepositoryInterface  $faqRepository
    ) {
        $this->resultPageFactory  = $resultPageFactory;
        $this->resultJsonFactory  = $resultJsonFactory;
        $this->faqFactory         = $faqFactory;
        $this->faqRepository      = $faqRepository;
        $this->faq  = $faq;
        parent::__construct($context);
    }

    /**
     * Faq Popup Form
     *
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $result = $this->resultJsonFactory->create();
        $value = [];
        if ($data) {
            $model = $this->faqFactory->create();
            $model->setData($data);
            try {
                $faqId = $this->faqRepository->save($model);
                $product = ['product_id' =>$data['product_id'],'faq_id' =>$faqId->getId()];
                $this->faq->saveProductFaq($product);
                $value['status'] = "success";
                $value['message'] = "Your Question has been Sent.";
            } catch (\Exception $exception) {
                $value['status'] = "error";
                $value['message'] = "Something went wrong while saving the file.";
            }
        }
        return $result->setData($value);
    }
}
