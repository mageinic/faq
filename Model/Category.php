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

namespace MageINIC\Faq\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Model\Context;
use \Magento\Framework\Model\AbstractModel;
use Magento\Framework\Data\Collection\AbstractDb;
use MageINIC\Faq\Api\Data\CategoryInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;

/**
 * Faq Class Category
 */
class Category extends AbstractModel implements CategoryInterface, IdentityInterface
{
    /**
     * MageINIC page cache tag
     */
    public const CACHE_TAG = 'MageINIC_category';
    public const ID        = 'category_id';

    /**
     * @var string
     */
    protected $cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'category';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'Category';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::ID;

    /**
     * Method for Category
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Category::class);
    }

    /**
     * Check Identifier
     *
     * @param mixed $identifier
     * @param mixed $storeId
     * @return mixed
     * @throws LocalizedException
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Receive Category store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getId(): ?int
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * @inheritdoc
     */
    public function getCategoryName(): ?string
    {
        return $this->getData(self::CATEGORY_NAME);
    }

    /**
     * @inheritdoc
     */
    public function getImage(): ?string
    {
        return $this->getData(self::CATEGORY_IMAGE);
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder(): ?string
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritdoc
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * @inheritdoc
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedDate(): ?string
    {
        return $this->getData(self::CREATED_DATE);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedDate(): ?string
    {
        return $this->getData(self::UPDATED_DATE);
    }

    /**
     * @inheritdoc
     */
    public function setId($id): CategoryInterface
    {
        return $this->setData(self::CATEGORY_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function setCategoryName(string $category_name): CategoryInterface
    {
        return $this->setData(self::CATEGORY_NAME, $category_name);
    }

    /**
     * @inheritdoc
     */
    public function setImage(string $image): CategoryInterface
    {
        return $this->setData(self::CATEGORY_IMAGE, $image);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): CategoryInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function setSortOrder(string $sortOrder): CategoryInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritdoc
     */
    public function setMetaKeywords(string $meta_keywords): CategoryInterface
    {
        return $this->setData(self::META_KEYWORDS, $meta_keywords);
    }

    /**
     * @inheritdoc
     */
    public function setMetaDescription(string $meta_description): CategoryInterface
    {
        return $this->setData(self::META_DESCRIPTION, $meta_description);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedDate(string $createdDate): CategoryInterface
    {
        return $this->setData(self::CREATED_DATE, $createdDate);
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedDate(string $updatedDate): CategoryInterface
    {
        return $this->setData(self::UPDATED_DATE, $updatedDate);
    }
}
