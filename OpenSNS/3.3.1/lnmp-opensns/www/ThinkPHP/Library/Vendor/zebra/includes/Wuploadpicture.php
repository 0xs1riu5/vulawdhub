<?php

/**
 *  Class for text controls.
 *
 * @author     Stefan Gabos <contact@stefangabos.ro>
 * @copyright  (c) 2006 - 2014 Stefan Gabos
 * @package    Controls
 */
class Zebra_Form_Wuploadpicture extends Zebra_Form_Control
{

    /**
     *  Adds an <input type="text"> control to the form.
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
     *  // don't forget to always call this method before rendering the form
     *  if ($form->validate()) {
     *      // put code here
     *  }
     *
     *  // output the form using an automatically generated template
     *  $form->render();
     *  </code>
     *
     * @param  string $id Unique name to identify the control in the form.
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
     *                                  // for a control named "my_text", one would use:
     *                                  echo $my_text;
     *                                  </code>
     *
     * @param  string $default (Optional) Default value of the text box.
     *
     * @param  array  $attributes (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.4 input}
     *                                  controls (size, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "readonly" attribute
     *                                  $obj = $form->add(
     *                                      'text',
     *                                      'my_text',
     *                                      '',
     *                                      array(
     *                                          'readonly' => 'readonly'
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  There's a special <b>data-prefix</b> attribute that you can use to add <i>uneditable
     *                                  prefixes</i> to input fields (text, images, or plain HTML), as seen in the image
     *                                  below. It works by injecting an absolutely positioned element into the DOM, right
     *                                  after the parent element, and then positioning it on the left side of the parent
     *                                  element and adjusting the width and the left padding of the parent element, so it
     *                                  looks like the prefix is part of the parent element.
     *
     *                                  <i>If the prefix is plain text or HTML code, it will be contained in a <div> tag
     *                                  having the class </i> <b>Zebra_Form_Input_Prefix</b><i>; if the prefix is a path to an
     *                                  image, it will be an <img> tag having the class </i> <b>Zebra_Form_Input_Prefix</b><i>.</i>
     *
     *                                  <samp>For anything other than plain text, you must use CSS to set the width and
     *                                  height of the prefix, or it will not be correctly positioned because when the image
     *                                  is not cached by the browser the code taking care of centering the image will
     *                                  be executed before the image is loaded by the browser and it will not know the
     *                                  image's width and height!</samp>
     *
     *                                  {@img src=../media/zebra-form-prefix.jpg class=graphic}
     *
     *                                  <code>
     *                                  // add simple text
     *                                  // style the text through the Zebra_Form_Input_Prefix class
     *                                  $form->add('text', 'my_text', '', array('data-prefix' => 'http://'));
     *                                  $form->add('text', 'my_text', '', array('data-prefix' => '(+1 917)'));
     *
     *                                  // add images
     *                                  // set the image's width and height through the img.Zebra_Form_Input_Prefix class
     *                                  // in your CSS or the image will not be correctly positioned!
     *                                  $form->add('text', 'my_text', '', array('data-prefix' => 'img:path/to/image'));
     *
     *                                  // add html - useful when using sprites
     *                                  // again, make sure that you set somewhere the width and height of the prefix!
     *                                  $form->add('text', 'my_text', '', array('data-prefix' => '<div class="sprite image1"></div>'));
     *                                  $form->add('text', 'my_text', '', array('data-prefix' => '<div class="sprite image2"></div>'));
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
     * @return void
     */
    function __construct($id, $default = '', $attributes = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_xss_filters',
            'default_value',
            'locked',
            'value',
            'type',

        );

        // set the default attributes for the text control
        // put them in the order you'd like them rendered
        $this->set_attributes(

            array(

                'type' => 'text',
                'name' => $id,
                'id' => $id,
                'value' => $default,
                'class' => '',

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
     * @return string  The control's HTML code
     */
    function toHTML()
    {


        $attributes = $this->get_attributes(array('name', 'value', 'id', 'config', 'class', 'text', 'width', 'height'));

        $attributes_id = $attributes['id'];
        $config = $attributes['config'];
        $class = $attributes['class'];
        $value = $attributes['value'];
        $name = $attributes['name'];
        $width = $attributes['width'] ? $attributes['width'] : 100;
        $height = $attributes['height'] ? $attributes['height'] : 100;

        $filetype = $this->rules['filetype'];

        $config = $config['config'];

        $id = $attributes_id;
        $config = array('text' => '选择文件'
        );


        if (intval($value) != 0) {
            $url = getThumbImageById($value, $width, $height);
            $img = '<img src="' . $url . '"/>';
        } else {
            $img = '请选择图片';
        }

        $control = <<<Eof
       <span  id="web_uploader_wrapper_{$id}">{$config['text']}</span>

        <input id="web_uploader_input_{$id}" name="{$name}"  type="hidden"  value="{$value}">
        <div id="web_uploader_picture_list_{$id}"  class="web_uploader_picture_list">
    {$img}
        </div>
Eof;
        $script = <<<Eof
 <script>
           var id="#web_uploader_wrapper_{$id}";
        var uploader_{$id} = WebUploader.create({
            // swf文件路径
            swf: 'Uploader.swf',
            // 文件接收服务端。
            server: U('Core/File/uploadPicture'),
            fileNumLimit: 5,
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {'id':id , 'multi': false}
        });
        uploader_{$id}.on('fileQueued', function (file) {
        uploader_{$id}.upload();
            $("#web_uploader_file_name_{$id}").text('正在上传...');
        });

        /*上传成功*/
        uploader_{$id}.on('uploadSuccess', function (file, ret) {
        if (ret.status == 0) {
            $("#web_uploader_file_name_{$id}").text(ret.info);
        } else {
            $('#web_uploader_input_{$id}').val(ret.data.file.id);

            $("#web_uploader_picture_list_{$id}").html('<img src="'+ret.data.file.path+'"/>');
        }
    });
    </script>
Eof;
        return $control . $script;

        // return '<input ' . $this->_render_attributes() . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '>';

    }

}