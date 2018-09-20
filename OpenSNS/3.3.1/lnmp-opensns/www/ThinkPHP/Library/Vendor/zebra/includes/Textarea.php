<?php

/**
 *  Class for textarea controls
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Textarea extends Zebra_Form_Control
{

    /**
     *  Adds an <textarea> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a textarea control to the form
     *  $obj = $form->add('textarea', 'my_textarea');
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
     *                                  The control's <b>name</b> attribute will be the same as the <b>id</b> attribute!
     *
     *                                  This is the name to be used when referring to the control's value in the
     *                                  POST/GET superglobals, after the form is submitted.
     *
     *                                  This is also the name of the variable to be used in custom template files, in
     *                                  order to display the control.
     *
     *                                  <code>
     *                                  // in a template file, in order to print the generated HTML
     *                                  // for a control named "my_textarea", one would use:
     *                                  echo $my_textarea;
     *                                  </code>
     *
     *  @param  string  $default        (Optional) Default value of the textarea.
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  <b>{@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.7 textarea}</b>
     *                                  controls (rows, cols, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "rows" attribute
     *                                  $obj = $form->add(
     *                                      'textarea',
     *                                      'my_textarea',
     *                                      '',
     *                                      array(
     *                                          'rows' => 10
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  See {@link Zebra_Form_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *                                  The following attributes are automatically set when the control is created and
     *                                  should not be altered manually:<br>
     *
     *                                  <b>id</b>, <b>name</b>, <b>class</b>
     *
     *  @return void
     */
    function __construct($id, $default = '', $attributes = '')
    {
    
        // call the constructor of the parent class
        parent::__construct();
    
        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes =  array(

            'default_value',
            'disable_xss_filters',
            'locked',
            'type',
            'value',

		);

        // set the default attributes for the textarea control
        // put them in the order you'd like them rendered
        $this->set_attributes(

            array(

                'name'      =>  $id,
                'id'        =>  $id,
                'rows'      =>  5,
                'cols'      =>  '80',           // used only for passing W3C validation
                'class'     =>  'control',
                'type'      =>  'textarea',
                'value'     =>  $default,

            )

        );
        
        // if "class" is amongst user specified attributes
        if (is_array($attributes) && isset($attributes['class'])) {

            // we need to set the "class" attribute like this, so it doesn't overwrite previous values
            $this->set_attributes(array('class' => $attributes['class']), false);

            // make sure we don't set it again below
            unset($attributes['class']);

        }

        // sets user specified attributes for the control
        $this->set_attributes($attributes);
        
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

        // get private attributes
        $attributes = $this->get_attributes('value');

        return '<textarea ' . $this->_render_attributes() . '>' . (isset($attributes['value']) ? $attributes['value'] : '') . '</textarea>';

    }

}

?>
