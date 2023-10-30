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

namespace MageINIC\Faq\Controller\Category;

use MageINIC\Faq\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Faq Class View
 */
class View extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

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
     */
    public function __construct(
        Context     $context,
        Data $data,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory  = $resultPageFactory;
        parent::__construct($context);
        $this->data = $data;
    }

    /**
     * View Execute
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $enable = $this->data->getFaqEnable();
        if ($enable) {
            $resultPage = $this->resultPageFactory->create();
            return $resultPage;
        } else {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/noroute');
            return $resultRedirect;
        }
    }
}
