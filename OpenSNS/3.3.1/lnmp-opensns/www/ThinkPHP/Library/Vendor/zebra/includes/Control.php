<?php

/**
 *  A generic class containing common methods, shared by all the controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Generic
 */
class Zebra_Form_Control extends XSS_Clean
{

    /**
     *  Array of HTML attributes of the element
     *
     *  @var array
     *
     *  @access private
     */
    public $attributes;

    /**
     *  Array of HTML attributes that the control's {@link render_attributes()} method should skip
     *
     *  @var array
     *
     *  @access private
     */
    public $private_attributes;

    /**
     *  Array of validation rules set for the control
     *
     *  @var array
     *
     *  @access private
     */
    public $rules;

    /**
     *  Constructor of the class
     *
     *  @return void
     *
     *  @access private
     */
    function __construct()
    {

        $this->attributes = array(

            'locked' => false,
            'disable_xss_filters' => false,

        );

        $this->private_attributes = array();

        $this->rules = array();

    }

    /**
     *  Call this method to instruct the script to force all letters typed by the user, to either uppercase or lowercase,
     *  in real-time.
     *
     *  Works only on {@link Zebra_Form_Text text}, {@link Zebra_Form_Textarea textarea} and
     *  {@link Zebra_Form_Password password} controls.
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a text control to the form
     *  $obj = $form->add('text', 'my_text');
     *
     *  // entered characters will be upper-case
     *  $obj->change_case('upper');
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
     *  @param  string  $case   The case to convert all entered characters to.
     *
     *                          Can be (case-insensitive) "upper" or "lower".
     *
     *  @since  2.8
     *
     *  @return void
     */
    function change_case($case)
    {

        // make sure the argument is lowercase
        $case = strtolower($case);

        // if valid case specified
        if ($case == 'upper' || $case == 'lower')

            // add an extra class to the element
            $this->set_attributes(array('class' => 'modifier-' . $case . 'case'), false);

    }

    /**
     *  Disables the SPAM filter for the control.
     *
     *  By default, for checkboxes, radio buttons and select boxes, the library will prevent the submission of other
     *  values than those declared when creating the form, by triggering the error: "SPAM attempt detected!". Therefore,
     *  if you plan on adding/removing values dynamically, from JavaScript, you will have to call this method to prevent
     *  that from happening.
     *
     *  Works only for {@link Zebra_Form_Checkbox checkbox}, {@link Zebra_Form_Radio radio} and
     *  {@link Zebra_Form_Select select} controls.
     *
     *  @return void
     */
    function disable_spam_filter()
    {

        // set the "disable_xss_filters" private attribute of the control
        $this->set_attributes(array('disable_spam_filter' => true));

    }

    /**
     *  Disables XSS filtering of the control's submitted value.
     *
     *  By default, all submitted values are filtered for XSS (Cross Site Scripting) injections. The script will
     *  automatically remove possibly malicious content (event handlers, javascript code, etc). While in general this is
     *  the right thing to do, there may be the case where this behaviour is not wanted: for example, for a CMS where
     *  the WYSIWYG editor inserts JavaScript code.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->disable_xss_filters();
     *  </code>
     *
     *  @return void
     */
    function disable_xss_filters()
    {

        // set the "disable_xss_filters" private attribute of the control
        $this->set_attributes(array('disable_xss_filters' => true));

    }

    /**
     *  Returns the values of requested attributes.
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a text field to the form
     *  $obj = $form->add('text', 'my_text');
     *
     *  // set some attributes for the text field
     *  $obj->set_attributes(array(
     *      'readonly'  => 'readonly',
     *      'style'     => 'font-size:20px',
     *  ));
     *
     *  // retrieve the attributes
     *  $attributes = $obj->get_attributes(array('readonly', 'style'));
     *
     *  // the result will be an associative array
     *  //
     *  // $attributes = Array(
     *  //      [readonly]  => "readonly",
     *  //      [style]     => "font-size:20px"
     *  // )
     *  </code>
     *
     *  @param  mixed   $attributes     A single or an array of attributes for which the values to be returned.
     *
     *  @return array                   Returns an associative array where keys are the attributes and the values are
     *                                  each attribute's value, respectively.
     */
    function get_attributes($attributes)
    {

        // initialize the array that will be returned
        $result = array();

        // if the request was for a single attribute,
        // treat it as an array of attributes
        if (!is_array($attributes)) $attributes = array($attributes);

        // iterate through the array of attributes to look for
        foreach ($attributes as $attribute)

            // if attribute exists
            if (array_key_exists($attribute, $this->attributes))

                // populate the $result array
                $result[$attribute] = $this->attributes[$attribute];

        // return the results
        return $result;

    }

    /**
     *  Returns the control's value <b>after</b> the form is submitted.
     *
     *  <i>This method is automatically called by the form's {@link Zebra_Form::validate() validate()} method!</i>
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->get_submitted_value();
     *  </code>
     *
     *  @return void
     *
     *  @access private
     */
    function get_submitted_value()
    {

        // get some attributes of the control
        $attribute = $this->get_attributes(array('name', 'type', 'value', 'disable_xss_filters', 'locked'));

        // if control's value is not locked to the default value
        if ($attribute['locked'] !== true) {

            // strip any [] from the control's name (usually used in conjunction with multi-select select boxes and
            // checkboxes)
            $attribute['name'] = preg_replace('/\[\]/', '', $attribute['name']);

            // reference to the form submission method
            global ${'_' . $this->form_properties['method']};

            $method = & ${'_' . $this->form_properties['method']};

            // if form was submitted
            if (

                isset($method[$this->form_properties['identifier']]) &&

                $method[$this->form_properties['identifier']] == $this->form_properties['name']

            ) {

                // if control is a time picker control
                if ($attribute['type'] == 'time') {

                    // combine hour, minutes and seconds into one single string (values separated by :)
                    // hours
                    $combined = (isset($method[$attribute['name'] . '_hours']) ? $method[$attribute['name'] . '_hours'] : '');
                    // minutes
                    $combined .= (isset($method[$attribute['name'] . '_minutes']) ? ($combined != '' ? ':' : '') . $method[$attribute['name'] . '_minutes'] : '');
                    // seconds
                    $combined .= (isset($method[$attribute['name'] . '_seconds']) ? ($combined != '' ? ':' : '') . $method[$attribute['name'] . '_seconds'] : '');
                    // AM/PM
                    $combined .= (isset($method[$attribute['name'] . '_ampm']) ? ($combined != '' ? ' ' : '') . $method[$attribute['name'] . '_ampm'] : '');

                    // create a super global having the name of our time picker control
                    // (remember, we don't have a control with the time picker's control name but three other controls
                    // having the time picker's control name as prefix and _hours, _minutes and _seconds respectively
                    // as suffix)
                    // we need to do this so that the values will also be filtered for XSS injection
                    $method[$attribute['name']] = $combined;

                    // unset the three temporary fields as we want to return to the user the result in a single field
                    // having the name he supplied
                    unset($method[$attribute['name'] . '_hours']);
                    unset($method[$attribute['name'] . '_minutes']);
                    unset($method[$attribute['name'] . '_seconds']);
                    unset($method[$attribute['name'] . '_ampm']);

                }

                // if control was submitted
                if (isset($method[$attribute['name']])) {

                    // create the submitted_value property for the control and
                    // assign to it the submitted value of the control
                    $this->submitted_value = $method[$attribute['name']];

                    // if submitted value is an array
                    if (is_array($this->submitted_value)) {

                        // iterate through the submitted values
                        foreach ($this->submitted_value as $key => $value)

                            // and also, if magic_quotes_gpc is on (meaning that
                            // both single and double quotes are escaped)
                            // strip those slashes
                            if (get_magic_quotes_gpc()) $this->submitted_value[$key] = stripslashes($value);

                    // if submitted value is not an array
                    } else

                        // and also, if magic_quotes_gpc is on (meaning that both
                        // single and double quotes are escaped)
                        // strip those slashes
                        if (get_magic_quotes_gpc()) $this->submitted_value = stripslashes($this->submitted_value);

                    // if submitted value is an array
                    if (is_array($this->submitted_value))

                        // iterate through the submitted values
                        foreach ($this->submitted_value as $key => $value)

                            // filter the control's value for XSS injection and/or convert applicable characters to their equivalent HTML entities
                            $this->submitted_value[$key] = htmlspecialchars(!$attribute['disable_xss_filters'] ? $this->sanitize($value) : $value);

                    // if submitted value is not an array, filter the control's value for XSS injection and/or convert applicable characters to their equivalent HTML entities
                    else $this->submitted_value = htmlspecialchars(!$attribute['disable_xss_filters'] ? $this->sanitize($this->submitted_value) : $this->submitted_value);

                    // set the respective $_POST/$_GET value to the filtered value
                    $method[$attribute['name']] = $this->submitted_value;

                // if control is a file upload control and a file was indeed uploaded
                } elseif ($attribute['type'] == 'file' && isset($_FILES[$attribute['name']]))

                    $this->submitted_value = true;

                // if control was not submitted
                // we set this for those controls that are not submitted even
                // when the form they reside in is (i.e. unchecked checkboxes)
                // so that we know that they were indeed submitted but they
                // just don't have a value
                else $this->submitted_value = false;

                if (

                    //if type is password, textarea or text OR
                    ($attribute['type'] == 'password' || $attribute['type'] == 'textarea' || $attribute['type'] == 'text') &&

                    // control has the "uppercase" or "lowercase" modifier set
                    preg_match('/\bmodifier\-uppercase\b|\bmodifier\-lowercase\b/i', $this->attributes['class'], $modifiers)

                ) {

                    // if string must be uppercase, update the value accordingly
                    if ($modifiers[0] == 'modifier-uppercase') $this->submitted_value = strtoupper($this->submitted_value);

                    // otherwise, string needs to be lowercase
                    else $this->submitted_value = strtolower($this->submitted_value);

                    // set the respective $_POST/$_GET value to the updated value
                    $method[$attribute['name']] = $this->submitted_value;

                }

            }

            // if control was submitted
            if (isset($this->submitted_value)) {

                // the assignment of the submitted value is type dependant
                switch ($attribute['type']) {

                    // if control is a checkbox
                    case 'checkbox':

                        if (

                            (

	                            // if is submitted value is an array
								is_array($this->submitted_value) &&

	                            // and the checkbox's value is in the array
	                            in_array($attribute['value'], $this->submitted_value)

							// OR
							) ||

                            // assume submitted value is not an array and the
                            // checkbox's value is the same as the submitted value
                            $attribute['value'] == $this->submitted_value

                        // set the "checked" attribute of the control
                        ) $this->set_attributes(array('checked' => 'checked'));

                        // if checkbox was "submitted" as not checked
                        // and if control's default state is checked, uncheck it
                        elseif (isset($this->attributes['checked'])) unset($this->attributes['checked']);

                        break;

                    // if control is a radio button
                    case 'radio':

                        if (

                            // if the radio button's value is the same as the
                            // submitted value
                            ($attribute['value'] == $this->submitted_value)

                        // set the "checked" attribute of the control
                        ) $this->set_attributes(array('checked' => 'checked'));

                        break;

                    // if control is a select box
                    case 'select':

                        // set the "value" private attribute of the control
                        // the attribute will be handled by the
                        // Zebra_Form_Select::_render_attributes() method
                        $this->set_attributes(array('value' => $this->submitted_value));

                        break;

                    // if control is a file upload control, a hidden control, a password field, a text field or a textarea control
                    case 'file':
                    case 'hidden':
                    case 'password':
                    case 'text':
                    case 'textarea':
                    case 'time':

                        // set the "value" standard HTML attribute of the control
                        $this->set_attributes(array('value' => $this->submitted_value));

                        break;

                }

            }

        }

    }

    /**
     *  Locks the control's value. A <i>locked</i> control will preserve its default value after the form is submitted
     *  even if the user altered it.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->lock();
     *  </code>
     *
     *  @return void
     */
    function lock() {

        // set the "locked" private attribute of the control
        $this->set_attributes(array('locked' => true));

    }

    /**
     *  Resets the control's submitted value (empties text fields, unchecks radio buttons/checkboxes, etc).
     *
     *  <i>This method also resets the associated POST/GET/FILES superglobals!</i>
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->reset();
     *  </code>
     *
     *  @return void
     */
    function reset()
    {

        // reference to the form submission method
        global ${'_' . $this->form_properties['method']};

        $method = & ${'_' . $this->form_properties['method']};

        // get some attributes of the control
        $attributes = $this->get_attributes(array('type', 'name', 'other'));

        // sanitize the control's name
        $attributes['name'] = preg_replace('/\[\]/', '', $attributes['name']);

        // see of what type is the current control
        switch ($attributes['type']) {

            // control is any of the types below
            case 'checkbox':
            case 'radio':

                // unset the "checked" attribute
                unset($this->attributes['checked']);

                // unset the associated superglobal
                unset($method[$attributes['name']]);

                break;

            // control is any of the types below
            case 'date':
            case 'hidden':
            case 'password':
            case 'select':
            case 'text':
            case 'textarea':

                // simply empty the "value" attribute
                $this->attributes['value'] = '';

                // unset the associated superglobal
                unset($method[$attributes['name']]);

                // if control has the "other" attribute set
                if (isset($attributes['other']))

                    // clear the associated superglobal's value
                    unset($method[$attributes['name'] . '_other']);

                break;

            // control is a file upload control
            case 'file':

                // unset the related superglobal
                unset($_FILES[$attributes['name']]);

                break;

            // for any other control types
            default:

                // as long as control is not label, note nor captcha
                if (

                    $attributes['type'] != 'label' &&
                    $attributes['type'] != 'note' &&
                    $attributes['type'] != 'captcha'

                // unset the associated superglobal
                ) unset($method[$attributes['name']]);

        }

    }

    /**
     *  Sets one or more of the control's attributes.
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a text field to the form
     *  $obj = $form->add('text', 'my_text');
     *
     *  // set some attributes for the text field
     *  $obj->set_attributes(array(
     *      'readonly'  => 'readonly',
     *      'style'     => 'font-size:20px',
     *  ));
     *
     *  // retrieve the attributes
     *  $attributes = $obj->get_attributes(array('readonly', 'style'));
     *
     *  // the result will be an associative array
     *  //
     *  // $attributes = Array(
     *  //      [readonly]  => "readonly",
     *  //      [style]     => "font-size:20px"
     *  // )
     *  </code>
     *
     *  @param  array       $attributes     An associative array, in the form of <i>attribute => value</i>.
     *
     *  @param  boolean     $overwrite      Setting this argument to FALSE will instruct the script to append the values
     *                                      of the attributes to the already existing ones (if any) rather then overwriting
     *                                      them.
     *
     *                                      Useful, for adding an extra CSS class to the already existing ones.
     *
     *                                      For example, the {@link Zebra_Form_Text text} control has, by default, the
     *                                      <b>class</b> attribute set and already containing some classes needed both
     *                                      for styling and for JavaScript functionality. If there's the need to add one
     *                                      more class to the existing ones, without breaking styles nor functionality,
     *                                      one would use:
     *
     *                                      <code>
     *                                          // obj is a reference to a control
     *                                          $obj->set_attributes(array('class'=>'my_class'), false);
     *                                      </code>
     *
     *                                      Default is TRUE
     *
     *  @return void
     */
    function set_attributes($attributes, $overwrite = true)
    {

        // check if $attributes is given as an array
        if (is_array($attributes))

            // iterate through the given attributes array
            foreach ($attributes as $attribute => $value) {

                // we need to url encode the prefix as it may contain HTML entities which would produce validation errors
                if ($attribute == 'data-prefix') $value = urlencode($value);

                // if the value is to be appended to the already existing one
                // and there is a value set for the specified attribute
                // and the values do not represent an array
                if (!$overwrite && isset($this->attributes[$attribute]) && !is_array($this->attributes[$attribute]))

                    // append the value
                    $this->attributes[$attribute] = $this->attributes[$attribute] . ' ' . $value;

                // otherwise, add attribute to attributes array
                else $this->attributes[$attribute] = $value;

            }

    }

    /**
     *  Sets a single or an array of validation rules for the control.
     *
     *  <code>
     *      // $obj is a reference to a control
     *      $obj->set_rule(array(
     *          'rule #1'    =>  array($arg1, $arg2, ... $argn),
     *          'rule #2'    =>  array($arg1, $arg2, ... $argn),
     *          ...
     *          ...
     *          'rule #n'    =>  array($arg1, $arg2, ... $argn),
     *      ));
     *      // where 'rule #1', 'rule #2', 'rule #n' are any of the rules listed below
     *      // and $arg1, $arg2, $argn are arguments specific to each rule
     *  </code>
     *
     *  When a validation rule is not passed, a variable becomes available in the template file, having the name
     *  as specified by the rule's <b>error_block</b> argument and having the value as specified by the rule's
     *  <b>error_message</b> argument.
     *
     *  <samp>Validation rules are checked in the given order, the exceptions being the "dependencies", "required" and
     *  "upload" rules, which are *always* checked in the order of priority: "dependencies" has priority over "required"
     *  which in turn has priority over "upload".</samp>
     *
     *  I usually have at the top of my custom templates something like (assuming all errors are sent to an error block
     *  named "error"):
     *
     *  <code>echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));</code>
     *
     *  <samp>The above code nees to be used only for custom templates, or when the output is generated via callback
     *  functions! For automatically generated templates it is all taken care for you automatically by the library! Notice
     *  the $zf_error variable which is automatically created by the library if there is a SPAM or a CSRF error! Unless
     *  you use it, these errors will not be visible for the user. Again, remember, we're talking about custom templates,
     *  or output generated via callback functions.</samp>
     *
     *  One or all error messages can be displayed in an error block.
     *  See the {@link Zebra_Form::show_all_error_messages() show_all_error_messages()} method.
     *
     *  <b>Everything related to error blocks applies only for server-side validation.</b><br>
     *  <b>See the {@link Zebra_Form::client_side_validation() client_side_validation()} method for configuring how errors
     *  are to be displayed to the user upon client-side validation.</b>
     *
     *  Available rules are
     *  -   alphabet
     *  -   alphanumeric
     *  -   captcha
     *  -   compare
     *  -   convert
     *  -   custom
     *  -   date
     *  -   datecompare
     *  -   dependencies
     *  -   digits
     *  -   email
     *  -   emails
     *  -   filesize
     *  -   filetype
     *  -   float
     *  -   image
     *  -   length
     *  -   number
     *  -   regexp
     *  -   required
     *  -   resize
     *  -   upload
     *  -   url
     *
     *  Rules description:
     *
     *  -   <b>alphabet</b>
     *
     *  <code>'alphabet' => array($additional_characters, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>additional_characters</i> is a list of additionally allowed characters besides the alphabet (provide
     *      an empty string if none); note that if you want to use / (backslash) you need to specify it as three (3)
     *      backslashes ("///")!
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value contains only characters from the alphabet (case-insensitive a to z) <b>plus</b> characters
     *  given as additional characters (if any).
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'alphabet' => array(
     *          '-'                                     // allow alphabet plus dash
     *          'error',                                // variable to add the error message to
     *          'Only alphabetic characters allowed!'   // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>alphanumeric</b>
     *
     *  <code>'alphanumeric' => array($additional_characters, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>additional_characters</i> is a list of additionally allowed characters besides the alphabet and
     *      digits 0 to 9 (provide an empty string if none); note that if you want to use / (backslash) you need to
     *      specify it as three (3) backslashes ("///")!
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value contains only characters from the alphabet (case-insensitive a to z) and digits (0 to 9)
     *  <b>plus</b> characters given as additional characters (if any).
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'alphanumeric' => array(
     *          '-',                                    // allow alphabet, digits and dash
     *          'error',                                // variable to add the error message to
     *          'Only alphanumeric characters allowed!' // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>captcha</b>
     *
     *  <code>'captcha' => array($error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value matches the characters seen in the {@link Zebra_Form_Captcha captcha} image
     *  (therefore, there must be a {@link Zebra_Form_Captcha captcha} image on the form)
     *
     *  Available only for the {@link Zebra_Form_Text text} control
     *
     *  <i>This rule is not available client-side!</i>
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'captcha' => array(
     *          'error',                            // variable to add the error message to
     *          'Characters not entered correctly!' // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>compare</b>
     *
     *  <code>'compare' => array($control, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>control</i> is the name of a control on the form to compare values with
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value is the same as the value of the control indicated by <i>control</i>.
     *
     *  Useful for password confirmation.
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'compare' => array(
     *          'password'                          // name of the control to compare values with
     *          'error',                            // variable to add the error message to
     *          'Password not confirmed correctly!' // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>convert</b>
     *
     *  <samp>This rule requires the prior inclusion of the {@link http://stefangabos.ro/php-libraries/zebra-image Zebra_Image}
     *  library!</samp>
     *
     *  <code>'convert' => array($type, $jpeg_quality, $preserve_original_file, $overwrite, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>type</i> the type to convert the image to; can be (case-insensitive) JPG, PNG or GIF
     *
     *  -   <i>jpeg_quality</i>: Indicates the quality of the output image (better quality means bigger file size).
     *
     *      Range is 0 - 100
     *
     *      Available only if <b>type</b> is "jpg".
     *
     *  -   <i>preserve_original_file</i>: Should the original file be preserved after the conversion is done?
     *
     *  -   <i>$overwrite</i>: If a file with the same name as the converted file already exists, should it be
     *      overwritten or should the name be automatically computed.
     *
     *      If a file with the same name as the converted file already exists and this argument is FALSE, a suffix of
     *      "_n" (where n is an integer) will be appended to the file name.
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  This rule will convert an image file uploaded using the <b>upload</b> rule from whatever its type (as long as is one
     *  of the supported types) to the type indicated by <i>type</i>.
     *
     *  Validates if the uploaded file is an image file and <i>type</i> is valid.
     *
     *  This is not actually a "rule", but because it can generate an error message it is included here
     *
     *  You should use this rule in conjunction with the <b>upload</b> and <b>image</b> rules.
     *
     *  If you are also using the <b>resize</b> rule, make sure you are using it AFTER the <b>convert</b> rule!
     *
     *  Available only for the {@link Zebra_Form_File file} control
     *
     *  <i>This rule is not available client-side!</i>
     *
     *  <code>
     *  // $obj is a reference to a file upload control
     *  $obj->set_rule(
     *       'convert' => array(
     *          'jpg',                          // type to convert to
     *          85,                             // converted file quality
     *          false,                          // preserve original file?
     *          false,                          // overwrite if converted file already exists?
     *          'error',                        // variable to add the error message to
     *          'File could not be uploaded!'   // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>custom</b>
     *
     *  Using this rule, custom rules can be applied to the submitted values.
     *
     *  <code>'custom'=>array($callback_function_name, [optional arguments to be passed to the function], $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>callback_function_name</i> is the name of the callback function
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  <i>The callback function's first argument must ALWAYS be the control's submitted value. The optional arguments to
     *  be passed to the callback function will start as of the second argument!</i>
     *
     *  <i>The callback function MUST return TRUE on success or FALSE on failure!</i>
     *
     *  Multiple custom rules can also be set through an array of callback functions:
     *
     *  <code>
     *  'custom' => array(
     *
     *      array($callback_function_name1, [optional arguments to be passed to the function], $error_block, $error_message),
     *      array($callback_function_name1, [optional arguments to be passed to the function], $error_block, $error_message)
     *
     *  )
     *  </code>
     *
     *  <b>If {@link Zebra_Form::client_side_validation() client-side validation} is enabled (enabled by default), the
     *  custom function needs to also be available in JavaScript, with the exact same name as the function in PHP!</b>
     *
     *  For example, here's a custom rule for checking that an entered value is an integer, greater than 21:
     *
     *  <code>
     *  // the custom function in JavaScript
     *  <script type="text/javascript">
     *      function is_valid_number(value)
     *      {
     *          // return false if the value is less than 21
     *          if (value < 21) return false;
     *          // return true otherwise
     *          return true;
     *      }
     *  <&92;script>
     *  </code>
     *
     *  <code>
     *  // the callback function in PHP
     *  function is_valid_number($value)
     *  {
     *      // return false if the value is less than 21
     *      if ($value < 21) return false;
     *      // return true otherwise
     *      return true;
     *  }
     *
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a text control to the form
     *  $obj = $form->add('text', 'my_text');
     *
     *  // set two rules:
     *  // on that requires the value to be an integer
     *  // and a custom rule that requires the value to be greater than 21
     *  $obj->set_rule(
     *      'number'    =>  array('', 'error', 'Value must be an integer!'),
     *      'custom'    =>  array(
     *          'is_valid_number',
     *          'error',
     *          'Value must be greater than 21!'
     *      )
     *  );
     *  </code>
     *
     *  And here's how I do validations using <b>AJAX</b>:
     *
     *  In my website's main JavaScript file I have something like:
     *
     *  <code>
     *  var valid = null;
     *
     *  // I have functions like these for everything that I need checked through AJAX; note that they are in the global
     *  // namespace and outside the DOM-ready event
     *
     *  // functions have to return TRUE in order for the rule to be considered as obeyed
     *  function username_not_taken(username) {
     *      $.ajax({data: 'username=' + username});
     *      return valid;
     *  }
     *
     *  function emailaddress_not_taken(email) {
     *      $.ajax({data: 'email=' + email});
     *      return valid;
     *  }
     *
     *  // in the DOM ready event
     *  $(document).ready(function() {
     *
     *      // I setup an AJAX object that will handle all my AJAX calls
     *      $.ajaxSetup({
     *          url: 'path/to/validator/',  // actual work will be done in PHP
     *          type: 'post',
     *          dataType: 'text',
     *          async: false,               // this is important!
     *          global: false,
     *          beforeSend: function() {
     *              valid = null;
     *          },
     *          success: function(data, textStatus) {
     *              if (data == 'valid') valid = true;
     *              else valid = false;
     *          }
     *      });
     *
     *      // ...other JavaScript code for your website...
     *
     *  }
     *  </code>
     *
     *  I also have a "validation.php" "helper" file which contains the PHP functions that do the actual checkings. This
     *  file is included both in the page where I create the form (used by the server-side validation) and also by the
     *  file defined by the "url" property of the AJAX object (used for client-side validation). This might look something
     *  like:
     *
     *  <code>
     *  function username_not_taken($username) {
     *      // check for username and return TRUE if it's NOT taken, or FALSE otherwise
     *  }
     *
     *  function emailaddress_not_taken($email) {
     *      // check for email address and return TRUE if it's NOT taken, or FALSE otherwise
     *  }
     *  </code>
     *
     *  As stated above, when I create a form I include this "helper" file at the top, because the functions in it will
     *  be used by the server-side validation, and set the custom rules like this:
     *
     *  <code>
     *  $obj->set_rule(array(
     *      'custom'  =>  array(
     *          'username_not_taken',
     *          'error',
     *          'This user name is already taken!'
     *      ),
     *  ));
     *  </code>
     *
     *  ...and finally, at the "url" set in the AJAX object, I have something like:
     *
     *  <code>
     *  // include the "helper" file
     *  require 'path/to/validation.php';
     *
     *  if (
     *
     *      // make sure it's an AJAX request
     *      isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
     *
     *      // make sure it has a referrer
     *      isset($_SERVER['HTTP_REFERER']) &&
     *
     *      // make sure it comes from your website
     *      strpos($_SERVER['HTTP_REFERER'], 'your/website/base/url') === 0
     *
     *  ) {
     *
     *      if (
     *
     *          // i run functions depending on what's in the $_POST and also make some extra sanity checks
     *          (isset($_POST['username']) && count($_POST) == 1 && username_not_taken($_POST['username'])) ||
     *          (isset($_POST['email']) && count($_POST) == 1 && emailaddress_not_taken($_POST['email']))
     *
     *      // if whatever I'm checking is OK, I just echo "valid"
     *      // (this will be later used by the AJAX object)
     *      ) echo 'valid';
     *
     *  }
     *
     *  // do nothing for any other case
     *
     *  </code>
     *
     *  -   <b>date</b>
     *
     *  <code>'date' => array($error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value is a propper date, formated according to the format set through the
     *  {@link Zebra_Form_Date::format() format()} method.
     *
     *  Available only for the {@link Zebra_Form_Date date} control.
     *
     *  <i>Note that the validation is language dependant: if the form's language is other than English and month names
     *  are expected, the script will expect the month names to be given in that particular language, as set in the
     *  language file!</i>
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'date' => array(
     *          'error',        // variable to add the error message to
     *          'Invalid date!' // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>datecompare</b>
     *
     *  <code>'datecompare' => array($control, $comparison_operator, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>control</i> is the name of a date control on the form to compare values with
     *
     *  -   <i>comparison_operator</i> indicates how the value should be, compared to the value of <i>control</i>.<br>
     *      Possible values are <, <=, >, >=
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value satisfies the comparison operator when compared to the other date control's value.
     *
     *  Available only for the {@link Zebra_Form_Date date} control.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'datecompare' => array(
     *          'another_date'                      // name of another date control on the form
     *          '>',                                // comparison operator
     *          'error',                            // variable to add the error message to
     *          'Date must be after another_date!'  // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>dependencies</b>
     *
     *  <code>'dependencies' => array($conditions)</code>
     *
     *  or
     *
     *  <code>'dependencies' => array(array($conditions), 'callback_function_name[, arguments]')</code>
     *
     *  where
     *
     *  -   <i>$conditions</i> an array of associative arrays where the keys represent the <b>names</b> of form controls
     *      (remember: <i>names</i>, not IDs, and without the square brackets ([]) used for checkbox groups and multiple
     *      selects), while the associated value/values represent the value/values that those controls need to have in
     *      order for the current control to be validated. Notable exceptions are the {@link Zebra_Form_Submit submit}
     *      and {@link Zebra_Form_Image image} elements, where the associated value must *always* be "click".
     *
     *      <i>Only when all conditions are met, the control's other rules will be checked!</i>
     *
     *  -   <i>callback_function_name</i> is the name of an existing JavaScript function which will be executed whenever
     *      the value of any of the controls listed in the "dependencies" rule changes - useful for showing/hiding controls
     *      that depend on the values of other controls.
     *
     *  An element's other existing rules will be checked only if this rule is passed.
     *
     *  Conditions can be applied to an infinite depth and will be checked accordingly - so, a control may depend on
     *  another control which, in turn, may depend on another control and so on, and all this will be automatically taken
     *  care of: say control C depends on control B having the value "1", while control B depends on control A having the
     *  value "2"; now, even if B has the value "1", as long as A doesn't have the value "2", control C will not be
     *  validated.
     *
     *  <samp>The library will terminate exection and will trigger an error message if an infinite loop of dependencies is
     *  detected. Also, dependencies on non-existing elements will be ignored. And finally, this rule should only be used
     *  with custom templates.</samp>
     *
     *  Available for the following controls: {@link Zebra_Form_Checkbox checkbox}, {@link Zebra_Form_Date date},
     *  {@link Zebra_Form_File file}, {@link Zebra_Form_Password password}, {@link Zebra_Form_Radio radio}
     *  {@link Zebra_Form_Select select}, {@link Zebra_Form_Text text}, {@link Zebra_Form_Textarea textarea},
     *  {@link Zebra_Form_Time time}.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(array(
     *
     *       // any other rules will be checked only
     *       // if all of the following conditions are met
     *       'dependencies'  =>  array(
     *
     *          // the value of the control named "input1" is "Value 1"
     *          'input1'    =>  'Value 1',
     *
     *          // the value of the control named "input2" is "Value 2" OR "Value 3"
     *          'input2'    =>  array('Value 2', 'Value 3'),
     *
     *          // the value of the control named "radio1" is "Option 1" OR "Option 2"
     *          'radio1'    =>  array('Option 1', 'Option 2'),
     *
     *          // the value of the control named "checkbox1" (where multiple options can
     *          // be checked, same as for multiple selects) is
     *          // 'Option 1' AND 'Option 2'
     *          //  OR
     *          // 'Option 4'
     *          //  OR
     *          // 'Option 1' AND 'Option 3'
     *          'checkbox1' =>  array(
     *                              array('Option 1', 'Option 2')
     *                              'Option 4',
     *                              array('Option 1', 'Option 3')
     *                          ),
     *
     *          // the "submit" control having the "btnsubmit" ID is clicked
     *          'btnsubmit' =>  'click',
     *
     *       ),
     *
     *      // this rule will be checked only
     *      // if all of the conditions above are met
     *      'required'      =>  array('error', 'Value is required!'),
     *  ));
     *  </code>
     *
     *  Whenever any of the elements in the "dependencies" rule changes value, the library will check if all the conditions
     *  are met and, if a callback function is attached, will execute the callback function. The callback function's first
     *  argument will be the boolean result of checking if all the conditions are met. Optionally, additional comma separated
     *  arguments may be passed to the callback function (separated with a comma from the function name). Optional arguments
     *  can be used for having a single callback function instead of more, and doing different actions depending on the
     *  optional argument/arguments
     *
     *  To attach a callback function, declare the "dependencies" rule like this:
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(array(
     *
     *      // notice an array of arrays...
     *      'dependencies'  =>  array(array(
     *
     *          // conditions
     *
     *      ), 'callback_function[, argument]'),
     *
     *  ));
     *  </code>
     *
     *  Here are some examples of how to define the callback function in JavaScript:
     *
     *  <code>
     *  // in the global scope and outside the "domready" event
     *  var my_callback = function(valid) {
     *      // all conditions are met
     *      if (valid) {}
     *      // if not all conditions are met
     *      else {}
     *  }
     *
     *  // ...here comes the rest of your code
     *  $(document).ready(function() {});
     *
     *  /* ======================================================{@*}
     *
     *  // the same as above, again, outside the "domready" event
     *  function my_callback(valid) {
     *      // all conditions are met
     *      if (valid) {}
     *      // if not all conditions are met
     *      else {}
     *  }
     *
     *  // ...here comes the rest of your code
     *  $(document).ready(function() {});
     *
     *  /* ======================================================{@*}
     *
     *  // create a variable in the global scope
     *  var my_callback;
     *
     *  // put your function inside the "doready" event
     *  $(document).ready(function() {
     *
     *      // but tied to the variable from the global scope...
     *      my_callback = function(valid) {
     *          // all conditions are met
     *          if (valid) {}
     *          // if not all conditions are met
     *          else {}
     *      }
     *
     *  });
     *
     *  /* ======================================================{@*}
     *
     *  // don't pollute the global scope, use namespaces
     *  $(document).ready(function() {
     *
     *      myNameSpace = {
     *          my_callback: function(valid) {
     *              // all conditions are met
     *              if (valid) {}
     *              // if not all conditions are met
     *              else {}
     *          }
     *      }
     *
     *  });
     *
     *  // in PHP, refer to the callback function like myNameSpace.my_callback
     *  $obj->set_rule(array(
     *
     *      // notice an array of arrays...
     *       'dependencies'  =>  array(array(
     *          // conditions
     *       ), 'myNameSpace.my_callback'),
     *
     *  ));
     *
     *  </code>
     *
     *  -   <b>digits</b>
     *
     *  <code>'digits' => array($additional_characters, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>additional_characters</i> is a list of additionally allowed characters besides digits (provide
     *      an empty string if none); note that if you want to use / (backslash) you need to specify it as three (3)
     *      backslashes ("///")!
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value contains only digits (0 to 9) <b>plus</b> characters given as additional characters (if any).
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'digits' => array(
     *          '-'                         // allow digits and dash
     *          'error',                    // variable to add the error message to
     *          'Only digits are allowed!'  // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>email</b>
     *
     *  <code>'email' => array($error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value is a properly formatted email address.
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'email' => array(
     *          'error',                    // variable to add the error message to
     *          'Invalid email address!'    // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>emails</b>
     *
     *  <code>'emails' => array($error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value is a properly formatted email address <b>or</b> a comma separated list of properly
     *  formatted email addresses.
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'emails' => array(
     *          'error',                        // variable to add the error message to
     *          'Invalid email address(es)!'    // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>filesize</b>
     *
     *  <code>'filesize' => array($file_size, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>file_size</i> is the allowed file size, in bytes
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the size (in bytes) of the uploaded file is not larger than the value (in bytes) specified by
     *  <i>file_size</i>.
     *
     *  <b>Note that $file_size should be lesser or equal to the value of upload_max_filesize set in php.ini!</b>
     *
     *  Available only for the {@link Zebra_Form_File file} control.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'filesize' => array(
     *          '102400',                           // maximum allowed file size (in bytes)
     *          'error',                            // variable to add the error message to
     *          'File size must not exceed 100Kb!'  // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>filetype</b>
     *
     *  <b>If you want to check for images use the dedicated "image" rule instead!</b>
     *
     *  <code>'filetype' => array($file_types, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>file_types</i> is a string of comma separated file extensions representing uploadable file types
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates only if the uploaded file's MIME type matches the MIME types associated with the extensions set by
     *  <i>file_types</i> as defined in <i>mimes.json</i> file.
     *
     *  Note that for PHP versions 5.3.0+, compiled with the "php_fileinfo" extension, the uploaded file's mime type is
     *  determined using PHP's {@link http://php.net/manual/en/function.finfo-file.php finfo_file} function; Otherwise,
     *  the library relies on information available in the $_FILES super-global for determining an uploaded file's MIME
     *  type, which, as it turns out, is determined solely by the file's extension, representing a potential security
     *  risk;
     *
     *  Available only for the {@link Zebra_Form_File file} control.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'filetype' => array(
     *          'xls, xlsx'                 // allow only EXCEL files to be uploaded
     *          'error',                    // variable to add the error message to
     *          'Not a valid Excel file!'   // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>float</b>
     *
     *  <code>'float' => array($additional_characters, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>additional_characters</i> is a list of additionally allowed characters besides digits, one dot and one
     *      minus sign (provide an empty string if none); note that if you want to use / (backslash) you need to specify
     *      it as three (3) backslashes ("///")!
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value contains only digits (0 to 9) and/or <b>one</b> dot (but not as the very first character)
     *  and/or <b>one</b> minus sign (but only if it is the very first character) <b>plus</b> characters given as
     *  additional characters (if any).
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'float' => array(
     *          ''                  // don't allow any extra characters
     *          'error',            // variable to add the error message to
     *          'Invalid number!'   // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>image</b>
     *
     *  <code>'image' => array($error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *
     *  Validates only if the uploaded file is a valid GIF, PNG or JPEG image file.
     *
     *  Available only for the {@link Zebra_Form_File file} control.
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'image' => array(
     *          'error',                                // variable to add the error message to
     *          'Not a valid GIF, PNG or JPEG file!'    // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>length</b>
     *
     *  <code>'length' => array($minimum_length, $maximum_length, $error_block, $error_message, $show_counter)</code>
     *
     *  where
     *
     *  -   <i>minimum_length</i> is the minimum number of characters the values should contain
     *
     *  -   <i>maximum_length</i> is the maximum number of characters the values should contain
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  -   <i>show_counter</i> if set to TRUE, a counter showing the remaining characters will be displayed along with
     *      the element
     *
     *      <i>If you want to change the counter's position, do so by setting margins for the .Zebra_Character_Counter
     *      class in the zebra_form.css file</i>
     *
     *
     *  Validates only if the number of characters of the value is between $minimum_length and $maximum_length.
     *
     *  If an exact length is needed, set both $minimum_length and $maximum_length to the same value.
     *
     *  Set $maximum_length to 0 (zero) if no upper limit needs to be set for the value's length.
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'length' => array(
     *          3,                                              // minimum length
     *          6,                                              // maximum length
     *          'error',                                        // variable to add the error message to
     *          'Value must have between 3 and 6 characters!'   // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>number</b>
     *
     *  <code>'number' => array($additional_characters, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>additional_characters</i> is a list of additionally allowed characters besides digits and one
     *      minus sign (provide an empty string if none); note that if you want to use / (backslash) you need to specify
     *      it as three (3) backslashes ("///")!
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value contains only digits (0 to 9) and/or <b>one</b> minus sign (but only if it is the very
     *  first character) <b>plus</b> characters given as additional characters (if any).
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'number' => array(
     *          ''                  // don't allow any extra characters
     *          'error',            // variable to add the error message to
     *          'Invalid integer!'  // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>regexp</b>
     *
     *  <code>'regexp' => array($regular_expression, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>regular_expression</i> is the regular expression pattern (without delimiters) to be tested on the value
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value satisfies the given regular expression
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'regexp' => array(
     *          '^0123'                         // the regular expression
     *          'error',                        // variable to add the error message to
     *          'Value must begin with "0123"'  // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>required</b>
     *
     *  <code>'required' => array($error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *
     *  Validates only if a value exists.
     *
     *  Available for the following controls: {@link Zebra_Form_Checkbox checkbox}, {@link Zebra_Form_Date date},
     *  {@link Zebra_Form_File file}, {@link Zebra_Form_Password password}, {@link Zebra_Form_Radio radio},
     *  {@link Zebra_Form_Select select}, {@link Zebra_Form_Text text}, {@link Zebra_Form_Textarea textarea},
     *  {@link Zebra_Form_Time time}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'required' => array(
     *          'error',            // variable to add the error message to
     *          'Field is required' // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>resize</b>
     *
     *  <samp>This rule requires the prior inclusion of the {@link http://stefangabos.ro/php-libraries/zebra-image Zebra_Image}
     *  library!</samp>
     *
     *  <code>'resize' => array(
     *      $prefix,
     *      $width,
     *      $height,
     *      $preserve_aspect_ratio,
     *      $method,
     *      $background_color,
     *      $enlarge_smaller_images,
     *      $jpeg_quality,
     *      $error_block,
     *      $error_message,
     *  )
     *  </code>
     *
     *  where
     *
     *  -   <i>prefix</i>: If the resized image is to be saved as a new file and the originally uploaded file needs to be
     *      preserved, specify a prefix to be used for the new file. This way, the resized image will have the same name as
     *      the original file but prefixed with the given value (i.e. "thumb_").
     *
     *      Specifying an empty string as argument will instruct the script to apply the resizing to the uploaded image
     *      and therefore overwriting the originally uploaded file.
     *
     *  -   <i>width</i> is the width to resize the image to.
     *
     *      If set to <b>0</b>, the width will be automatically adjusted, depending on the value of the <b>height</b>
     *      argument so that the image preserves its aspect ratio.
     *
     *      If <b>preserve_aspect_ratio</b> is set to TRUE and both this and the <b>height</b> arguments are values
     *      greater than <b>0</b>, the image will be resized to the exact required width and height and the aspect ratio
     *      will be preserved (see the description for the <b>method</b> argument below on how can this be done).
     *
     *      If <b>preserve_aspect_ratio</b> is set to FALSE, the image will be resized to the required width and the
     *      aspect ratio will be ignored.
     *
     *      If both <b>width</b> and <b>height</b> are set to <b>0</b>, a copy of the source image will be created
     *      (<b>jpeg_quality</b> will still apply).
     *
     *      If either <b>width</b> or <b>height</b> are set to <b>0</b>, the script will consider the value of the
     *      <b>preserve_aspect_ratio</b> to bet set to TRUE regardless of its actual value!
     *
     *  -   <i>height</i> is the height to resize the image to.
     *
     *      If set to <b>0</b>, the height will be automatically adjusted, depending on the value of the <b>width</b>
     *      argument so that the image preserves its aspect ratio.
     *
     *      If <b>preserve_aspect_ratio</b> is set to TRUE and both this and the <b>width</b> arguments are values greater
     *      than <b>0</b>, the image will be resized to the exact required width and height and the aspect ratio will be
     *      preserved (see the description for the <b>method</b> argument below on how can this be done).
     *
     *      If <b>preserve_aspect_ratio</b> is set to FALSE, the image will be resized to the required height and the
     *      aspect ratio will be ignored.
     *
     *      If both <b>height</b> and <b>width</b> are set to <b>0</b>, a copy of the source image will be created
     *      (<b>jpeg_quality</b> will still apply).
     *
     *      If either <b>height</b> or <b>width</b> are set to <b>0</b>, the script will consider the value of the
     *      <b>preserve_aspect_ratio</b> to bet set to TRUE regardless of its actual value!
     *
     *  -   <i>preserve_aspect_ratio</i>: If set to TRUE, the image will be resized to the given width and height and the
     *      aspect ratio will be preserved.
     *
     *      Set this to FALSE if you want the image forcefully resized to the exact dimensions given by width and height
     *      ignoring the aspect ratio
     *
     *  -   <i>method</i>: is the method to use when resizing images to exact width and height while preserving aspect
     *      ratio.
     *
     *      If the <b>preserve_aspect_ratio</b> property is set to TRUE and both the <b>width</b> and <b>height</b>
     *      arguments are values greater than <b>0</b>, the image will be resized to the exact given width and height
     *      and the aspect ratio will be preserved by using on of the following methods:
     *
     *  -   <b>ZEBRA_IMAGE_BOXED</b> - the image will be scalled so that it will fit in a box with the given width and
     *      height (both width/height will be smaller or equal to the required width/height) and then it will be centered
     *      both horizontally and vertically. The blank area will be filled with the color specified by the
     *      <b>background_color</b> argument. (the blank area will be filled only if the image is not transparent!)
     *
     *  -   <b>ZEBRA_IMAGE_NOT_BOXED</b> - the image will be scalled so that it <i>could</i> fit in a box with the given
     *      width and height but will not be enclosed in a box with given width and height. The new width/height will be
     *      both smaller or equal to the required width/height
     *
     *  -   <b>ZEBRA_IMAGE_CROP_TOPLEFT</b>
     *  -   <b>ZEBRA_IMAGE_CROP_TOPCENTER</b>
     *  -   <b>ZEBRA_IMAGE_CROP_TOPRIGHT</b>
     *  -   <b>ZEBRA_IMAGE_CROP_MIDDLELEFT</b>
     *  -   <b>ZEBRA_IMAGE_CROP_CENTER</b>
     *  -   <b>ZEBRA_IMAGE_CROP_MIDDLERIGHT</b>
     *  -   <b>ZEBRA_IMAGE_CROP_BOTTOMLEFT</b>
     *  -   <b>ZEBRA_IMAGE_CROP_BOTTOMCENTER</b>
     *  -   <b>ZEBRA_IMAGE_CROP_BOTTOMRIGHT</b>
     *
     *  For the methods involving crop, first the image is scaled so that both its sides are equal or greater than the
     *  respective sizes of the bounding box; next, a region of required width and height will be cropped from indicated
     *  region of the resulted image.
     *
     *  -   <i>background_color</i> is the hexadecimal color of the blank area (without the #). See the <b>method</b>
     *      argument.
     *
     *  -   <i>enlarge_smaller_images</i>: if set to FALSE, images having both width and height smaller than the required
     *      width and height, will be left untouched (<b>jpeg_quality</b> will still apply).
     *
     *  -   <i>jpeg_quality</i> indicates the quality of the output image (better quality means bigger file size).
     *
     *      Range is 0 - 100
     *
     *      Available only for JPEG files.
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  <i>This rule must come</i> <b>after</b> <i>the</i> <b>upload</b> <i>rule!</i>
     *
     *  This is not an actual "rule", but because it can generate an error message it is included here.
     *
     *  Available only for the {@link Zebra_Form_File file} control
     *
     *  <i>This rule is not available client-side!</i>
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'resize' => array(
     *          'thumb_',                           // prefix
     *          '150',                              // width
     *          '150',                              // height
     *          true,                               // preserve aspect ratio
     *          ZEBRA_IMAGE_BOXED,                  // method to be used
     *          'FFFFFF',                           // background color
     *          true,                               // enlarge smaller images
     *          85,                                 // jpeg quality
     *          'error',                            // variable to add the error message to
     *          'Thumbnail could not be created!'   // error message if value doesn't validate
     *       )
     *  );
     *
     *  // for multiple resizes, use an array of arrays:
     *  $obj->set_rule(
     *       'resize' => array(
     *          array('thumb1_', 150, 150, true, ZEBRA_IMAGE_BOXED, 'FFFFFF', true, 85, 'error', 'Error!'),
     *          array('thumb2_', 300, 300, true, ZEBRA_IMAGE_BOXED, 'FFFFFF', true, 85, 'error', 'Error!'),
     *       )
     *  );
     *  </code>
     *
     *  -   <b>upload</b>
     *
     *  <code>'upload' => array($upload_path, $file_name, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>upload_path</i> the path where to upload the file to, relative to the currently running script.
     *
     *  -   <i>file_name</i>: specifies whether the uploaded file's original name should be preserved, should it be
     *      prefixed with a string, or should it be randomly generated.
     *
     *      Possible values can be <b>TRUE</b>: the uploaded file's original name will be preserved; <b>FALSE</b> (or,
     *      for better code readability, you should use the "ZEBRA_FORM_UPLOAD_RANDOM_NAMES" constant instead of "FALSE")
     *      : the uploaded file will have a randomly generated name; <b>a string</b>: the uploaded file's original name
     *      will be preserved but it will be prefixed with the given string (i.e. "original_", or "tmp_").
     *
     *      Note that when set to TRUE or a string, a suffix of "_n" (where n is an integer) will be appended to the
     *      file name if a file with the same name already exists at the given path.
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the file was successfully uploaded to the folder specified by <b>upload_path</b>.
     *
     *  <i>Remember to check the form's {@link Zebra_Form::$file_upload $file_upload} property for information about the
     *  uploaded file after the form is submitted!</i>
     *
     *  <i>Remember to check the form's {@link Zebra_Form::$file_upload_permissions $file_upload_permissions} property
     *  for how to set the filesystem permissions of the uploaded files!</i>
     *
     *  <i>Note that once this rule is run client-side, the DOM element the rule is attached to, will get a data-attribute
     *  called</i> <b>file_info</b> <i>which will contain information about the uploaded file, accessible via JavaScript.</i>
     *
     *  <code>
     *  console.log($('#element_id').data('file_info'))
     *  </code>
     *
     *  This is not actually a "rule", but because it can generate an error message it is included here
     *
     *  You should use this rule in conjunction with the <b>filesize</b> rule
     *
     *  Available only for the {@link Zebra_Form_File file} control
     *
     *  <i>This rule is not available client-side!</i>
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'upload' => array(
     *          'tmp',                              // path to upload file to
     *          ZEBRA_FORM_UPLOAD_RANDOM_NAMES,     // upload file with random-generated name
     *          'error',                            // variable to add the error message to
     *          'File could not be uploaded!'       // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  -   <b>url</b>
     *
     *  <code>'url' => array($require_protocol, $error_block, $error_message)</code>
     *
     *  where
     *
     *  -   <i>require_protocol</i> indicates whether the <i>http</i> or <i>https</i> prefix should be mandatory or not
     *
     *  -   <i>error_block</i> is the PHP variable to append the error message to, in case the rule does not validate
     *
     *  -   <i>error_message</i> is the error message to be shown when rule is not obeyed
     *
     *  Validates if the value represents a valid URL
     *
     *  The regular expression used is the following:
     *
     *  <code>/^(http(s)?\:\/\/)?[^\s\.]+\..{2,}/i</code>
     *
     *  Some example URLs that are considered valid by this rule are:
     *  -   google.com (if the <i>require_protocol</i> attribut is set to FALSE)
     *  -   http://google.com
     *  -   http://www.google.com
     *  -   http://www.google.com?foo=bar
     *  -   http://www.google.com?foo=bar#anchor
     *
     *  <samp>Note that this rule will only validate common URLs and does not attempt to be a validator for all possible
     *  valid URLs, and therefore it will fail on most of the more exotic URLs used in the tests {@link http://mathiasbynens.be/demo/url-regex here}.
     *  Keep that in mind when deciding on whether to use this rule or not. Nevertheless, this should be enough for
     *  validating most of the URLs you encountered on a daily basis.</samp>
     *
     *  Available for the following controls: {@link Zebra_Form_Password password}, {@link Zebra_Form_Text text},
     *  {@link Zebra_Form_Textarea textarea}
     *
     *  <code>
     *  // $obj is a reference to a control
     *  $obj->set_rule(
     *       'url' => array(
     *          true,               // require users to start the URL with http:// or https:// in order for the URL to be valid
     *          'error',            // variable to add the error message to
     *          'Not a valid URL!'  // error message if value doesn't validate
     *       )
     *  );
     *  </code>
     *
     *  @param  array   $rules  An associative array
     *
     *                          See above how it needs to be specified for each rule
     *
     *  @return void
     */
    function set_rule($rules)
    {

        // continue only if argument is an array
        if (is_array($rules))

            // iterate through the given rules
            foreach ($rules as $rule_name => $rule_properties) {

                // make sure the rule's name is lowercase
                $rule_name = strtolower($rule_name);

                // if custom rule
                if ($rule_name == 'custom')

                    // if more custom rules are specified at once
                    if (is_array($rule_properties[0]) && count($rule_properties[0]) == 3)

                        // iterate through the custom rules
                        // and add them one by one
                        foreach ($rule_properties as $rule) $this->rules[$rule_name][] = $rule;

                    // if a single custom rule is specified
                    // save the custom rule to the "custom" rules array
                    else $this->rules[$rule_name][] = $rule_properties;

                // for all the other rules
                // add the rule to the rules array
                else $this->rules[$rule_name] = $rule_properties;

                // for some rules we do some additional settings
                switch ($rule_name) {

                    // we set a reserved attribute for the control by which we're telling the
                    // _render_attributes() method to append a special class to the control when rendering it
                    // so that we can also control user input from javascript
                    case 'alphabet':
                    case 'digits':
                    case 'alphanumeric':
                    case 'number':
                    case 'float':

                        $this->set_attributes(array('onkeypress' => 'javascript:return $(\'#' . $this->form_properties['name'] . '\').data(\'Zebra_Form\').filter_input(\'' . $rule_name . '\', event' . ($rule_properties[0] != '' ? ', \'' . addcslashes($rule_properties[0], '\'') . '\'' : '') . ');'));

                        break;

                    // if the rule is about the length of the input
                    case 'length':

                        // if there is a maximum of allowed characters
                        if ($rule_properties[1] > 0) {

                            // set the maxlength attribute of the control
                            $this->set_attributes(array('maxlength' => $rule_properties[1]));

                            // if there is a 5th argument to the rule, the argument is boolean true
                            if (isset($rule_properties[4]) && $rule_properties[4] === true) {

                                // add an extra class so that the JavaScript library will know to show the character counter
                                $this->set_attributes(array('class' => 'show-character-counter'), false);

                            }

                        }

                        break;

                }

            }

    }

    /**
     *  Converts the array with control's attributes to valid HTML markup interpreted by the {@link toHTML()} method
     *
     *  Note that this method skips {@link $private_attributes}
     *
     *  @return string  Returns a string with the control's attributes
     *
     *  @access private
     */
    protected function _render_attributes()
    {

        // the string to be returned
        $attributes = '';

        // if
        if (

            // control has the "disabled" attribute set
            isset($this->attributes['disabled']) &&

            $this->attributes['disabled'] == 'disabled' &&

            // control is not a radio button
            $this->attributes['type'] != 'radio' &&

            // control is not a checkbox
            $this->attributes['type'] != 'checkbox'

        // add another class to the control
        ) $this->set_attributes(array('class' => 'disabled'), false);

        // iterates through the control's attributes
        foreach ($this->attributes as $attribute => $value)

            if (

                // if control has no private attributes or the attribute is not  a private attribute
                (!isset($this->private_attributes) || !in_array($attribute, $this->private_attributes)) &&

                // and control has no private javascript attributes or the attribute is not in a javascript private attribute
                (!isset($this->javascript_attributes) || !in_array($attribute, $this->javascript_attributes))

            )

                // add attribute => value pair to the return string
                $attributes .=

                    ($attributes != '' ? ' ' : '') . $attribute . '="' . preg_replace('/\"/', '&quot;', $value) . '"';

        // returns string
        return $attributes;

    }

}

?>