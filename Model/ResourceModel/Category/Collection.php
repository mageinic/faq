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

namespace MageINIC\Faq\Model\ResourceModel\Category;

use MageINIC\Faq\Api\Data\CategoryInterface;
use MageINIC\Faq\Model\Category;
use MageINIC\Faq\Model\ResourceModel\CategoryAbstract\AbstractCollection;
use MageINIC\Faq\Model\ResourceModel\Category as ResourceModelCategory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Faq Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = Category::ID;

    /**
     * @var $_previewFlag
     */
    protected $_previewFlag;

    /**
     * Add Store Filter
     *
     * @param mixed $store
     * @param mixed $withAdmin
     * @return $this|mixed
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Set First Store Flag
     *
     * @param bool $flag
     * @return $this
     */
    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * Construct Method
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Category::class, ResourceModelCategory::class);

        $this->_map['fields']['category_id'] = 'main_table.category_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * After Load
     *
     * @return AbstractCollection
     * @throws NoSuchEntityException
     */
    protected function _afterLoad(): AbstractCollection
    {
        $entityMetadata = $this->metadataPool->getMetadata(CategoryInterface::class);
        $this->performAfterLoad('mageinic_faq_category_store', $entityMetadata->getLinkField());
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    /**
     * Render Filters Before
     *
     * @throws \Exception
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(CategoryInterface::class);
        $this->joinStoreRelationTable('mageinic_faq_category_store', $entityMetadata->getLinkField());
    }
}
