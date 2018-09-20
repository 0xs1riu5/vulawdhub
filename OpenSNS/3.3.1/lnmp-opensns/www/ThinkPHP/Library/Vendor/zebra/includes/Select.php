<?php

/**
 *  Class for select box controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Select extends Zebra_Form_Control
{

    /**
     *  Adds an <select> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  By default, unless the <b>multiple</b> attribute is set, the control will have a default first option added
     *  automatically inviting users to select one of the available options. Default value for English is
     *  "<i>-&nbsp;select&nbsp;-</i>" taken from the language file - see the {@link Zebra_Form::language() language()}
     *  method. If you don't want it or want to set it at runtime, set the <i>overwrite</i> argument to TRUE when calling
     *  the {@link add_options()} method.
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // single-option select box
     *  $obj = $form->add('select', 'my_select');
     *
     *  // add selectable values with default indexes
     *  // values will be "0", "1" and "2", respectively
     *  // a default first value, "- select -" (language dependent) will also be added
     *  $obj->add_options(array(
     *      'Value 1',
     *      'Value 2',
     *      'Value 3'
     *  ));
     *
     *  // single-option select box
     *  $obj = $form->add('select', 'my_select2');
     *
     *  // add selectable values with specific indexes
     *  // values will be "v1", "v2" and "v3", respectively
     *  // a default first value, "- select -" (language dependent) will also be added
     *  $obj->add_options(array(
     *      'v1' => 'Value 1',
     *      'v2' => 'Value 2',
     *      'v3' => 'Value 3'
     *  ));
     *
     *  // single-option select box with the second value selected
     *  $obj = $form->add('select', 'my_select3', 'v2');
     *
     *  // add selectable values with specific indexes
     *  // values will be "v1", "v2" and "v3", respectively
     *  // also, overwrite the language-specific default first value (notice the boolean TRUE at the end)
     *  $obj->add_options(array(
     *      ''   => '- select a value -',
     *      'v1' => 'Value 1',
     *      'v2' => 'Value 2',
     *      'v3' => 'Value 3'
     *  ), true);
     *
     *  // multi-option select box with the first two options selected
     *  $obj = $form->add('select', 'my_select4[]', array('v1', 'v2'), array('multiple' => 'multiple'));
     *
     *  // add selectable values with specific indexes
     *  // values will be "v1", "v2" and "v3", respectively
     *  $obj->add_options(array(
     *      'v1' => 'Value 1',
     *      'v2' => 'Value 2',
     *      'v3' => 'Value 3'
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
     *  <samp>By default, for checkboxes, radio buttons and select boxes, the library will prevent the submission of other
     *  values than those declared when creating the form, by triggering the error: "SPAM attempt detected!". Therefore,
     *  if you plan on adding/removing values dynamically, from JavaScript, you will have to call the
     *  {@link Zebra_Form_Control::disable_spam_filter() disable_spam_filter()} method to prevent that from happening!</samp>
     *
     *  @param  string  $id             Unique name to identify the control in the form.
     *
     *                                  The control's <b>name</b> attribute will be as specified by the <i>$id</i>
     *                                  argument.<br>
     *                                  The <b>id</b> attribute will be as specified by the <i>$id</i> argument but with
     *                                  square brackets trimmed off (if any).
     *
     *                                  This is the name to be used when referring to the control's value in the
     *                                  POST/GET superglobals, after the form is submitted.
     *
     *                                  This is also the name of the variable (again, with square brackets trimmed off
     *                                  if it's the case) to be used in the template file, containing the generated HTML
     *                                  for the control.
     *
     *                                  <code>
     *                                  // in a template file, in order to print the generated HTML
     *                                  // for a control named "my_select", one would use:
     *                                  echo $my_select;
     *                                  </code>
     *
     *  @param  mixed   $default        (Optional) Default selected option.
     *
     *                                  This argument can also be an array in case the <b>multiple</b> attribute is set
     *                                  and multiple options need to be preselected by default.
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#edef-SELECT select}
     *                                  controls (multiple, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "multiple" attribute
     *                                  $obj = $form->add(
     *                                      'select',
     *                                      'my_select',
     *                                      '',
     *                                      array(
     *                                          'multiple' => 'multiple'
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  <b>Special attribute:</b>
     *
     *                                  When setting the special attribute <b>other</b> to <b>true</b>, a
     *                                  {@link Zebra_Form_Text textbox} control will be automatically created having the
     *                                  name <i>[id]_other</i> where [id] is the select control's <b>id</b> attribute.
     *                                  The text box will be hidden until the user selects the automatically added
     *                                  <i>Other...</i> option (language dependent) from the selectable options. The
     *                                  option's value will be <b>other</b>. If the template is not automatically
     *                                  generated you will have to manually add the automatically generated control to
     *                                  the template.
     *
     *                                  See {@link Zebra_Form_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *                                  The following attributes are automatically set when the control is created and
     *                                  should not be altered manually:<br>
     *
     *                                  <b>id</b>, <b>name</b>
     *
     *  @param  string  $default_other  The default value in the "other" field (if the "other" attribute is set to true,
     *                                  see above)
     *
     *  @return void
     */
    function __construct($id, $default = '', $attributes = '', $default_other = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'default_other',
            'disable_spam_filter',
            'disable_xss_filters',
            'locked',
            'options',
            'other',
            'type',
            'value',

		);

        // set the default attributes for the textarea control
        // put them in the order you'd like them rendered
        $this->set_attributes(

			array(

                'name'          =>  $id,
                'id'            =>  str_replace(array('[', ']'), '', $id),
                'class'         =>  'control',
                'options'       =>  array(),
			    'type'          =>  'select',
                'value'         =>  $default,
                'default_other' =>  $default_other,

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
     *  Adds options to the select box control
     *
     *  <b>If the "multiple" attribute is not set, the first option will be always considered as the "nothing is selected"
     *  state of the control!</b>
     *
     *  @param  array   $options    An associative array of options where the key is the value of the option and the
     *                              value is the actual text to be displayed for the option.
     *
     *                              <b>Option groups</b> can be set by giving an array of associative arrays as argument:
     *
     *                              <code>
     *                                  // add as groups:
     *                                  $obj->add_options(array(
     *                                      'group' => array('option 1', 'option 2')
     *                                  ));
     *                              </code>
     *
     *  @param  boolean $overwrite  (Optional) By default, succesive calls of this method will appended the options
     *                              given as arguments to the already existing options.
     *
     *                              Setting this argument to TRUE will instead overwrite the previously existing options.
     *
     *                              Default is FALSE
     *
     *  @return void
     */
    function add_options($options, $overwrite = false)
    {

        // continue only if parameter is an array
        if (is_array($options)) {

            // get some properties of the select control
            $attributes = $this->get_attributes(array('options', 'multiple'));

            // if there are no options so far AND
            // we're not overwriting existing options AND
            // the "multiple" attribute is not set
            if (empty($attributes['options']) && $overwrite === false && !isset($attributes['multiple']))

                // add the default value
                // we'll replace the value with the appropriate language
                $options = array('' => $this->form_properties['language']['select']) + $options;

            // set the options attribute of the control
            $this->set_attributes(

				array(

				    'options'   =>  ($overwrite ? $options : $attributes['options'] + $options)

				)

			);

        // if options are not specified as an array
        } else {

            // trigger an error message
            _zebra_form_show_error('
                Selectable values for the <strong>' . $this->attributes['id'] . '</strong> control must be specified as
                an array
            ');

        }

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

        // get the options of the select control
        $attributes = $this->get_attributes(array('options', 'value', 'multiple', 'other'));

        // if select box is not "multi-select" and the "other" attribute is set
        if (!isset($attributes['multiple']) && isset($attributes['other']))

            // add an extra options to the already existing ones
            $attributes['options'] += array('other' => $this->form_properties['language']['other']);

        // if the default value, as added when instantiating the object is still there
        // or if no options were specified
        if (($key = array_search('#replace-with-language#', $attributes['options'])) !== false || empty($attributes['options']))

            // put the label from the language file
            $attributes['options'][$key] = $this->form_properties['language']['select'];

        // use a private, recursive method to generate the select's content
        $optContent = $this->_generate($attributes['options'], $attributes['value']);

        // return generated HTML
        return '<select '. $this->_render_attributes() . '>' . $optContent . '</select>';

    }

    /**
     *  Takes the options array and recursively generates options and optiongroups
     *
     *  @return string  Resulted HTML code
     *
     *  @access private
     */
    private function _generate(&$options, &$selected, $level = 0)
    {

        $content = '';

        // character(s) used for indenting levels
        $indent = '&nbsp;&nbsp;';

        // iterate through the available options
        foreach ($options as $value => $caption) {

            // if option has child options
            if (is_array($caption)) {

                // create a dummy option group (for valid HTML/XHTML we are not allowed to create nested option groups
                // and also empty optiongroups are not allowed)
                // BUT because in IE7 the "disabled" attribute is not supported and in all versions of IE these
                // can't be styled, we will remove them from JavaScript
                // having a dummy option in them (the option is disabled and, from CSS, rendered invisible)
                $content .= '
                    <optgroup label="' . str_repeat($indent, $level) . $value . '">
                        <option disabled="disabled" class="dummy"></option>
                    </optgroup>
                ';

                // call the method recursively to generate the output for the children options
                $content .= $this->_generate($caption, $selected, $level + 1);

            // if entry is a standard option
            } else {

                // create the appropriate code
                $content .= '<option value="' . $value . '"' .

                    // if anything was selected
					($selected !== '' && $value !== '' &&

                    	(

                            // and the current option is selected
    						(is_array($selected) && in_array($value, $selected)) ||

                    		(!is_array($selected) && (string)$value === (string)$selected)

                        // set the appropriate attribute
    					) ? ' selected="selected"' : ''

                    ) . '>' .

                // indent appropriately
                str_repeat($indent, $level) . $caption . '</option>';

            }

        }

        // return generated content
        return $content;

    }

}

?>
