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

namespace MageINIC\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use MageINIC\Faq\Controller\Adminhtml\Faq;
use MageINIC\Faq\Model\FaqFactory;

/**
 * Faq Class Edit
 */
class Edit extends Faq implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_Faq::faq_edit';

    /**
     * @var PageFactory
     */
    private PageFactory $resultPageFactory;

    /**
     * @var FaqFactory
     */
    private FaqFactory $faqModel;

    /**
     * @var Registry
     */
    private Registry $coreRegistry;

    /**
     * Edit constructor.
     *
     * @param Context       $context
     * @param Registry      $coreRegistry
     * @param PageFactory   $resultPageFactory
     * @param FaqFactory    $faqModel
     */
    public function __construct(
        Context      $context,
        Registry     $coreRegistry,
        PageFactory  $resultPageFactory,
        FaqFactory   $faqModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->faqModel          = $faqModel;
        $this->coreRegistry      = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Edit Execute
     *
     * @return Page|Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('faq_id');
        $model = $this->faqModel->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Faq no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('MageINIC_faq_faq', $model);
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Faq') : __('New Faq'),
            $id ? __('Edit Faq') : __('New Faq')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Faq'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Faq'));
        return $resultPage;
    }
}
