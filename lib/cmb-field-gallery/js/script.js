(function ($) {
  $('.pw-gallery').each(function() {
    var instance = this;

    $('input.manage-gallery', instance).click(function() {
      var gallerysc = '[gallery ids="' + $('input[type=hidden]', instance).val() + '"]';
      wp.media.gallery.edit(gallerysc).on('update', function(g) {
        var id_array = [];
        $.each(g.models, function(id, img) { id_array.push(img.id); });
        $('input[type=hidden]', instance).val(id_array.join(","));

        if( id_array.length > 0 ) {
          $('input.clear-gallery', instance).removeClass('hidden');
        }
      });
    });

    $('input.clear-gallery', instance).click(function() {
      var gallerysc = '[gallery ids="' + $('input[type=hidden]', instance).val() + '"]';
      $('input[type=hidden]', instance).val('');
      $('input.clear-gallery', instance).addClass('hidden');
    });
  });
}(jQuery));
