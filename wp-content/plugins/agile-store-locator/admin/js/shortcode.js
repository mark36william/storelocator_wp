var asl_engine = window['asl_engine'] || {};

(function($, app_engine) {
  'use strict';

    /**
     * [shortcode_generator description]
     * @return {[type]} [description]
     */
    app_engine['shortcode_generator'] = function() {

      // Generate Shortcode
      $('#sl-add-shortcode').on('click',function(){

        var $form = $('.smodal-body').find('#sl-shortcode-popup');
        var formData = $form.ASLSerializeObject();


          var shortcode_attrs = [],
              attributes      = '',
              shortcode       = '[ASL_STORELOCATOR]';

          for (var key in formData) {
            shortcode_attrs.push(key + '="' + formData[key]+'"') ;
          }

          attributes = shortcode_attrs.join(' ');

          if(attributes != '' && attributes != null){

              shortcode = '[ASL_STORELOCATOR '+attributes+']';

            if ($('.sl_shortcode_area')[0]) {
              
              window.asl_gutenberg_attrs.setAttributes({shortcode:  shortcode});

            }else{
              var prev_content = tmce_getContent('content');
              tmce_setContent(prev_content+shortcode);
              tmce_focus('content');
            }
          }

          setTimeout(function() { 
            $("[data-dismiss=smodal]").trigger({ type: "click" });
          }, 200);

      });


      // get tmce content 
      function tmce_getContent(editor_id, textarea_id) {
        if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
        if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
        
        if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
          return tinyMCE.get(editor_id).getContent();
        }else{
          return jQuery('#'+textarea_id).val();
        }
      }

      // set tmce content
      function tmce_setContent(content, editor_id, textarea_id) {
        if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
        if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
        
        if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
          return tinyMCE.get(editor_id).setContent(content);
        }else{
          return jQuery('#'+textarea_id).val(content);
        }
      }

      // Focus on tmce
      function tmce_focus(editor_id, textarea_id) {
        if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
        if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
        
        if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
          return tinyMCE.get(editor_id).focus();
        }else{
          return jQuery('#'+textarea_id).focus();
        }
      }
    };


})(jQuery, asl_engine);