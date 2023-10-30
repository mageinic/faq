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

namespace MageINIC\Faq\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use MageINIC\Faq\Api\CategoryRepositoryInterface;
use MageINIC\Faq\Model\Category;
use MageINIC\Faq\Model\CategoryFactory;
use MageINIC\Faq\Model\ImageUploader;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Faq Class Save
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_Faq::category_save';

    /**
     * @var CategoryFactory
     */
    public CategoryFactory $categoryFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    public CategoryRepositoryInterface $categoryRepository;

    /**
     * @var Category
     */
    protected Category $categoryModel;

    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;

    /**
     * @var AdapterFactory
     */
    protected AdapterFactory $adapterFactory;

    /**
     * @var ImageUploader
     */
    private ImageUploader $imageUploader;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param Category $categoryModel
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context                     $context,
        Category                    $categoryModel,
        CategoryFactory             $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        UploaderFactory             $uploaderFactory,
        AdapterFactory              $adapterFactory,
        ImageUploader $imageUploader
    ) {
        $this->categoryModel      = $categoryModel;
        $this->categoryFactory    = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->uploaderFactory    = $uploaderFactory;
        $this->adapterFactory     = $adapterFactory;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * Index Execute
     *
     * @return Redirect
     * @return ResponseInterface
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];

                $this->imageUploader->moveFileFromTmp($data['image']);
            } elseif (isset($data['image'][0]['image']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['image'];
            } else {
                if (isset($data['image']) && isset($data['image']['value'])) {
                    if (isset($data['image']['delete'])) {
                        $data['image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['image']['value'])) {
                        $data['image'] = $data['image']['value'];
                    } else {
                        $data['image'] = null;
                    }
                }
            }
            $model = $this->categoryFactory->create();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model = $this->categoryRepository->getById($id);
            }
            $model->setData($data);
            $model->setData('identifier', $data['category_name']);
            $this->_eventManager->dispatch(
                'reporttoadmin_category_prepare_save',
                ['category' => $model, 'request' => $this->getRequest()]
            );
            try {
                $this->categoryRepository->save($model);
                $this->messageManager->addSuccess(__('Category saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit',
                        ['category_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException|\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Category'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit',
                ['category_id' => $this->getRequest()->getParam('category_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
