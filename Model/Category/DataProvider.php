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

namespace MageINIC\Faq\Model\Category;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use MageINIC\Faq\Model\ResourceModel\Category\CollectionFactory;

/**
 * Faq Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * @var
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * DataProvider constructor.
     *
     * @param string                    $name
     * @param string                    $primaryFieldName
     * @param string                    $requestFieldName
     * @param DataPersistorInterface    $dataPersistor
     * @param StoreManagerInterface     $storeManager
     * @param CollectionFactory         $collectionFactory
     * @param array                     $meta
     * @param array                     $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface  $dataPersistor,
        StoreManagerInterface   $storeManager,
        CollectionFactory       $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager  = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get Data
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        $baseurl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $item = $this->collection->getItems();
        foreach ($item as $custom) {
            $temp = $custom->getData();
            if ($temp['image']):
                $img = [];
                $img[0]['image'] = $temp['image'];
                $img[0]['url'] = $baseurl . 'MageINIC/faq/image/' . $temp['image'];
                $temp['image'] = $img;
            endif;
            $this->loadedData[$custom->getId()] = $temp;
        }
        $data = $this->dataPersistor->get('faq_category');
        if (!empty($data)) {
            $custom = $this->collection->getNewEmptyItem();
            $custom->setData($data);
            $this->loadedData[$custom->getId()] = $custom->getData();
            $this->dataPersistor->clear('faq_category');
        }
        return $this->loadedData;
    }
}
