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
Ricento.categoryMappingPopup = function(url, target) {
    if ($('ricento_popup') && typeof(Windows) != 'undefined') {
        Windows.focus('ricento_popup');
        return;
    }

    Dialog.info({url:url.replace('#ID#', target.getValue())}, {
        closable:true,
        resizable:true,
        maximizable: true,
        draggable:true,
        className:'magento',
        windowClassName:'popup-window',
        title: Translator.translate('Choose Ricardo Category'),
        top:50,
        width:900,
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
    });
    Ricento.categoryMappingTargetInput = target;
};
Ricento.closePopup = function() {
    Windows.close('ricento_popup');
};

Ricento.toggleSalesTypeFieldset = function(value) {
    $$('div[id^=fieldset_toggle_]').each(Element.hide);
    $$('#fieldset_toggle_' + value).each(Element.show);
}

Ricento.CategoryMappper = Class.create();
Ricento.CategoryMappper.prototype = {
    categoriesLoading : false,

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
        Event.stop(event);
    },

    hideLevel: function(prefix, levelToHide)
    {
        var elementToRemove;
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
    }
};