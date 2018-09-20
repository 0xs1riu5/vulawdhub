<?php

/**
 *  Class for file upload controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_File extends Zebra_Form_Control
{

    /**
     *  Adds an <input type="file"> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a file upload control to the form
     *  $obj = $form->add('file', 'my_file_upload');
     *
     *  // don't forget to always call this method before rendering the form
     *  if ($form->validate()) {
     *      
     *      // all the information about the uploaded file will be
     *      // available in the "file_upload" property
     *      print_r('<pre>');
     *      print_r($form->file_upload['my_file_upload']);
     *
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
     *                                  // for a control named "my_file_upload", one would use:
     *                                  echo $my_file_upload;
     *                                  </code>
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.4 input}
     *                                  controls (size, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "disabled" attribute
     *                                  $obj = $form->add(
     *                                      'file',
     *                                      'my_file_upload',
     *                                      '',
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
     *
     *                                  <b>type</b>, <b>id</b>, <b>name</b>, <b>class</b>
     *
     *  @return void
     */
    function __construct($id, $attributes = '')
    {
    
        // call the constructor of the parent class
        parent::__construct();
    
        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_xss_filters',
            'locked',
            'files',

        );

        // set the default attributes for the text control
        // put them in the order you'd like them rendered
        $this->set_attributes(
        
            array(

		        'type'      =>  'file',
                'name'      =>  $id,
                'id'        =>  $id,
                'class'     =>  'control file',

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
    
        // all file upload controls must have the "upload" rule set or we trigger an error
        if (!isset($this->rules['upload'])) _zebra_form_show_error('The control named <strong>"' . $this->attributes['name'] . '"</strong> in form <strong>"' . $this->form_properties['name'] . '"</strong> must have the <em>"upload"</em> rule set', E_USER_ERROR);

        // if the "image" rule is set
        if (isset($this->rules['image']))

            // these are the allowed file extensions
            $allowed_file_types = array('jpe', 'jpg', 'jpeg', 'png', 'gif');

        // if the "filetype" rule is set
        elseif (isset($this->rules['filetype']))

            // get the array of allowed file extensions
            $allowed_file_types = array_map(create_function('$value', 'return trim($value);'), explode(',', $this->rules['filetype'][0]));

        // if file selection should be restricted to certain file types
        if (isset($allowed_file_types)) {

            $mimes = array();

            // iterate through allowed extensions
            foreach ($allowed_file_types as $extension)

                // get the mime type for each extension
                if (isset($this->form_properties['mimes'][$extension]))

                    $mimes = array_merge($mimes, (array)$this->form_properties['mimes'][$extension]);

            // set the "accept" attribute
            // see http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#file-upload-state-%28type=file%29
            // at the time of writing, on December 30, 2012, this was only working on Chrome 23 and IE 10
            $this->set_attributes(array('accept' => '.' . implode(',.', $allowed_file_types) . ',' . implode(',', $mimes)));

        }

        // show the file upload control
        $output = '<input ' . $this->_render_attributes() . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '>';

        // return the generated output
        return $output;

    }

}

?>
