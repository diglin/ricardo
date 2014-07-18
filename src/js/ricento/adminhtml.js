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
        title:'Add Product', //TODO translate
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
        title:'Create Product Listing', //TODO translate
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
Ricento.closePopup = function() {
    Windows.close('ricento_popup');
};

Ricento.toggleSalesTypeFieldset = function(value) {
    $$('div[id^=fieldset_toggle_]').each(Element.hide);
    $$('#fieldset_toggle_' + value).each(Element.show);
}