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

require([
        'jquery',
        'Magento_Ui/js/modal/modal',
    ],
    function($, modal) {
        var options = {
            type: 'popup',
            responsive: false,
            innerScroll: false,
            modalClass: 'modal-custom',
            buttons: [{
                text: $.mage.__('Submit'),
                class: 'faqmodal',
                click: function (data) {
                    var dataForm = $('#faq-form');
                    if (dataForm.validation('isValid')){
                        var sender_name = $("#sender_name").val();
                        var sender_email = $("#sender_email").val();

                        if (sender_name && sender_email){
                            $.ajax({
                                url: "<?= $formUrl; ?>",
                                dataType: 'json',
                                type: 'POST',
                                data:$("#faq-form").serialize(),
                                success: function (data) {
                                    /*alert(JSON.stringify(data));*/
                                    if (data.status == "success") {
                                        $("#faq-form").trigger("reset");
                                        $("#response-message").addClass("success");
                                        $("#response-message").html(data.message).css({
                                            "background-color": "green"
                                        });
                                    } else {
                                        $("#response-message").addClass("error");
                                        $("#response-message").html(data.message).css({
                                            "background-color": "red"
                                        });
                                    }
                                }
                            });
                        }
                        setTimeout(function () {
                            $("#popup-modal").modal("closeModal");
                        }, 2000);
                    }
                }
            }]
        };
        var popup = modal(options, $('#popup-modal'));
        $("#click-me").on('click',function(){
            $("#popup-modal").modal("openModal");
        });
    }
);
