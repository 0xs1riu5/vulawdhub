    <?php

/**
 *  Class for button controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Button extends Zebra_Form_Control
{

    /**
     *  Constructor of the class
     *
     *  Adds an <button> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a submit button to the form
     *  $obj = $form->add('button', 'my_button', 'Click me!', 'submit');
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
     *                                  // for a control named "my_button", one would use:
     *                                  echo $my_button;
     *                                  </code>
     *
     *  @param  string  $caption        Caption of the button control.
     *
     *                                  Can be HTML markup.
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.4 input}
     *                                  controls (size, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "disabled" attribute
     *                                  $obj = $form->add(
     *                                      'button',
     *                                      'my_button',
     *                                      'Click me!',
     *                                      'submit'  // <- make this a submit button
     *                                      array(
     *                                          'disabled' => 'disabled'
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  See {@link Zebra_Form_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *                                  The following attributes are automatically set when the control is created and
     *                                  should not be altered manually:<br>
     *                                  <b>id</b>, <b>name</b>, <b>class</b>
     *
     *  @param  string  $type           (Optional) Type of the button: button, submit or reset.
     *
     *                                  Default is "button".
     *
     *  @return void
     */
    function __construct($id, $caption, $type = 'button', $attributes = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_xss_filters',
            'locked',
            'value',

        );

        // set the default attributes for the button control
        // put them in the order you'd like them rendered
        $this->set_attributes(

            array(
                'type'  =>  $type,
                'name'  =>  $id,
                'id'    =>  $id,
                'value' =>  $caption,
               // 'class' =>  'button' . ($type != 'button' ? ' ' . $type : ''),
                'class' =>  'btn' . ($type != 'button' ? ' ' . $type : ''),
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

        return '<button ' . $this->_render_attributes() . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '>' . $this->attributes['value'] . '</button>';

    }

}

?>