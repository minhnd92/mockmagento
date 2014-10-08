jQuery(document).ready(function(){
    jQuery('.sm-filter-item').click(function(evt){
        var url = jQuery(this).data('url');
        filter(evt,url);
    });

    jQuery('input.item-selected').attr('checked','checked');
});

function filter(evt,url){
    evt.preventDefault();
    jQuery('.col-left-first').css('opacity','0.5');
    jQuery('.category-products-container').css('opacity','0.5');
    jQuery('.loadingmask').css('display','block');
    jQuery.ajax({
        url : url,
        success : function(resp) {
            jQuery('.loadingmask').css('display','none');
            resp = jQuery.parseJSON(resp);

            jQuery('.col-left-first').html(resp.leftFirst);
            jQuery('.category-products-container').html(resp.productList);
            jQuery('.category-products-container').css('opacity','1');
            jQuery('.col-left-first').css('opacity','1');

            jQuery('.sm-filter-item').click(function(evt){
                var url = jQuery(this).data('url');
                filter(evt,url);
            });
            jQuery('input.item-selected').attr('checked','checked');
            history.pushState({},'',url);

        }
    });
//    setLocation(url);
}

