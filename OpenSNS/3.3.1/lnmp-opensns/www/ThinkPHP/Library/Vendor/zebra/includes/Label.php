<?php

/**
 *  Class for labels
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Label extends Zebra_Form_Control
{

    /**
     *  Add an <label> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a label, attached to a textbox control
     *  $form->add('label', 'label_my_text', 'my_text', 'Enter some text:');
     *
     *  // add a text control to the form
     *  $obj = $form->add('text', 'my_text');
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
     *                                  // for a control named "my_label", one would use:
     *                                  echo $my_label;
     *                                  </code>
     *
     *  @param  string  $attach_to      The <b>id</b> attribute of the control to attach the note to.
     *
     *                                  <i>Notice that this must be the "id" attribute of the control you are attaching
     *                                  the label to, and not the "name" attribute!</i>
     *
     *                                  This is important as while most of the controls have their <b>id</b> attribute
     *                                  set to the same value as their <b>name</b> attribute, for {@link Zebra_Form_Checkbox checkboxes},
     *                                  {@link Zebra_Form_Select selects} and {@link Zebra_Form_Radio radio&nbsp;buttons} this
     *                                  is different.
     *
     *                                  <b>Exception to the rule:</b>
     *
     *                                  Just like in the case of {@link Zebra_Form_Note notes}, if you want a <b>master</b>
     *                                  label, a label that is attached to a <b>group</b> of checkboxes/radio buttons
     *                                  rather than individual controls, this attribute must instead refer to the <b>name</b>
     *                                  of the controls (which, for groups of checkboxes/radio buttons, is one and the same).
     *                                  This is important because if the group of checkboxes/radio buttons have the
     *                                  <i>required</i> rule set, this is the only way in which the "required" symbol
     *                                  (the red asterisk) will be attached to the master label instead of being attached
     *                                  to the first checkbox/radio button from the group.
     *
     *  @param  mixed   $caption        Caption of the label.
     *
     *                                  <i>Putting a $ (dollar) sign before a character will turn that specific character into
     *                                  the accesskey.</i><br>
     *                                  <i>If you need the dollar sign in the label, escape it with</i> \ <i>(backslash)</i>
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#edef-LABEL label}
     *                                  elements (style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "style" attribute
     *                                  $obj = $form->add(
     *                                      'label',
     *                                      'label_my_text',
     *                                      'my_text',
     *                                      'My Label:'
     *                                      array(
     *                                          'style' => 'font-weight: normal'
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  <b>Special attribute:</b>
     *
     *                                  When setting the special attribute <b>inside</b> to <b>true</b>, the label will
     *                                  appear inside the control is attached to (if the control the label is attached to
     *                                  is a {@link Zebra_Form_Text textbox} or a {@link Zebra_Form_Textarea textarea}) and
     *                                  will disappear when the control will receive focus. When the "inside" attribute is
     *                                  set to TRUE, the label will not be available in the template file as it will be
     *                                  contained by the control the label is attached to!
     *
     *                                  <code>
     *                                  $form->add('label', 'my_label', 'my_control', 'My Label:', array('inside' => true));
     *                                  </code>
     *
     *                                  <samp>Sometimes, when using floats, the inside-labels will not be correctly positioned
     *                                  as jQuery will return invalid numbers for the parent element's position; If this is
     *                                  the case, make sure you enclose the form in a div with position:relative to fix
     *                                  this issue.</samp>
     *
     *                                  See {@link Zebra_Form_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *                                  The following attributes are automatically set when the control is created and
     *                                  should not be altered manually:<br>
     *                                  <b>id</b>, <b>for</b>
     *
     *  @return void
     */
    function __construct($id, $attach_to, $caption, $attributes = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        $this->private_attributes = array(

            'disable_xss_filters',
            'for_group',
            'inside',
            'label',
            'locked',
            'name',
            'type',

        );

        // set the default attributes for the label
        $this->set_attributes(

			array(

                'for'   =>  $attach_to,
			    'id'    =>  $id,
                'label' =>  $caption,
                'name'  =>  $id,
                'type'  =>  'label',

			)

		);

        // sets user specified attributes for the table cell
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
        $attributes = $this->get_attributes('label');
        
        // if access key needs to be showed
        if (preg_match('/\$(.{1})/', $attributes['label'], $matches) > 0) {
        
            // set the requested accesskey
            $this->set_attributes(array('accesskey' => strtolower($matches[1])));
            
            // make the accesskey visible
            $attributes['label'] = preg_replace('/\$(.{1})/', '<span class="underline">$1</span>', $attributes['label']);

        }

        return '<label ' . $this->_render_attributes() . '>' . $attributes['label'] . '</label>';

    }

}

?>
