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
 * @package MageINIC_PricePerCustomer
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\Faq\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageINIC\Faq\Model\ResourceModel\Faq;

/**
 * Class of UpdateCustomerPrice
 */
class UpdateFaq implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Faq
     */
    private Faq $faq;

    /**
     * UpdateCustomerPrice Constructor
     *
     * @param RequestInterface $request
     * @param Faq $faq
     */
    public function __construct(
        RequestInterface  $request,
        Faq $faq
    ) {
        $this->request = $request;
        $this->faq = $faq;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer): UpdateFaq
    {
        $productId = $observer->getEvent()->getProduct()->getId();
        $productparams['links']['faq'] = '';
        $productparams = $this->request->getParams();
        $customerPriceIds = [];
        if (!empty($productparams['links']['faq'])) {
            $customerPrices = $productparams['links']['faq'];
            foreach ($customerPrices as $price) {
                $customerPriceIds[] = $price['id'];
            }
        }
        $this->faq->saveProductFaqRelation((array)$customerPriceIds, $productId);
        return $this;
    }
}
