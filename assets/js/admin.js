jQuery(document).on('click', function(e) {
    var $this = jQuery(e.target);
    var $form = $this.closest('.mac_options_form');

    if ($this.is('.mac-social-settings a')) {
      e.preventDefault();
      var $macsocial = $form.find('#mac-social-box');
        $macsocial.slideToggle();
    }
});