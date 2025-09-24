//if (!window.console) console = {log: function() {}};

jQuery(document).ready(function ($) {
    /* ---------------------------------------------------------------- */
    /* Lightbox
     /* ---------------------------------------------------------------- */
    init_lightbox2();
  
    function init_lightbox2($target) {
        $('#mpcth_content a[href$=".jpg"],#mpcth_content a[href$=".jpeg"], #mpcth_content a[href$=".png"], #mpcth_content a[href$=".JPG"], #mpcth_content a[href$=".JPEG"]').magnificPopup({
            type: 'image',
            // key: 'mpcth-popup',
            removalDelay: 300,
            mainClass: 'mfp-fade mpcth-popup',
            image: {
                verticalFit: true
            },
            gallery: {
                enabled: true
            }
        });
    }
});

