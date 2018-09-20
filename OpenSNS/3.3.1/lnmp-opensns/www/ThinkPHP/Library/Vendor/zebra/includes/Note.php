<?php

/**
 *  Class for notes attached to controls
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Note extends Zebra_Form_Control
{

    /**
     *  Adds a "note" to the form, attached to a control.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a text control to the form
     *  $obj = $form->add('text', 'my_text');
     *
     *  // attach a note to the textbox control
     *  $form->add('note', 'note_my_text', 'my_text', 'Enter some text in the field above');
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
     *                                  // for a control named "my_note", one would use:
     *                                  echo $my_note;
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
     *                                  Just like in the case of {@link Zebra_Form_Label labels}, if you want a <b>master</b>
     *                                  note, a note that is attached to a <b>group</b> of checkboxes/radio buttons rather than
     *                                  individual controls, this attribute must instead refer to the <b>name</b> of the
     *                                  controls (which, for groups of checkboxes/radio buttons, is one and the same). 
     *
     *  @param  string  $caption        Content of the note (can be both plain text and/or HTML)
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/struct/global.html#h-7.5.4 div}
     *                                  elements (style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "style" attribute
     *                                  $obj = $form->add(
     *                                      'note',
     *                                      'note_my_text',
     *                                      'my_text',
     *                                      array(
     *                                          'style' => 'width:250px'
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  See {@link Zebra_Form_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *                                  The following attributes are automatically set when the control is created and
     *                                  should not be altered manually:<br>
     *                                  <b>class</b>
     *
     *  @return void
     */
    function __construct($id, $attach_to, $caption, $attributes = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        $this->private_attributes = array(

            'caption',
            'disable_xss_filters',
            'locked',
            'for',
            'name',
            'type',

        );


        // set the default attributes for the HTML control
        $this->set_attributes(

            array(

                'class'     =>  'note',
                'caption'   =>  $caption,
                'for'       =>  $attach_to,
                'id'    	=>  $id,
                'name'      =>  $id,
                'type'  	=>  'note',

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

        $attributes = $this->get_attributes('caption');
        
        return '<div ' . $this->_render_attributes() . '>' . $attributes['caption'] . '</div>';

    }

}

?>
