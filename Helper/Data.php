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

namespace MageINIC\Faq\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Faq Class Data
 */
class Data extends AbstractHelper
{
    public const MEDIA_DIR              = 'MageINIC';
    public const ENABLE_SLIDER          = 'mageinic/slider_setting/enable_slider';
    public const SLIDER_TITLE           = 'mageinic/slider_setting/slider_title';
    public const INFINITE_LOOPING       = 'mageinic/slider_setting/infinite_looping';
    public const SLIDER_SPEED           = 'mageinic/slider_setting/slider_speed';
    public const SLIDE_TO_DEFAULT       = 'mageinic/slider_setting/slide_to_default';
    public const SLICK_TO_DEFAULT       = 'mageinic/slider_setting/slick_to_default';
    public const DOT                    = 'mageinic/slider_setting/dot';
    public const ARROWS                  = 'mageinic/slider_setting/arrows';
    public const AUTOPLAY               = 'mageinic/slider_setting/autoplay';
    public const AUTOPLAYSPEED          = 'mageinic/slider_setting/autoplay_speed';
    public const FAQ_ENABLE             = 'mageinic/general/faq_enable';
    public const FOOTER_LINK_ACTION     = 'faq/category/index';
    public const FAQ_CATEGORY_PATH      = 'faq/category';
    public const FAQ_QUESTIONS_DISPLAY  = 'mageinic/general/faq_questions_display';
    public const FOOTER_LINK_DISPLAY    = 'mageinic/general/footer_link_display';
    public const PAGE_TITLE             = 'mageinic/general/page_title';
    public const META_KEYWORDS          = 'mageinic/general/meta_keywords';
    public const META_DESCRIPTION       = 'mageinic/general/meta_description';
    public const BREAKPOINT             = 'mageinic/slider_setting/breakpoints';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Serializer $serializer
     */
    public function __construct(
        Context               $context,
        StoreManagerInterface $storeManager,
        Serializer                $serializer
    ) {
        $this->storeManager = $storeManager;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Get Base Url Media
     *
     * @param mixed $path
     * @param mixed $secure
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrlMedia($path = '', $secure = false): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA, $secure) . $path;
    }

    /**
     * Get Category Image Url
     *
     * @param mixed $image
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCategoryImageUrl(mixed $image): string
    {
        return $this->getMediaUrl() . self::MEDIA_DIR . '/' . $image;
    }

    /**
     * Get MediaUrl
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Get Slider Enable
     *
     * @return mixed
     */
    public function getSliderEnable(): mixed
    {
        return $this->scopeConfig->getValue(self::ENABLE_SLIDER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Slider Title
     *
     * @return mixed
     */
    public function getSliderTitle(): mixed
    {
        return $this->scopeConfig->getValue(self::SLIDER_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Infinite Looping
     *
     * @return bool
     */
    public function getInfiniteLooping(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::INFINITE_LOOPING, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Default Slide
     *
     * @return int
     */
    public function getDefaultSlide(): int
    {
        return (int)$this->scopeConfig->getValue(self::SLIDE_TO_DEFAULT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Default Slick
     *
     * @return int
     */
    public function getDefaultSlick(): int
    {
        return (int)$this->scopeConfig->getValue(self::SLICK_TO_DEFAULT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Is Arrow
     *
     * @return bool
     */
    public function isArrow(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::ARROWS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Slider Speed
     *
     * @return int
     */
    public function getSliderSpeed(): int
    {
        return (int)$this->scopeConfig->getValue(self::SLIDER_SPEED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get AutoPlay
     *
     * @return bool
     */
    public function getAutoPlay(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::AUTOPLAY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Show Dots By Default
     *
     * @return bool
     */
    public function getShowDotsByDefault(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::DOT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Auto Play Speed
     *
     * @return int
     */
    public function getAutoPlaySpeed(): int
    {
        return (int)$this->scopeConfig->getValue(self::AUTOPLAYSPEED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Faq Enable
     *
     * @return mixed
     */
    public function getFaqEnable(): mixed
    {
        return $this->scopeConfig->getValue(self::FAQ_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Faq Questions Display
     *
     * @return mixed
     */
    public function getFaqQuestionsDisplay(): mixed
    {
        return $this->scopeConfig->getValue(self::FAQ_QUESTIONS_DISPLAY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Footer Link Display
     *
     * @return mixed
     */
    public function getFooterLinkDisplay(): mixed
    {
        return $this->scopeConfig->getValue(self::FOOTER_LINK_DISPLAY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Page Title
     *
     * @return mixed
     */
    public function getPageTitle(): mixed
    {
        return $this->scopeConfig->getValue(self::PAGE_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Keywords
     *
     * @return mixed
     */
    public function getKeywords(): mixed
    {
        return $this->scopeConfig->getValue(self::META_KEYWORDS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Description
     *
     * @return mixed
     */
    public function getDescription(): mixed
    {
        return $this->scopeConfig->getValue(self::META_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Receive Breakpoints
     *
     * @return string
     */
    public function getBreakPoints(): string
    {
        $breakpoints = $this->scopeConfig->getValue(self::BREAKPOINT, ScopeInterface::SCOPE_STORE);
        $values = [];
        $breakpoints = $this->serializer->unserialize($breakpoints);
        foreach ($breakpoints as $breakpoint) {
            $values[] = [
                "breakpoint" => (int)$breakpoint['breakpoint'],
                "settings" => [
                    "slidesToShow" => (int)$breakpoint['slides_to_show'],
                    "slidesToScroll" => (int)$breakpoint['slides_to_scroll'],
                    "dots" => (bool)$breakpoint['dots']
                ]
            ];
        }
        return $this->serializer->serialize($values);
    }
}
