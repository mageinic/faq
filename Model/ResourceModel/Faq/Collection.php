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

namespace MageINIC\Faq\Model\ResourceModel\Faq;

use MageINIC\Faq\Api\Data\FaqInterface;
use MageINIC\Faq\Model\Faq;
use MageINIC\Faq\Model\ResourceModel\FaqAbstract\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use MageINIC\Faq\Model\ResourceModel\Faq as ResourceModelFaq;

/**
 * Faq Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'faq_id';

    /**
     * @var $_previewFlag
     */
    protected $_previewFlag;

    /**
     * Add Store Filter
     *
     * @param mixed $store
     * @param bool $withAdmin
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
     * After Load
     *
     * @return AbstractCollection
     * @throws NoSuchEntityException
     */
    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);

        $this->performAfterLoad('mageinic_faq_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Faq::class,
            ResourceModelFaq::class
        );
        $this->_map['fields']['faq_id'] = 'main_table.faq_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Render Filters Before
     *
     * @throws \Exception
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $this->joinStoreRelationTable('mageinic_faq_store', $entityMetadata->getLinkField());
    }
}
