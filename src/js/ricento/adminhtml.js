/**
 *
 */
Ricento = window.Ricento || {};
Ricento.addProductsPopup = function(url) {
    if ($('ricento_popup') && typeof(Windows) != 'undefined') {
        Windows.focus('ricento_popup');
        return;
    }

    Dialog.info({url:url}, {
        closable:true,
        resizable:true,
        maximizable: true,
        draggable:true,
        className:'magento',
        windowClassName:'popup-window',
        title: Translator.translate('Add Product'),
        top:50,
        width:900,
        height:600,
        zIndex:1000,
        recenterAuto:false,
        hideEffect:Element.hide,
        showEffect:Element.show,
        id:'ricento_popup',
        showProgress:true,
        onShow:function(dialog) {
            dialog.element.innerHTML.evalScripts();
            /*
             * products_listing_add_massactionJsObject was declared in local scope, we need to make it global:
             */
            window.products_listing_add_massactionJsObject = products_listing_addJsObject.massaction;
        }
    });
};
Ricento.newListingPopup = function() {
    if ($('ricento_popup') && typeof(Windows) != 'undefined') {
        Windows.focus('ricento_popup');
        return;
    }

    Dialog.info(Ricento.htmlNewListingForm, {
        closable:true,
        resizable:true,
        maximizable: true,
        draggable:true,
        className:'magento',
        windowClassName:'popup-window',
        title: Translator.translate('Create Product Listing'),
        top:50,
        width:600,
        height:'auto',
        zIndex:1000,
        recenterAuto:false,
        resizeAuto:true,
        hideEffect:Element.hide,
        showEffect:Element.show,
        id:'ricento_popup',
        showProgress:true,
        onShow:function(dialog) {
            dialog.element.innerHTML.evalScripts();
            Ricento.newListingForm = new varienForm('diglin_ricento_create_listing_form');
        }
    });
};
Ricento.categoryMappingPopup = function(url, target, targetTitle) {
    if ($('ricento_popup') && typeof(Windows) != 'undefined') {
        Windows.focus('ricento_popup');
        return;
    }

    Dialog.info(
        {
            url : url.replace('#ID#', target.getValue()),
            options : {
                /*
                 * onSuccess is called before onComplete. If the response is a JSON string with error message,
                 * the dialog opening gets prevented with unsetting Dialog.callFunc
                 */
                onSuccess : function(response) {
                    try {
                        var jsonResponse = response.responseText.evalJSON();
                        if (jsonResponse && jsonResponse.error) {
                            alert(jsonResponse.message);
                            Dialog.callFunc = function() {};
                        }
                    } catch (e) {
                        // if it's not JSON, everything is fine.
                    }
                }
            }
        }, {
            closable:true,
            resizable:true,
            maximizable: true,
            draggable:true,
            className:'magento',
            windowClassName:'popup-window',
            title: Translator.translate('Choose Ricardo Category'),
            top:50,
            width:940,
            height:600,
            zIndex:400,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:'ricento_popup',
            showProgress:true,
            onShow:function(dialog) {
                dialog.element.innerHTML.evalScripts();
            }
        }
    );
    Ricento.categoryMappingTargetInput = target;
    Ricento.categoryMappingTargetTitle = targetTitle;
};
Ricento.showCategoryTreePopup = function(url) {
    if ($('ricento_popup') && typeof(Windows) != 'undefined') {
        Windows.focus('ricento_popup');
        return;
    }

    Dialog.info({url:url}, {
        closable:true,
        resizable:true,
        maximizable: true,
        draggable:true,
        className:'magento',
        windowClassName:'popup-window',
        title: Translator.translate('Add Products from selected categories'),
        top:50,
        width:900,
        height:600,
        zIndex:1000,
        recenterAuto:false,
        hideEffect:Element.hide,
        showEffect:Element.show,
        id:'ricento_popup',
        showProgress:true,
        onShow:function(dialog) {
            dialog.element.innerHTML.evalScripts();
            /*
             * products_listing_add_massactionJsObject was declared in local scope, we need to make it global:
             */
            /*window.products_listing_add_massactionJsObject = products_listing_addJsObject.massaction;*/
        }
    });
};
Ricento.progressPopup = function(url) {
    if ($('ricento_popup') && typeof(Windows) != 'undefined') {
        Windows.focus('ricento_popup');
        return;
    }

    Dialog.info({url:url}, {
        closable:true,
        resizable:true,
        maximizable: true,
        draggable:true,
        className:'magento',
        windowClassName:'popup-window',
        title: Translator.translate('Product Items Data Control'),
        top:50,
        width:900,
        height:600,
        zIndex:1000,
        recenterAuto:false,
        hideEffect:Element.hide,
        showEffect:Element.show,
        id:'ricento_popup',
        showProgress:true,
        onShow:function(dialog) {
            dialog.element.innerHTML.evalScripts();
        }
    });
};
Ricento.closePopup = function() {
    Windows.close('ricento_popup');
};

Ricento.useProductListSettings = function(checkbox, htmlIdPrefix) {
    checkbox.form.getElements().each(function(element) {
        if (element!=checkbox && element.id.startsWith(htmlIdPrefix)) {
            element.disabled=checkbox.checked;
            if (checkbox.checked) {
                element.addClassName('disabled');
            } else {
                element.removeClassName('disabled');
            }
        }
    });
    checkbox.form.select('img[id$=_trig]').each(function(calendar) {
        if (checkbox.checked) {
            calendar.hide();
        } else {
            calendar.show();
        }
    })
}

// Used in Synchronization Jobs Grid Page to display the progress
Ricento.progressInterval = function (url, prefix) {

    var progress_bar = new Control.ProgressBar('progress_bar' + prefix, {interval: 0.15});

    var u = new Ajax.PeriodicalUpdater('debug' + prefix, url, {
        method:     'get',
        frequency:  3,
        loaderArea: false,

        onSuccess: function(transport) {
            var response;
            var progressBarElement = $('progress_bar' + prefix);
            var progressElement = $('progress' + prefix);
            var adviceElement = $('advice' + prefix);
            var messageElement = $('job_message' + prefix);
            var statusElement = $('job_status' + prefix);
            var startedElement = $('started_at' + prefix);
            var endedElement = $('ended_at' + prefix);

            try {
                response = eval('(' + transport.responseText + ')');
                if (response.length <= 0) {
                    return;
                }
                progress_bar.setProgress(response.percentage);
                progressElement.innerHTML = Math.min(Math.round(response.percentage), 100) + '%';

                if (response.state == 'chunk_running' || response.state == 'running') {
                    progressElement.addClassName('sync-indicator');
                    adviceElement.innerHTML = '';
                } else if (response.state == 'completed') {
                    u.stop();

                    progressElement.removeClassName('sync-indicator');

                    switch (response.status ) {
                        case 'Success':
                            progressBarElement.setStyle({backgroundColor :'green'});
                            break;
                        case 'Warning':
                            progressBarElement.setStyle({backgroundColor :'orange'});
                            break;
                        case 'Error':
                            progressBarElement.setStyle({backgroundColor :'red'});
                            break;
                    }

                    statusElement.innerHTML = response.status;
                    statusElement.addClassName('job_status-' + response.status.toLowerCase());
                    adviceElement.innerHTML = '';
                }

                messageElement.innerHTML = response.message;
                startedElement.innerHTML = response.started_at;
                endedElement.innerHTML = response.ended_at;

            } catch (e) {
                response = {};
            }
        }
    });
}

Ricento.salesOptionsForm = Class.create();
Ricento.salesOptionsForm.prototype = {
    initialize : function(htmlIdPrefix) {
        this.htmlIdPrefix = htmlIdPrefix;
        this.requiredText = '<span class="required">*</span>';
        this.requiredClass = 'required-entry';
        this.validationPassedClass = 'validation-passed';
        this.requiredIfVisibleClass = 'required-if-visible';
        this.startPriceClass = 'validate-number-range number-range-0.05-1000000';
        this.langs = ['fr','de'];
        var self = this;

        this.showSalesTypeFieldsets($(this.htmlIdPrefix + "sales_type").value, $(this.htmlIdPrefix + "sales_auction_direct_buy").value == "1");

        Countable.live($(this.htmlIdPrefix + 'product_warranty_description_de'), function (counter){
            $(self.htmlIdPrefix + 'product_warranty_description_de_result__all').update(counter.characters);
        });
        Countable.live($(this.htmlIdPrefix + 'product_warranty_description_fr'), function (counter){
            $(self.htmlIdPrefix + 'product_warranty_description_fr_result__all').update(counter.characters);
        });

    },
    toggleRequired : function(field, required, label) {
        field = $(field);
        label = label || $$('label[for=' + field.id + ']')[0];
        var validationAdvice = $('advice-required-entry-' + field.id);
        if (label) {
            label.select('.required').each(Element.remove);
        }
        if (label && required) {
            label.insert(this.requiredText);
        }
        if (validationAdvice && !required) {
            validationAdvice.replace('');
        }
        field.removeClassName(this.validationPassedClass);
        if (required) {
            field.addClassName(this.requiredClass);
        } else {
            field.removeClassName(this.requiredClass);
        }
    },
    showSalesTypeFieldsets : function(salesType, allowDirectBuy, untilsoldText, untilsoldValue) {
        $$('div[id^=fieldset_toggle_]').each(this._hideFieldset.bind(this));
        this._showFieldset($('fieldset_toggle_' + salesType));
        if (allowDirectBuy) {
            this._showFieldset($('fieldset_toggle_buynow'));
        }
        if (salesType == 'buynow') {
            var option = document.createElement("option");
            option.text = untilsoldText;
            option.value = untilsoldValue;
            $(this.htmlIdPrefix + 'schedule_reactivation').add(option);
            $(this.htmlIdPrefix + 'sales_auction_start_price').removeClassName(this.startPriceClass);
        } else {
            var options = $(this.htmlIdPrefix + 'schedule_reactivation').options;
            $(this.htmlIdPrefix + 'sales_auction_start_price').addClassName(this.startPriceClass);

            for(var i= 0; i < options.length; i++)
            {
                if (options[i].value == untilsoldValue) {
                    $(this.htmlIdPrefix + 'schedule_reactivation').remove(i);
                }
            }
        }
    },
    _hideFieldset : function(fieldset) {
        var self = this;
        fieldset.select('.' + this.requiredIfVisibleClass).each(function(field) {
            self.toggleRequired(field, false);
        });
        fieldset.hide();
    },
    _showFieldset : function(fieldset) {
        var self = this;
        fieldset.select('.' + this.requiredIfVisibleClass).each(function(field) {
            self.toggleRequired(field, true);
        });
        fieldset.show();
    },
//    toggleConditionSource : function(field) {
//        conditionSourceLabel = $$('label[for='+ this.htmlIdPrefix + 'product_condition_use_attribute]')[0];
//        conditionSourceValidation = $('advice-required-entry-'+ this.htmlIdPrefix + 'product_condition_source_attribute_code');
//        conditionValidation = $('advice-required-entry-'+ this.htmlIdPrefix + 'product_condition');
//        conditionSource = $(this.htmlIdPrefix + 'product_condition_source_attribute_code');
//        condition = $(this.htmlIdPrefix + 'product_condition');
//
//        condition.disabled = field.checked;
//        this.toggleRequired(conditionSource, field.checked, conditionSourceLabel);
//        this.toggleRequired(condition, !field.checked);
//    },
    toggleWarrantyDescription: function (field) {

        for (i = 0; i < this.langs.length; i++) {
            warrantyDescription = $(this.htmlIdPrefix + 'product_warranty_description_' + this.langs[i]);
            warrantyDescriptionLabel = $$('label[for='+ this.htmlIdPrefix + 'product_warranty_description_' + this.langs[i] + ']')[0];

            required = (field.value == '0') ? 1 : 0;
            warrantyDescription.disabled = !required;
            this.toggleRequired(warrantyDescription, required, warrantyDescriptionLabel);
        }
    },
    toggleStartPrice: function (field, methodId) {
            if ($('rules_payment_methods_' + methodId).checked) {
                field.removeClassName('number-range-0.05-1000000');
                field.addClassName('number-range-0.05-2999.95');
            } else {
                field.addClassName('number-range-0.05-1000000');
                field.removeClassName('number-range-0.05-2999.95');
            }
    },
    toggleStockManagement: function (field) {
        if (field.value == 1) {
            $(this.htmlIdPrefix + 'stock_management').value = 1;
            $(this.htmlIdPrefix + 'stock_management_use_inventory0').checked = true;
            $(this.htmlIdPrefix + 'stock_management_use_inventory1').disable();
        } else {
            $(this.htmlIdPrefix + 'stock_management_use_inventory1').enable();

        }
    }
};

Ricento.RulesForm = Class.create (Ricento.salesOptionsForm, {
    initialize: function(htmlIdPrefix, packageSizes) {
        this.htmlIdPrefix = htmlIdPrefix;
        this.requiredText = '<span class="required">*</span>';
        this.requiredClass = 'required-entry';
        this.validationPassedClass = 'validation-passed';
        this.requiredIfVisibleClass = 'required-if-visible';
        this.packageSizes = packageSizes;
        this.langs = ['fr','de'];

        var self = this;
        Countable.live($(this.htmlIdPrefix + 'payment_description_de'), function (counter) {
            $(self.htmlIdPrefix + 'payment_description_de_result__all').update(counter.characters);
        });
        Countable.live($(this.htmlIdPrefix + 'payment_description_fr'), function (counter) {
            $(self.htmlIdPrefix + 'payment_description_fr_result__all').update(counter.characters);
        });
        Countable.live($(this.htmlIdPrefix + 'shipping_description_de'), function (counter) {
            $(self.htmlIdPrefix + 'shipping_description_de_result__all').update(counter.characters);
        });
        Countable.live($(this.htmlIdPrefix + 'shipping_description_fr'), function (counter) {
            $(self.htmlIdPrefix + 'shipping_description_fr_result__all').update(counter.characters);
        });
    },
    togglePaymentDescription: function (field) {
        for (i = 0; i < this.langs.length; i++) {
            paymentDescription = $(this.htmlIdPrefix + 'payment_description_' + this.langs[i]);
            paymentDescriptionLabel = $$('label[for='+ this.htmlIdPrefix + 'payment_description_' + this.langs[i] + ']')[0];

            required = field.checked;
            paymentDescription.disabled = !required;
            this.toggleRequired(paymentDescription, required, paymentDescriptionLabel);
        }
    },
    toggleShippingDescription: function (field) {
        for (i = 0; i < this.langs.length; i++) {
            shippingDescription = $(this.htmlIdPrefix + 'shipping_description_' + this.langs[i]);
            shippingDescriptionLabel = $$('label[for='+ this.htmlIdPrefix + 'shipping_description_'  + this.langs[i] + ']')[0];

            required = (field.value == '0') ? 1 : 0;
            shippingDescription.disabled = !required;
            this.toggleRequired(shippingDescription, required, shippingDescriptionLabel);
        }
    },
//    switchShippingPrice: function(field) {
//        shippingPrice = $('rules_shipping_price');
//        shippingPrice.value = '0.00';
//        shippingPrice.disabled = field.checked;
//    },
    initPackages: function(field, selected) {
        var deliveryId = field.value;
        var packages = JSON.parse(this.packageSizes);
        var package = packages[deliveryId];
        var select = $(this.htmlIdPrefix + 'shipping_package');
        select.removeClassName('hidden');

        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }

        if (package != undefined && package != null && package.length > 0) {
            for (i = 0; i < package.length; ++i) {
                var opt = document.createElement('option');

                if (selected != undefined && selected == package[i].PackageSizeId) {
                    opt.selected = true;
                }
                opt.value = package[i].PackageSizeId;
                opt.innerHTML = package[i].PackageSizeText;
                var attribute = document.createAttribute('data-package-cost');
                attribute.value = package[i].PackageSizeCost;
                opt.setAttributeNode(attribute);
                select.appendChild(opt);
                if (i == 0 && selected == undefined ) {
                    this._changePrice(package[i].PackageSizeCost);
                }
            }
        } else if (package > 0) {
            this._changePrice(package);
            select.addClassName('hidden');
        } else {
            this._changePrice(0);
            select.addClassName('hidden');
        }
    },
    setShippingFee: function(field) {
        selected = field.options[field.selectedIndex];
        this._changePrice(selected.getAttribute('data-package-cost'));
    },
    _changePrice: function(price) {
        $(this.htmlIdPrefix + 'shipping_price').value = (price > 0) ? Math.round(price*100)/100 : 0;
    }
});

Ricento.CategoryMappper = Class.create();
Ricento.CategoryMappper.prototype = {
    categoriesLoading : false,
    resizeAtLevel : 5,

    initialize: function(config) {
        Object.extend(this, config);
        var self = this;
        $(this.wrapperElement).select('a').each(function(item) {
            item.observe('click', self.onCategoryClick.bind(self));
        });
    },

    onCategoryClick: function(event) {
        var link = event.currentTarget;
        this.hideLevel(link.dataset.updatePrefix, link.dataset.updateLevel);
        if (link.dataset.isFinal) {
            this.chooseCategory(link);
        } else {
            this.loadChildren(link);
        }
        event.preventDefault();
    },

    hideLevel: function(prefix, levelToHide)
    {
        var elementToRemove;
        if (levelToHide < this.resizeAtLevel) {
            $(this.wrapperElement).removeClassName('ricardo_categories_resized');
        }
        while (elementToRemove = $(prefix + levelToHide)) {
            elementToRemove.remove();
            ++levelToHide;
        }
    },

    chooseCategory: function(link) {
        $(link.parentNode.parentNode).select('li').each(function(item) {
            $(item).removeClassName('selected');
        });
        $(link.parentNode).addClassName('selected');
        $(link.parentNode).select('input').each(function(input) {
            input.checked = true;
        });
        $('ricardo_category_selected_title').value = link.dataset.text;
        $('ricardo_categories_button_save')
            .enable()
            .removeClassName('disabled');
    },

    loadChildren: function(link) {
        if (this.categoriesLoading) {
            return;
        }
        $$("input[type=radio][name='ricardo_category_id']").each(function(input) { input.checked = false; });
        $('ricardo_categories_button_save')
            .disable()
            .addClassName('disabled');
        
        $(link.parentNode.parentNode).select('li').each(function(item) {
            $(item).removeClassName('selected');
        });
        $(link.parentNode).addClassName('selected');
        this.categoriesLoading = true;
        Element.show('loading-mask');
        setLoaderPosition();
        try {
            var self = this;
            new Ajax.Updater(
                'ricardo_children',
                this.loadChildrenUrl
                    .replace('#ID#', link.dataset.categoryId)
                    .replace('#LVL#', link.dataset.updateLevel),
                {
                    insertion: 'bottom',
                    onComplete: function() {
                        if (link.dataset.updateLevel >= self.resizeAtLevel) {
                            $(self.wrapperElement).addClassName('ricardo_categories_resized');
                        }
                        $$('#' + link.dataset.updatePrefix + link.dataset.updateLevel + ' a').each(function(item) {
                            item.observe('click', self.onCategoryClick.bind(self));
                        });
                        self.categoriesLoading = false;
                        Element.hide('loading-mask');
                    }
                }
            );
        } catch (e) {
            alert(e);
            this.categoriesLoading = false;
            Element.hide('loading-mask');
        }
    },
    submitForm: function (form) {
        formSerialized = form.serialize(true);
        Ricento.categoryMappingTargetInput.value = formSerialized['ricardo_category_id'];
        Ricento.categoryMappingTargetTitle.innerHTML = formSerialized['ricardo_category_selected_title'];
        Ricento.closePopup();
    }
};
Ricento.GeneralForm = Class.create();
Ricento.GeneralForm.prototype = {
    initialize: function(htmlIdPrefix) {
        this.htmlIdPrefix = htmlIdPrefix;
    },
    onChangeInput: function(source, languages) {
        var e = source;
        var t = $$('.lang_store_id');
        var self = this;

        $(this.htmlIdPrefix + 'default_language').disabled = !(e.value == 'all');

        languages.each(function(lang) {
            if (e.value == lang) {
                t.each(function(item) {
                    item.disabled = true;
                });
                $(self.htmlIdPrefix + 'lang_store_id_' + lang).disabled = false;
                self._showHideLangFields(lang, 'block');

            } else if (e.value == 'all'){
                self._showHideLangFields(lang, 'block');
            } else {
                self._showHideLangFields(lang, 'none');
            }
        });

        if (e.value == 'all') {
            t.each(function(item) {
                item.disabled = false;
            });
        }
    },
    _showHideLangFields: function(lang, displayStyle) {
        $$('label[for=rules_payment_description_' + lang + ']')[0].setStyle({'display': displayStyle});
        $('rules_payment_description_' + lang).setStyle({'display': displayStyle});
        $('note_payment_description_' + lang).setStyle({'display': displayStyle});

        $$('label[for=rules_shipping_description_' + lang + ']')[0].setStyle({'display': displayStyle});
        $('rules_shipping_description_' + lang).setStyle({'display': displayStyle});
        $('note_shipping_description_' + lang).setStyle({'display': displayStyle});

        $$('label[for=sales_options_product_warranty_description_' + lang + ']')[0].setStyle({'display': displayStyle});
        $('sales_options_product_warranty_description_' + lang).setStyle({'display': displayStyle});
        $('note_product_warranty_description_' + lang).setStyle({'display': displayStyle});

        if (displayStyle == 'none') {
            $('rules_payment_description_' + lang).removeClassName('required-entry');
            $('rules_shipping_description_' + lang).removeClassName('required-entry');
            $('sales_options_product_warranty_description_' + lang).removeClassName('required-entry');
        } else {
            $('rules_payment_description_' + lang).addClassName('required-entry');
            $('rules_shipping_description_' + lang).addClassName('required-entry');
            $('sales_options_product_warranty_description_' + lang).addClassName('required-entry');
        }
    }
};
