jQuery(document).ready(function () {
    jQuery('.search-block-form input.form-search').addClass('form-control');
    jQuery('.search-block-form input.form-search').attr('placeholder', 'Search...');
    jQuery('.simplenews-subscriber-form input[type="email"]').attr('placeholder', 'Email Address');
    jQuery('.google-map').click(function () {
        jQuery('.google-map iframe').css("pointer-events", "auto");
    });
    jQuery(".google-map").mouseleave(function () {
        jQuery('.google-map iframe').css("pointer-events", "none");
    });
    jQuery('#mainNav > li.dropdown > a').append('<i class="fa fa-caret-down"></i>');

    jQuery('#mainNav > li.dropdown > a .fa-caret-down').on('click', function (e) {
        e.preventDefault();
        if (jQuery(window).width() < 992) {
            jQuery(this).closest('li').toggleClass('opened');

        }
    });
    jQuery('.webform-submission-contact-advanced-form input[type="submit"]').val('Send Message');
    jQuery('.webform-submission-contact-footer-form input[type="submit"]').val('Send Message');
    jQuery('.messages').append('<i class="icon-cancel message-close fa fa-close"></i>');
    jQuery('body').on('click', '.icon-cancel.message-close', function () {
        jQuery(this).parent().animate({
            'opacity': '0'
        }, function () {
            jQuery(this).slideUp();
        });
    });
    //Validate
    jQuery('#webform-submission-contact-form').validate();
    jQuery('#webform-submission-contact-advanced-form').validate();
    jQuery('#webform-submission-contact-footer-form').validate();
    jQuery('.block-simplenews-subscription-block form').validate();
    jQuery('#webform-submission-contact-form-sidebar-form').validate();
    //Scrool
    var sections = jQuery('section.section');
    jQuery(window).on('scroll', function () {
        var cur_pos = jQuery(this).scrollTop();
        sections.each(function () {


        });
    });
});


var hideTwitterAttempts = 0;
function hideTwitterBoxElements() {
    setTimeout(function () {
        jQuery("iframe").contents().find(".timeline-Tweet-text").attr("style", "font-size:12px !important;color:#777 !important;");
        jQuery("footer.color iframe").contents().find(".timeline-Tweet-text").attr("style", "color:#fff !important;");
        jQuery("footer iframe").contents().find(".TweetAuthor-name").attr("style", "color:#fff !important;");
        //Increase attempt count
        hideTwitterAttempts++;
        //Attempts to style widget 3 times, at 1.5s increments
        //Basically ensures that it gets styled
        if (hideTwitterAttempts < 3) {
            hideTwitterBoxElements();
        }

    }, 1500);
}
//Trigger Styling
hideTwitterBoxElements();

// makes sure the whole site is loaded
jQuery(document).ready(function () {
    jQuery(".status").fadeOut();
    jQuery(".preloader").delay(1000).fadeOut("slow");
});

// add to cart in product list
function addToCartInList(id) {
    if (jQuery('#commerce-order-item-default-add-to-cart-' + id + '-form').length) {
        jQuery('#commerce-order-item-default-add-to-cart-' + id + '-form').submit();
    }
}

// portfolio load more
jQuery(document).ready(function () {
    jQuery('#portfolioShowNumber').val('4');

    jQuery('ul#portfolioLoadMoreWrapper li').addClass('hidden');
    jQuery('ul#portfolioLoadMoreWrapper li:nth-child(1)').removeClass('hidden');
    jQuery('ul#portfolioLoadMoreWrapper li:nth-child(2)').removeClass('hidden');
    jQuery('ul#portfolioLoadMoreWrapper li:nth-child(3)').removeClass('hidden');
    jQuery('ul#portfolioLoadMoreWrapper li:nth-child(4)').removeClass('hidden');
});

jQuery('#portfolioLoadMore').click(function () {
    portfolioLoadMore();
});

jQuery('.btn-portfolio-infinite-scroll').appear().on('appear', function () {
    jQuery('#portfolioLoadMore').trigger('click');
});

function portfolioLoadMore() {
    jQuery('.btn-ajax-more').hide();
    jQuery('#portfolioLoadMoreLoader').show();

    setTimeout(function () {
        var portfolio_show_number = parseInt(jQuery('#portfolioShowNumber').val());
        var portfolio_show_number_after = portfolio_show_number + 4;

        for (var i = portfolio_show_number; i < portfolio_show_number_after; i++) {
            var item_number = i + 1;

            if (jQuery('ul#portfolioLoadMoreWrapper li:nth-child(' + item_number + ')').length) {
                jQuery('ul#portfolioLoadMoreWrapper li:nth-child(' + item_number + ')').removeClass('hidden');
                if (jQuery('ul#portfolioLoadMoreWrapper li:nth-child(' + item_number + ')').css('opacity') === '0') {
                    jQuery('ul#portfolioLoadMoreWrapper li:nth-child(' + item_number + ')').css('display', 'none');
                }
            }
        }

        jQuery('#portfolioLoadMoreWrapper').isotope();
        jQuery('#portfolioShowNumber').val(portfolio_show_number_after);

        var item_number_after = portfolio_show_number_after + 1;
        if (jQuery('ul#portfolioLoadMoreWrapper li:nth-child(' + item_number_after + ')').length) {
            jQuery('.btn-ajax-more').show().blur();
        }

        jQuery('#portfolioLoadMoreLoader').hide();
    }, 1000);
}

/* BLOG LOAD MORE */
jQuery(document).ready(function () {
    jQuery('#blogShowNumber').val('4');

    jQuery('#blogLoadMoreWrapper article').addClass('hidden');
    jQuery('#blogLoadMoreWrapper article:nth-child(1)').removeClass('hidden');
    jQuery('#blogLoadMoreWrapper article:nth-child(2)').removeClass('hidden');
    jQuery('#blogLoadMoreWrapper article:nth-child(3)').removeClass('hidden');
    jQuery('#blogLoadMoreWrapper article:nth-child(4)').removeClass('hidden');
});

jQuery('#blogLoadMore').click(function () {
    blogLoadMore();
});

function blogLoadMore() {
    jQuery('#blogLoadMore').hide();
    jQuery('#blogLoadMoreLoader').show();

    setTimeout(function () {
        var blog_show_number = parseInt(jQuery('#blogShowNumber').val());
        var blog_show_number_after = blog_show_number + 3;

        for (var i = blog_show_number; i < blog_show_number_after; i++) {
            var item_number = i + 1;

            if (jQuery('#blogLoadMoreWrapper article:nth-child(' + item_number + ')').length) {
                jQuery('#blogLoadMoreWrapper article:nth-child(' + item_number + ')').removeClass('hidden');
            }
        }

        jQuery('#blogShowNumber').val(blog_show_number_after);

        var item_number_after = blog_show_number_after + 1;
        if (jQuery('#blogLoadMoreWrapper article:nth-child(' + item_number_after + ')').length) {
            jQuery('#blogLoadMore').show().blur();
        }

        jQuery('#blogLoadMoreLoader').hide();
    }, 1000);
}

/* COMMENT USER IMAGE */
jQuery(document).ready(function () {
    var sample_image = '<img src="http://placehold.it/85x85" alt="" class="img-responsive"/>';
    jQuery('ul.comments li .img-thumbnail article').each(function () {
        if (jQuery(this).find('img').length === 0) {
            jQuery(this).append(sample_image);
        }
    });
    var ms_ie = false;
    var ua = window.navigator.userAgent;
    var old_ie = ua.indexOf('MSIE');
    var new_ie = ua.indexOf('Trident/');
    if ((old_ie > -1) || (new_ie > -1)) {
        ms_ie = true;
    }
    if (ms_ie) {
        jQuery('body').addClass('ie');
    }

});