<?php

/**
 *  Class for CAPTCHA controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */

class Zebra_Form_Captcha extends Zebra_Form_Control
{

    /**
     *  Adds a CAPTCHA image to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <b>You must also place a {@link Zebra_Form_Text textbox} control on the form and set the "captcha" rule to it!
     *  (through {@link set_rule()})</b>
     *
     *  Properties of the CAPTCHA image can be altered by editing the file includes/captcha.php.
     *
     *  By default, captcha values are triple md5 hashed and stored in cookies, and when the user enters the captcha
     *  value the value is also triple md5 hashed and the two values are then compared. Sometimes, your users may have
     *  a very restrictive cookie policy and so cookies will not be set, and therefore they will never be able to get
     *  past the CAPTCHA control. If it's the case, call the {@link Zebra_Form::captcha_storage() captcha_storage}
     *  method and set the storage method to "session".
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a CAPTCHA image
     *  $form->add('captcha', 'my_captcha', 'my_text');
     *
     *  // add a label for the textbox
     *  $form->add('label', 'label_my_text', 'my_text', 'Are you human?');
     *
     *  // add a CAPTCHA to the form
     *  $obj = $form->add('text', 'my_text');
     *
     *  // set the "captcha" rule to the textbox
     *  $obj->set_rule(array(
     *      'captcha' => array('error', 'Characters not entered correctly!')
     *  ));
     *
     *  // don't forget to always call this method before rendering the form
     *  if ($form->validate()) {
     *      // put code here
     *  }
     *
     *  // output the form using an automatically generated template
     *  $form->render();
     *  </code>
     *
     *  @param  string  $id             Unique name to identify the control in the form.
     *
     *                                  This is the name of the variable to be used in the template file, containing
     *                                  the generated HTML for the control.
     *
     *                                  <code>
     *                                  // in a template file, in order to print the generated HTML
     *                                  // for a control named "my_captcha", one would use:
     *                                  echo $my_captcha;
     *                                  </code>
     *
     *  @param  string  $attach_to      The <b>id</b> attribute of the {@link Zebra_Form_Text textbox} control to attach
     *                                  the CAPTCHA image to.
     *
     *  @return void
     */
    function __construct($id, $attach_to, $storage = 'cookie')
    {

        // call the constructor of the parent class
        parent::__construct();
        
        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_xss_filters',
            'for',
            'locked',

        );

        // set the default attributes for the text control
        // put them in the order you'd like them rendered
        $this->set_attributes(
        
            array(
            
                'type'      =>  'captcha',
                'name'      =>  $id,
                'id'        =>  $id,
                'for'       =>  $attach_to,

            )
            
        );

    }

    /**
     *  Generates the control's HTML code.
     *
     *  <i>This method is automatically called by the {@link Zebra_Form::render() render()} method!</i>
     *
     *  @return string  The control's HTML code
     */
    function toHTML()
    {

        return '<div class="captcha-container"><img src="' . $this->form_properties['assets_url'] . 'process.php?captcha=' . ($this->form_properties['captcha_storage'] == 'session' ? 2 : 1) . '&amp;nocache=' . time() . '" alt=""' . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '><a href="javascript:void(0)" title="' . $this->form_properties['language']['new_captcha'] . '">' . $this->form_properties['language']['new_captcha'] . '</a></div>';
    
    }
    
}

?>
