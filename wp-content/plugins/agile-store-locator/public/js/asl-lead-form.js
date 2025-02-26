jQuery( document ).ready(function() {

  (function($) {
    
    var lead_container = document.querySelector('.asl-lead-cont');

    //  Main lead_container is missing!
    if(!lead_container) {
      return;
    }

  
  
    // fetch all the forms we want to apply custom style
    var lead_form   = lead_container.querySelector('.asl-lead-form');

    //  Register button
    var $lead_btn   = $(lead_form.querySelector('#sl-lead-save'));
      


    var defaultConfig = {
      // class of the parent element where the error/success class is added
      classTo: 'sl-form-group',
      errorClass: 'has-danger',
      successClass: 'has-success',
      // class of the parent element where error text element is appended
      errorTextParent: 'sl-form-group',
      // type of element to create for the error text
      errorTextTag: 'div',
      // class of the error text element
      errorTextClass: 'text-help'
    };


    //  Add the validation
    var pristine_form = new Pristine(lead_form, defaultConfig, false);


    //  Click Event of the save button
    $lead_btn.bind('click', function(e) {

      
      var form_data = $(lead_form).ASLSerializeObject(),
          is_valid  = true;

      //  Validate the field
      if(!pristine_form.validate()) {
        return;
      }

      $lead_btn.bootButton('loading');

      //  Add the nounce
      $.ajax({
        url: ASL_FORM.ajax_url,
        data: {action: 'asl_lead_request', form_params: form_data, vkey: ASL_FORM.vkey, config: {radius: asl_lead_configuration.radius, country: asl_lead_configuration.country}},
        type: 'POST',
        dataType: 'json',
        /**
         * [success description]
         * @param  {[type]} _data [description]
         * @return {[type]}       [description]
         */
        success: function(_response) {

          //  Reset the button
          $lead_btn.bootButton('reset');

          // Create alert
          asl_notify(_response.message, _response.success);

          //  Reset it
          if(_response.success) {
            lead_form.reset();
          }

          //  When there is a redirect URL
          if(_response.success && asl_lead_configuration.redirect) {
            window.location = asl_lead_configuration.redirect;
          }
        },
        /**
         * [error description]
         * @param  {[type]} _data [description]
         * @return {[type]}       [description]
         */
        error: function(_data) {}
      });
      
    });

  })(jQuery);
});
