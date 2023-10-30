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

namespace MageINIC\Faq\Api\Data;

/**
 * Interface FaqInterface
 */
interface FaqInterface
{
    public const FAQ_ID           = 'faq_id';
    public const CATEGORY_ID      = 'category_id';
    public const TITLE            = 'title';
    public const ANSWER           = 'answer';
    public const STATUS           = 'status';
    public const SORT_ORDER       = 'sort_order';
    public const CREATED_DATE     = 'created_date';
    public const UPDATED_DATE     = 'updated_date';
    public const MOST_FREQUENTLY  = 'most_frequently';
    public const SENDER_NAME      = 'sender_name';
    public const SENDER_EMAIL     = 'sender_email';
    public const VISIBILITY       = 'visibility';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get category_id
     *
     * @return int|null
     */
    public function getCategoryId(): ?int;

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Get answer
     *
     * @return string|null
     */
    public function getAnswer(): ?string;

    /**
     * Get status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Get sort order
     *
     * @return string|null
     */
    public function getSortOrder(): ?string;

    /**
     * Get created date
     *
     * @return string|null
     */
    public function getCreatedDate(): ?string;

    /**
     * Get updated date
     *
     * @return string|null
     */
    public function getUpdatedDate(): ?string;

    /**
     * Get most frequently
     *
     * @return int
     */
    public function getMostFrequently(): int;

    /**
     * Get sender_name
     *
     * @return string|null
     */
    public function getSenderName(): ?string;

    /**
     * Get sender_email
     *
     * @return string|null
     */
    public function getSenderEmail(): ?string;

    /**
     * Get visibility
     *
     * @return int|null
     */
    public function getVisibility(): ?int;

    /**
     * Set ID
     *
     * @param int $id
     * @return FaqInterface
     */
    public function setId($id): FaqInterface;

    /**
     * Set category_id
     *
     * @param int $category_id
     * @return FaqInterface
     */
    public function setCategoryId(int $category_id): FaqInterface;

    /**
     * Set title
     *
     * @param string $title
     * @return FaqInterface
     */
    public function setTitle(string $title): FaqInterface;

    /**
     * Set answer
     *
     * @param string $answer
     * @return FaqInterface
     */
    public function setAnswer(string $answer): FaqInterface;

    /**
     * Set status
     *
     * @param int $status
     * @return FaqInterface
     */
    public function setStatus(int $status): FaqInterface;

    /**
     * Set sort order
     *
     * @param string $sortOrder
     * @return FaqInterface
     */
    public function setSortOrder(string $sortOrder): FaqInterface;

    /**
     * Set created date
     *
     * @param string $createdDate
     * @return FaqInterface
     */
    public function setCreatedDate(string $createdDate): FaqInterface;

    /**
     * Set updated date
     *
     * @param string $updatedDate
     * @return FaqInterface
     */
    public function setUpdatedDate(string $updatedDate): FaqInterface;

    /**
     * Set most frequently
     *
     * @param int $mostFrequently
     * @return FaqInterface
     */
    public function setMostFrequently(int $mostFrequently): FaqInterface;

    /**
     * Set sender_name
     *
     * @param string $senderName
     * @return FaqInterface
     */
    public function setSenderName(string $senderName): FaqInterface;

    /**
     * Set sender_email
     *
     * @param string $senderEmail
     * @return FaqInterface
     */
    public function setSenderEmail(string $senderEmail): FaqInterface;

    /**
     * Set visibility
     *
     * @param int $visibility
     * @return FaqInterface
     */
    public function setVisibility(int $visibility): FaqInterface;
}
