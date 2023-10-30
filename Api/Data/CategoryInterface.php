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
 * Faq Interface CategoryInterface
 */
interface CategoryInterface
{
    public const CATEGORY_ID        = 'category_id';
    public const CATEGORY_NAME      = 'category_name';
    public const CATEGORY_IMAGE     = 'image';
    public const STATUS             = 'status';
    public const SORT_ORDER         = 'sort_order';
    public const META_KEYWORDS      = 'meta_keywords';
    public const META_DESCRIPTION   = 'meta_description';
    public const CREATED_DATE       = 'created_date';
    public const UPDATED_DATE       = 'updated_date';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get category_name
     *
     * @return string|null
     */
    public function getCategoryName(): ?string;

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage(): ?string;

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
     * Get meta_keywords
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * Get meta_description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string;

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
     * Set ID
     *
     * @param int $id
     * @return CategoryInterface
     */
    public function setId($id): CategoryInterface;

    /**
     * Set category_name
     *
     * @param string $category_name
     * @return CategoryInterface
     */
    public function setCategoryName(string $category_name): CategoryInterface;

    /**
     * Set image
     *
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): CategoryInterface;

    /**
     * Set status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): CategoryInterface;

    /**
     * Set sort order
     *
     * @param string $sortOrder
     * @return $this
     */
    public function setSortOrder(string $sortOrder): CategoryInterface;

    /**
     * Set meta_keywords
     *
     * @param string $meta_keywords
     * @return $this
     */
    public function setMetaKeywords(string $meta_keywords): CategoryInterface;

    /**
     * Set meta_description
     *
     * @param string $meta_description
     * @return $this
     */
    public function setMetaDescription(string $meta_description): CategoryInterface;

    /**
     * Set created date
     *
     * @param string $createdDate
     * @return $this
     */
    public function setCreatedDate(string $createdDate): CategoryInterface;

    /**
     * Set updated date
     *
     * @param string $updatedDate
     * @return $this
     */
    public function setUpdatedDate(string $updatedDate): CategoryInterface;
}
