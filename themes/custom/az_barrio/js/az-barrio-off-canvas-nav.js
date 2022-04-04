(function ($, Drupal) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      $('#navbarOffcanvasDemo').on('opened.az.offcanvasmenu', function (e) {
	      if ($(e.target.ownerDocument.activeElement).attr('id') === 'jsAzSearch') {
          $('#block-az-barrio-offcanvas-searchform input').trigger('focus');
	      }
      });

      $('#headerOffcanvas>section').removeClass('col-md');
      $('#headerOffcanvas ul').removeClass().addClass('clearfix navbar-nav');
      $('#headerOffcanvas .nav-item>a').removeClass('text-muted');
    }
  };
})(jQuery, Drupal);
