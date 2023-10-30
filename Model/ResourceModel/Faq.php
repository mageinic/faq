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

namespace MageINIC\Faq\Model\ResourceModel;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\Faq\Api\Data\FaqInterface;

/**
 * MageINIC Class Faq
 */
class Faq extends AbstractDb
{
    /**
     * @var null
     */
    protected $_store = null;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @var DateTime
     */
    protected DateTime $dateTime;

    /**
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * @var MetadataPool
     */
    protected MetadataPool $metadataPool;

    /**
     * Faq constructor.
     *
     * @param Context               $context
     * @param StoreManagerInterface $storeManager
     * @param DateTime              $dateTime
     * @param EntityManager         $entityManager
     * @param MetadataPool          $metadataPool
     * @param mixed                 $connectionName
     */
    public function __construct(
        Context               $context,
        StoreManagerInterface $storeManager,
        DateTime              $dateTime,
        EntityManager         $entityManager,
        MetadataPool          $metadataPool,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
        $this->dateTime      = $dateTime;
        $this->entityManager = $entityManager;
        $this->metadataPool  = $metadataPool;
    }

    /**
     * Post Abstract Resource Constructor
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('mageinic_faq', 'faq_id');
    }

    /**
     * Load Method
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param mixed $field
     * @return $this|AbstractDb
     * @throws LocalizedException
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $pageId = $this->getFaqnameId($object, $value, $field);
        if ($pageId) {
            $this->entityManager->load($object, $pageId);
        }
        return $this;
    }

    /**
     * Get Faq name Id
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param mixed $field
     * @return bool|int|string
     * @throws LocalizedException
     */
    private function getFaqnameId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);

        if (!is_numeric($value) && $field === null) {
            $field = 'faq_id';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }

        $pageId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $pageId = count($result) ? $result[0] : false;
        }
        return $pageId;
    }

    /**
     * Get Load Select
     *
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object): Select
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId(),
            ];
            $select->join(
                ['mageinic_faq_store' => $this->getTable('mageinic_faq_store')],
                $this->getMainTable() . '.' . $linkField . ' = mageinic_faq_store.' . $linkField,
                []
            )
                ->where('is_active = ?', 1)
                ->where('mageinic_faq_store.store_id IN (?)', $storeIds)
                ->order('mageinic_store.store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Get Connection
     *
     * @return false|AdapterInterface
     * @throws \Exception
     */
    public function getConnection(): AdapterInterface|bool
    {
        return $this->metadataPool->getMetadata(FaqInterface::class)->getEntityConnection();
    }

    /**
     * Lookup Store Ids
     *
     * @param mixed $pageId
     * @return array
     * @throws LocalizedException
     */
    public function lookupStoreIds($pageId): array
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['cps' => $this->getTable('mageinic_faq_store')], 'store_id')
            ->join(['cp' => $this->getMainTable()], 'cps.' . $linkField . ' = cp.' . $linkField, [])
            ->where('cp.' . $entityMetadata->getIdentifierField() . ' = :faq_id');

        return $connection->fetchCol($select, ['faq_id' => (int)$pageId]);
    }

    /**
     * Get Store
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }

    /**
     * Set Store
     *
     * @param mixed $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Save Method
     *
     * @param AbstractModel $object
     * @return $this|AbstractDb
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * Delete Method
     *
     * @param AbstractModel $object
     * @return $this|AbstractDb
     * @throws \Exception
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * Save Product Faq Relation
     *
     * @param mixed $productfaqIds
     * @param mixed $productfaqProductId
     * @return $this
     * @throws \Exception
     */
    public function saveProductFaqRelation(mixed $productfaqIds, mixed $productfaqProductId)
    {
        $oldProducts = $this->lookupProductFaqIds($productfaqProductId);
        $newProducts = $productfaqIds;
        if (isset($newProducts)) {
            $table = $this->getTable('mageinic_faq_product_relation');
            $insert = array_diff($newProducts, $oldProducts);
            $delete = array_diff($oldProducts, $newProducts);
            if ($delete) {
                $where = ['product_id = ?' => (int)$productfaqProductId, 'faq_id IN (?)' => $delete];
                $this->getConnection()->delete($table, $where);
            }
            if ($insert) {
                $data = [];
                foreach ($insert as $productId) {
                    $data[] = ['faq_id' => (int)$productId, 'product_id' => (int)$productfaqProductId];
                }
                $this->getConnection()->insertMultiple($table, $data);
            }
        }

        return $this;
    }

    /**
     * Save Product Faq
     *
     * @param mixed $proArray
     * @throws \Exception
     */
    public function saveProductFaq($proArray)
    {
        $table = $this->getTable('mageinic_faq_product_relation');
        $this->getConnection()->insertMultiple($table, $proArray);
    }

    /**
     * Lookup Product Faq Ids
     *
     * @param mixed $productId
     * @return array
     * @throws \Exception
     */
    public function lookupProductFaqIds($productId): array
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from($this->getTable('mageinic_faq_product_relation'), 'faq_id')
            ->where('product_id = ?', (int)$productId);
        return $adapter->fetchCol($select);
    }
}
