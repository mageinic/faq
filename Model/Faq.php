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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use MageINIC\Faq\Api\Data\FaqInterface;

/**
 * MageINIC Class Faq
 */
class Faq extends AbstractModel implements FaqInterface, IdentityInterface
{
    /**
     * MageINIC page cache tag
     */
    public const CACHE_TAG = 'MageINIC_faq';
    public const ID        = 'faq_id';

    /**
     * @var string
     */
    protected $cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'MageINIC_faq';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'User';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::ID;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(ResourceModel\Faq::class);
    }

    /**
     * Check Identifier
     *
     * @param mixed $identifier
     * @param mixed $storeId
     * @return mixed
     * @throws LocalizedException
     */
    public function checkIdentifier($identifier, $storeId): mixed
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores(): array
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getId(): ?int
    {
        return $this->getData(self::FAQ_ID);
    }

    /**
     * @inheritdoc
     */
    public function getCategoryId(): ?int
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function getAnswer(): ?string
    {
        return $this->getData(self::ANSWER);
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
    public function getMostFrequently(): int
    {
        return $this->getData(self::MOST_FREQUENTLY);
    }

    /**
     * @inheritdoc
     */
    public function getSenderName(): ?string
    {
        return $this->getData(self::SENDER_NAME);
    }

    /**
     * @inheritdoc
     */
    public function getSenderEmail(): ?string
    {
        return $this->getData(self::SENDER_EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function getVisibility(): ?int
    {
        return $this->getData(self::VISIBILITY);
    }

    /**
     * @inheritdoc
     */
    public function setId($id): FaqInterface
    {
        return $this->setData(self::FAQ_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function setCategoryId(int $category_id): FaqInterface
    {
        return $this->setData(self::CATEGORY_ID, $category_id);
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): FaqInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function setAnswer(string $answer): FaqInterface
    {
        return $this->setData(self::ANSWER, $answer);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): FaqInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function setSortOrder(string $sortOrder): FaqInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedDate(string $createdDate): FaqInterface
    {
        return $this->setData(self::CREATED_DATE, $createdDate);
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedDate(string $updatedDate): FaqInterface
    {
        return $this->setData(self::UPDATED_DATE, $updatedDate);
    }

    /**
     * @inheritdoc
     */
    public function setMostFrequently(int $mostFrequently): FaqInterface
    {
        return $this->setData(self::MOST_FREQUENTLY, $mostFrequently);
    }

    /**
     * @inheritdoc
     */
    public function setSenderName(string $senderName): FaqInterface
    {
        return $this->setData(self::SENDER_NAME, $senderName);
    }

    /**
     * @inheritdoc
     */
    public function setSenderEmail(string $senderEmail): FaqInterface
    {
        return $this->setData(self::SENDER_EMAIL, $senderEmail);
    }

    /**
     * @inheritdoc
     */
    public function setVisibility(int $visibility): FaqInterface
    {
        return $this->setData(self::VISIBILITY, $visibility);
    }
}
