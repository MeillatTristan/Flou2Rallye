$(document).ready(() => {
    $('.remove-collection-item').each(function () {
        addRemoveEvent($(this));
    });

    $('.add-collection-item').click(function(e) {
        e.preventDefault();

        let prototype =  $(this).closest('.collection-list').data('prototype');


        prototype = prototype.replace(/__name__/g, $(this).closest('.collection-list').find('.collection-item').length);

        $(this).before(prototype);

        let max =  $(this).closest('.collection-list').data('max');
        let counter = $(this).closest('.collection-list').find('.collection-item').length;

        if (counter > 0) {
            $(this).addClass('mt-4');

        } else {
            $(this).removeClass('mt-4');
        }

        if (max && (counter >= max)) {
            $(this).attr('disabled', true);
            $(this).fadeOut();
        }

        addRemoveEvent($(this).closest('.collection-list').find('.remove-collection-item').last());

    });

    $('.collection-list').each(function( index ) {
        let max =  $(this).data('max');
        let counter = $(this).find('.collection-item').length;

        if (max && (counter >= max)) {
            $(this).find('.add-collection-item').attr('disabled', true);
            $(this).find('.add-collection-item').fadeOut();
        }

        if (counter > 0) {
            $(this).addClass('mt-4');
        } else {
            $(this).removeClass('mt-4');
        }
    });
});

const addRemoveEvent = (selector) => {
    selector.click(function(e) {
        e.preventDefault();

        let collection = $(this).closest('.collection-list');


        $(this).closest('.collection-item').remove();

        let max = collection.data('max');
        let counter = collection.find('.collection-item').length;

        if (max && (counter < max)) {
            collection.find('.add-collection-item').removeAttr('disabled');
            collection.find('.add-collection-item').fadeIn();
        }

        if (counter > 0) {
            collection.find('.add-collection-item').addClass('mt-4');
        } else {
            collection.find('.add-collection-item').removeClass('mt-4');
        }
    });
}
