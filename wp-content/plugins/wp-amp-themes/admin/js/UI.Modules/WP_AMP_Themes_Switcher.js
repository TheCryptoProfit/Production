function WP_AMP_THEMES_SWITCHER() {

  var JSObject = this;

  this.type = 'wp_amp_themes_switcher';

  this.DOMDoc;


  /**
   * Init method, called from WATJSInterface
   */
  this.init = function () {

    // save a reference to WATJSInterface Object
    WATJSInterface = window.parent.WATJSInterface;

    // add actions for selecting a theme
    this.initSelectButtons();
  };

  /**
   * Select themes
   */
  this.initSelectButtons = function () {

    jQuery('.' + JSObject.type + '_select').click(

      function () {

        var newTheme = jQuery(this).attr('data-theme');

        var isConfirmed = confirm('If you change your theme you will lose all the customization options of your current theme. Are you sure you want to continue?');

        if (isConfirmed) {
          JSObject.switchTheme(newTheme);
        }
      }
    );
  };

  /**
   * Make Ajax call to change the theme
   */
  this.switchTheme = function (newTheme) {

    WATJSInterface.Preloader.start({ message: 'Switching theme ...' });

    jQuery.get(
      ajaxurl,
      {
        'action': 'wp_amp_themes_switch_theme',
        'theme': newTheme
      },
      function (response) {

        // remove preloader
        WATJSInterface.Preloader.remove(100);

        response = Boolean(Number(String(response)));

        if (response) {

          var message = 'Your AMP theme has been successfully changed.';
          WATJSInterface.Loader.display({ message: message });

          setTimeout(function(){ location.reload(); }, 3000);

        } else {

          var message = 'There was an error. Please reload the page and try again.';
          WATJSInterface.Loader.display({ message: message });
        }
      }
    );
  };
}
