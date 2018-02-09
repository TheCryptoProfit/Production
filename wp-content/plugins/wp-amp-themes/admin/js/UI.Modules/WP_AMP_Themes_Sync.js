function WP_AMP_THEMES_SYNC() {

  var JSObject = this;

  this.type = 'wp_amp_themes_sync';

  this.DOMDoc;


  /**
   * Init method, called from WATJSInterface
   */
  this.init = function () {

    // save a reference to WATJSInterface Object
    WATJSInterface = window.parent.WATJSInterface;

    // add actions for syncing themes
    this.initSyncButton();
  };

  /**
   * Sync themes
   */
  this.initSyncButton = function () {

    jQuery('.' + JSObject.type + '_btn').click(
      function () {
        JSObject.syncThemes();
      }
    );
  };

  /**
   * Make Ajax call to sync themes
   */
  this.syncThemes = function () {

    WATJSInterface.Preloader.start({ message: 'Syncing themes ...' });

    jQuery.get(
      ajaxurl,
      {
        'action': 'wp_amp_themes_sync'
      },
      function (responseJSON) {

        // remove preloader
        WATJSInterface.Preloader.remove(100);

        var parsedJSON = JSON.parse(responseJSON);
        var response = Boolean(Number(String(parsedJSON.status)));

        WATJSInterface.Loader.display({ message: parsedJSON.message });

        setTimeout(function() {

          if (response) {
            location.reload();
          }
        }, 3000);
      }
    );
  };
}
