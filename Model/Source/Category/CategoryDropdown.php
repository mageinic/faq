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

namespace MageINIC\Faq\Model\Source\Category;

use Magento\Framework\Data\OptionSourceInterface;
use MageINIC\Faq\Model\ResourceModel\Category\CollectionFactory;

/**
 * Faq Class CategoryDropdown
 */
class CategoryDropdown implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * CategoryDropdown constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Option Array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $questionCollection = $this->collectionFactory->create();
        $options[] = [
            'label' => __('-- Select Category(s) --'),
            'value' => 0,
        ];
        foreach ($questionCollection as $question) {
            $options[] = [
                'label' => $question->getCategoryName(),
                'value' => $question->getCategoryId()
            ];
        }
        return $options;
    }
}
