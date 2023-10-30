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

namespace MageINIC\Faq\Model\ResourceModel\FaqAbstract;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\Store;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as CollectionAbstractCollection;

/**
 * Faq Class AbstractCollection
 */
abstract class AbstractCollection extends CollectionAbstractCollection
{
    /**
     * @var EntityFactoryInterface
     */
    public EntityFactoryInterface $entityFactory;

    /**
     * @var LoggerInterface
     */
    public LoggerInterface $logger;

    /**
     * @var FetchStrategyInterface
     */
    public FetchStrategyInterface $fetchStrategy;

    /**
     * @var ManagerInterface
     */
    public ManagerInterface $eventManager;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var MetadataPool
     */
    protected MetadataPool $metadataPool;

    /**
     * AbstractCollection constructor.
     *
     * @param EntityFactoryInterface    $entityFactory
     * @param LoggerInterface           $logger
     * @param FetchStrategyInterface    $fetchStrategy
     * @param ManagerInterface          $eventManager
     * @param StoreManagerInterface     $storeManager
     * @param MetadataPool              $metadataPool
     * @param AdapterInterface|null     $connection
     * @param AbstractDb|null           $resource
     */
    public function __construct(
        EntityFactoryInterface  $entityFactory,
        LoggerInterface         $logger,
        FetchStrategyInterface  $fetchStrategy,
        ManagerInterface        $eventManager,
        StoreManagerInterface   $storeManager,
        MetadataPool            $metadataPool,
        AdapterInterface        $connection = null,
        AbstractDb              $resource = null
    ) {
        $this->storeManager  = $storeManager;
        $this->metadataPool  = $metadataPool;
        $this->entityFactory = $entityFactory;
        $this->logger        = $logger;
        $this->fetchStrategy = $fetchStrategy;
        $this->eventManager  = $eventManager;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Add Field To Filter
     *
     * @param array|string $field
     * @param mixed $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add Store Filter
     *
     * @param mixed $store
     * @param bool $withAdmin
     * @return mixed
     */
    abstract public function addStoreFilter($store, $withAdmin = true);

    /**
     * Get Select CountSql
     *
     * @return Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Select::GROUP);
        return $countSelect;
    }

    /**
     * Perform After Load
     *
     * @param mixed $tableName
     * @param mixed $linkField
     * @throws NoSuchEntityException
     */
    protected function performAfterLoad($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['mageinic_faq_store' => $this->getTable($tableName)])
                ->where('mageinic_faq_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }
                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storesData[$linkedId]);
                }
            }
        }
    }

    /**
     * Perform AddStore Filter
     *
     * @param mixed $store
     * @param bool $withAdmin
     */
    protected function performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    /**
     * Join Store Relation Table
     *
     * @param mixed $tableName
     * @param mixed $linkField
     */
    protected function joinStoreRelationTable($tableName, $linkField)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = store_table.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
        parent::_renderFiltersBefore();
    }
}
