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

namespace MageINIC\Faq\Controller\Index;

use MageINIC\Faq\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Block\Html\Breadcrumbs;

/**
 * Faq Class FaqQuesList
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var Breadcrumbs
     */
    protected Breadcrumbs $breadcrumbs;

    /**
     * @var Data
     */
    private Data $data;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param Data $data
     * @param PageFactory $resultPageFactory
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(
        Context     $context,
        Data $data,
        PageFactory $resultPageFactory,
        Breadcrumbs $breadcrumbs
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->breadcrumbs = $breadcrumbs;
        $this->data = $data;
        parent::__construct($context);
    }

    /**
     * Execute Method
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $enable = $this->data->getFaqEnable();
        if ($enable) {
            $resultPage = $this->resultPageFactory->create();
            $breadcrumbsBlock = $resultPage->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbsBlock) {
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'title' => __('Go to Home Page'),
                        'link' => $this->_url->getUrl('')
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'faq',
                    [
                        'label' => __('FAQ'),
                        'title' => __('Frequently Asked Questions'),
                        'link' => $this->_url->getUrl('faq')
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'current_page',
                    [
                        'label' => __('Questions & Answers List'),
                        'title' => __('Customers Questions & Answers List')
                    ]
                );
            }
            $resultPage->getConfig()->getTitle()->prepend(__('Customers Questions & Answers List'));
            return $resultPage;
        } else {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/noroute');
            return $resultRedirect;
        }
    }
}
