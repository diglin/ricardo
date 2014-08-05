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

Ricento.salesOptionsForm = Class.create();
Ricento.salesOptionsForm.prototype = {
    initialize : function(htmlIdPrefix) {
        this.htmlIdPrefix = htmlIdPrefix;
        this.requiredText = '<span class="required">*</span>';
        this.requiredClass = 'required-entry';
        this.validationPassedClass = 'validation-passed';
        this.requiredIfVisibleClass = 'required-if-visible';

        this.showSalesTypeFieldsets($(this.htmlIdPrefix + "sales_type").value, $(this.htmlIdPrefix + "sales_auction_direct_buy").value == "1");
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
    showSalesTypeFieldsets : function(salesType, allowDirectBuy) {
        $$('div[id^=fieldset_toggle_]').each(this._hideFieldset.bind(this));
        this._showFieldset($('fieldset_toggle_' + salesType));
        if (allowDirectBuy) {
            this._showFieldset($('fieldset_toggle_buynow'));
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
    toggleConditionSource : function(field) {
        conditionSourceLabel = $$('label[for='+ this.htmlIdPrefix + 'product_condition_use_attribute]')[0];
        conditionSourceValidation = $('advice-required-entry-'+ this.htmlIdPrefix + 'product_condition_source_attribute_code');
        conditionValidation = $('advice-required-entry-'+ this.htmlIdPrefix + 'product_condition');
        conditionSource = $(this.htmlIdPrefix + 'product_condition_source_attribute_code');
        condition = $(this.htmlIdPrefix + 'product_condition');

        condition.disabled = field.checked;
        this.toggleRequired(conditionSource, field.checked, conditionSourceLabel);
        this.toggleRequired(condition, !field.checked);

    }
};

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
        $('ricardo_categories_button_save').enable();
        $('ricardo_categories_button_save').removeClassName('disabled');
    },

    loadChildren: function(link) {
        if (this.categoriesLoading) {
            return;
        }
        $$("input[type=radio][name='ricardo_category_id']").each(function(input) { input.checked = false; });
        $('ricardo_categories_button_save').disable();
        $('ricardo_categories_button_save').addClassName('disabled');
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