<?php

/**
 *  Class for checkbox controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Checkbox extends Zebra_Form_Control
{

    /**
     *  Constructor of the class
     *
     *  Adds an <input type="checkbox"> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // single checkbox
     *  $obj = $form->add('checkbox', 'my_checkbox', 'my_checkbox_value');
     *
     *  // multiple checkboxes
     *  // notice that is "checkboxes" instead of "checkbox"
     *  // checkboxes values will be "0", "1" and "2", respectively, and will be available in a custom template like
     *  // "mycheckbox_0", "mycheckbox_1" and "mycheckbox_2".
     *  // label controls will be automatically created having the names "label_mycheckbox_0", "label_mycheckbox_1" and
     *  // "label_mycheckbox_2" (label + underscore + control name + underscore + value with anything else other than
     *  // letters and numbers replaced with an underscore)
     *  // $obj is a reference to the first checkbox
     *  $obj = $form->add('checkboxes', 'mycheckbox',
     *      array(
     *          'Value 1',
     *          'Value 2',
     *          'Value 3'
     *      )
     *  );
     *
     *  // multiple checkboxes with specific indexes
     *  // checkboxes values will be "v1", "v2" and "v3", respectively, and will be available in a custom template like
     *  // "mycheckbox_v1", "mycheckbox_v2" and "mycheckbox_v3".
     *  // label controls will be automatically created having the names "label_mycheckbox_v1", "label_mycheckbox_v2" and
     *  // "label_mycheckbox_v3" (label + underscore + control name + underscore + value with anything else other than
     *  // letters and numbers replaced with an underscore)
     *  $obj = $form->add('checkboxes', 'mycheckbox',
     *      array(
     *          'v1' => 'Value 1',
     *          'v2' => 'Value 2',
     *          'v3' => 'Value 3'
     *      )
     *  );
     *
     *  // multiple checkboxes with preselected value
     *  // "Value 2" will be the preselected value
     *  // note that for preselecting values you must use the actual indexes of the values, if available, (like
     *  // in the current example) or the default, zero-based index, otherwise (like in the next example)
     *  $obj = $form->add('checkboxes', 'mycheckbox',
     *      array(
     *          'v1'    =>  'Value 1',
     *          'v2'    =>  'Value 2',
     *          'v3'    =>  'Value 3'
     *      ),
     *      'v2'    // note the index!
     *  );
     *
     *  // "Value 2" will be the preselected value.
     *  // note that for preselecting values you must use the actual indexes of the values, if available, (like
     *  // in the example above) or the default, zero-based index, otherwise (like in the current example)
     *  $obj = $form->add('checkboxes', 'mycheckbox',
     *      array(
     *          'Value 1',
     *          'Value 2',
     *          'Value 3'
     *      ),
     *      1    // note the index!
     *  );
     *
     *  // multiple checkboxes with multiple preselected values
     *  $obj = $form->add('checkboxes', 'mycheckbox[]',
     *      array(
     *          'v1'    =>  'Value 1',
     *          'v2'    =>  'Value 2',
     *          'v3'    =>  'Value 3'
     *      ),
     *      array('v1', 'v2')
     *  );
     *
     *  // custom classes (or other attributes) can also be added to all of the elements by specifying a 4th argument;
     *  // this needs to be specified in the same way as you would by calling {@link set_attributes()} method:
     *  $obj = $form->add('checkboxes', 'mycheckbox[]',
     *      array(
     *          '1' =>  'Value 1',
     *          '2' =>  'Value 2',
     *          '3' =>  'Value 3',
     *      ),
     *      '', // no default value
     *      array('class' => 'my_custom_class')
     *  );
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
     *  <samp>By default, for checkboxes, radio buttons and select boxes, the library will prevent the submission of other
     *  values than those declared when creating the form, by triggering the error: "SPAM attempt detected!". Therefore,
     *  if you plan on adding/removing values dynamically, from JavaScript, you will have to call the
     *  {@link Zebra_Form_Control::disable_spam_filter() disable_spam_filter()} method to prevent that from happening!</samp>
     *
     *  @param  string  $id             Unique name to identify the control in the form.
     *
     *                                  <b>$id needs to be suffixed with square brackets if there are more checkboxes
     *                                  sharing the same name, so that PHP treats them as an array!</b>
     *
     *                                  The control's <b>name</b> attribute will be as indicated by <i>$id</i>
     *                                  argument while the control's <b>id</b> attribute will be <i>$id</i>, stripped of
     *                                  square brackets (if any), followed by an underscore and followed by <i>$value</i>
     *                                  with all the spaces replaced by <i>underscores</i>.
     *
     *                                  So, if the <i>$id</i> arguments is "my_checkbox" and the <i>$value</i> argument
     *                                  is "value 1", the control's <b>id</b> attribute will be <b>my_checkbox_value_1</b>.
     *
     *                                  This is the name to be used when referring to the control's value in the
     *                                  POST/GET superglobals, after the form is submitted.
     *
     *                                  This is also the name of the variable to be used in custom template files, in
     *                                  order to display the control.
     *
     *                                  <code>
     *                                  // in a template file, in order to print the generated HTML
     *                                  // for a control named "my_checkbox" and having the value of "value 1",
     *                                  // one would use:
     *                                  echo $my_checkbox_value_1;
     *                                  </code>
     *
     *                                  <i>Note that when adding the required rule to a group of checkboxes (checkboxes
     *                                  sharing the same name), it is sufficient to add the rule to the first checkbox!</i>
     *
     *  @param  mixed   $value          Value of the checkbox.
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.4 input}
     *                                  controls (disabled, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "checked" attribute
     *                                  $obj = $form->add(
     *                                      'checkbox',
     *                                      'my_checkbox',
     *                                      'v1',
     *                                      array(
     *                                          'checked' => 'checked'
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
     *                                  <b>type</b>, <b>id</b>, <b>name</b>, <b>value</b>, <b>class</b>
     *
     *  @return void
     */
    function __construct($id, $value, $attributes = '')
    {
    
        // call the constructor of the parent class
        parent::__construct();
    
        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_spam_filter',
            'disable_xss_filters',
            'locked',

        );

        // set the default attributes for the checkbox control
        // put them in the order you'd like them rendered
        $this->set_attributes(
        
            array(
            
                'type'  =>  'checkbox',
                'name'  =>  $id,
                'id'    =>  str_replace(array(' ', '[', ']'), array('_', ''), $id) . '_' . str_replace(' ', '_', $value),
                'value' =>  $value,
                'class' =>  'control checkbox',

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
    
        return '<input ' . $this->_render_attributes() . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '>';

    }

}

?>
