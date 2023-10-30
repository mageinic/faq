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

use Magento\Framework\View\Element\Template;
use MageINIC\Faq\Helper\Data;

/**
 * Faq Footer Link
 */
class FooterLink extends Template
{
    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * FooterConfig constructor.
     *
     * @param Template\Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * Check if the footer link is enabled or disabled
     *
     * @return bool
     */
    public function isFooterLinkEnabled(): bool
    {
        return $this->helper->getFooterLinkDisplay();
    }

    /**
     * Check if the footer link is enabled or disabled
     *
     * @return bool
     */
    public function getFaqEnable(): bool
    {
        return $this->helper->getFaqEnable();
    }
}
