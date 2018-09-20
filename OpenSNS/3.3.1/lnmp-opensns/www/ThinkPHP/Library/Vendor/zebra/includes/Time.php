<?php

/**
 *  Class for time picker controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Time extends Zebra_Form_Control
{

    /**
     *  Adds a time picker control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  The output of this control will be one, two, three or four {@link Zebra_Form_Select select} controls for hour,
     *  minutes, seconds and AM/PM respectively, according to the given format as set by the <i>$attributes</i> argument.
     *
     *  Note that even though there will be more select boxes, the submitted values will be available as a single merged
     *  value (in the form of hh:mm:ss AM/PM, depending on the format), with the name as given by the <i>id</i> argument.
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a time picker control for hour and minutes
     *  $obj = $form->add('time', 'my_time', date('H:i'), array('format' => 'hm'));
     *
     *  // don't forget to always call this method before rendering the form
     *  if ($form->validate()) {
     *
     *      // note that even though there will be more select boxes, the submitted
     *      // values will be available as a single merged value (in the form of
     *      // mm:mm:ss AM/PM, depending on the format), with the name as given by
     *      // the "id" argument:
     *      echo $_POST['my_time'];
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
     *                                  // for a control named "my_time", one would use:
     *                                  echo $my_time;
     *                                  </code>
     *
     *  @param  string  $default        (Optional) String representing the default time to be shown. Must be set according
     *                                  to the format of the time, as specified in <i>$attributes</i>. For example, for a
     *                                  time format of "hm", one would set the default time in the form of "hh:mm" while
     *                                  for a time format of "hms", one would set the time in the form of "hh:mm:ss".
     *
     *                                  Default is current system time.
     *
     *  @param  array   $attributes     (Optional) An array of user specified attributes valid for an time picker
     *                                  control (format, hours, minutes, seconds, am/pm).
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *
     *                                  Available attributes are:
     *
     *                                  -   format - format of time; a string containing one, or a combination of the four
     *                                      allowed characters: "h" (hours), "m" (minutes) and "s" (seconds) and "g" for
     *                                      using 12-hours format instead of the default 23-hours format; (i.e. setting the
     *                                      format to "hm" would allow the selection of hours and minutes, setting the
     *                                      format to "hms" would allow the selection of hours, minutes and seconds, and
     *                                      setting the format to "hmg" would allow the selection of hours and minutes
     *                                      using the 12-hours format instead of the 24-hours format)
     *
     *                                  -   hours - an array of selectable hours (i.e. array(10, 11, 12))
     *
     *                                  -   minutes - an array of selectable minutes (i.e. array(15, 30, 45))
     *
     *                                  -   seconds - an array of selectable seconds
     *
     *                                  See {@link Zebra_Form_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *  @return void
     */
    function __construct($id, $default = '', $attributes = '')
    {
    
        // call the constructor of the parent class
        parent::__construct();
    
        // these will hold the default selectable hours, minutes and seconds
        $hours = $minutes = $seconds = array();

        // all the 24 hours are available by default
        for ($i = 0; $i < 24; $i++) $hours[] = $i;

        // all the minutes and seconds are available by default
        for ($i = 0; $i < 60; $i++) $minutes[] = $seconds[] = $i;

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(
        
            'disable_xss_filters',
            'locked',
            'type',
            'name',
            'id',
            'format',
            'hours',
            'minutes',
            'seconds',
            'value',
            
        );

        // set the default attributes for the text control
        // put them in the order you'd like them rendered
        $this->set_attributes(
        
            array(
            
                'type'      =>  'time',
                'name'      =>  $id,
                'id'        =>  $id,
                'value'     =>  $default,
                'class'     =>  'control time',
                'format'    =>  'hm',
                'hours'     =>  $hours,
                'minutes'   =>  $minutes,
                'seconds'   =>  $seconds,

            )
            
        );

        // sets user specified attributes for the control
        $this->set_attributes($attributes);
        
    }

    /**
     *  Generates and returns the control's HTML code.
     *
     *  <i>This method is automatically called by the {@link Zebra_Form::render() render()} method!</i>
     *
     *  @return string  The control's HTML code
     */
    function toHTML()
    {

        // get some attributes of the control
        $attributes = $this->get_attributes(array('name', 'value', 'class', 'format', 'hours', 'minutes', 'seconds'));

        // make sure format is in lower characters
        $attributes['format'] = strtolower($attributes['format']);

        // if invalid format specified, revert to the default "hm"
        if (preg_match('/^[hmsg]+$/i', $attributes['format']) == 0 || strlen(preg_replace('/([a-z]{2,})/i', '$1', $attributes['format'])) != strlen($attributes['format'])) $attributes['format'] = 'hm';

        // see what have we sepcified as default time
        $time = array_diff(explode(':', trim(str_replace(array('am', 'pm'), '', strtolower($attributes['value'])))), array(''));

        // if, according to the time format, we have to show the hours, and the hour is given in the default time
        if (($hour_position = strpos($attributes['format'], 'h')) !== false && isset($time[$hour_position]))

            // the default selected hour
            $selected_hour = $time[$hour_position];

        // if, according to the time format, we have to show the minutes, and the minutes are given in the default time
        if (($minutes_position = strpos($attributes['format'], 'm')) !== false && isset($time[$minutes_position]))

            // the default selected minute
            $selected_minute = $time[$minutes_position];

        // if, according to the time format, we have to show the seconds, and the seconds are given in the default time
        if (($seconds_position = strpos($attributes['format'], 's')) !== false && isset($time[$seconds_position]))

            // the default selected minute
            $selected_second = $time[$seconds_position];

        // if 12-hours format is to be used
        if (strpos($attributes['format'], 'g')) {

            // set a flag
            $ampm = true;

            // if this is also present in the default time
            if (preg_match('/\bam\b|\bpm\b/i', $attributes['value'], $matches))

                // extract the format from the default time
                $ampm = strtolower($matches[0]);

        }


        $output = '';

        // if the hour picker is to be shown
        if ($hour_position !== false) {

            // generate the hour picker
            $output .= '
                <select name="' . $attributes['name'] . '_hours" id="' . $attributes['name'] . '_hours" ' . $this->_render_attributes() . '>
                    <option value="">-</option>';

            foreach ($attributes['hours'] as $hour)

                // show 12 or 24 hours depending on the format
                if (!isset($ampm) || ($hour > 0 && $hour < 13))

                    $output .= '<option value="' . str_pad($hour, 2, '0', STR_PAD_LEFT) . '"' . (isset($selected_hour) && ltrim($selected_hour, '0') == ltrim($hour, '0') ? '  selected="selected"' : '') . '>' . str_pad($hour, 2, '0', STR_PAD_LEFT) . '</option>';

            $output .= '
                </select>
            ';

        }
        
        // if the minute picker is to be shown
        if ($minutes_position !== false) {

            // generate the minute picker
            $output .= '
                <select name="' . $attributes['name'] . '_minutes" id="' . $attributes['name'] . '_minutes" ' . $this->_render_attributes() . '>
                    <option value="">-</option>';

            foreach ($attributes['minutes'] as $minute)

                $output .= '<option value="' . str_pad($minute, 2, '0', STR_PAD_LEFT) . '"' . (isset($selected_minute) && ltrim($selected_minute, '0') == ltrim($minute, '0') ? ' selected="selected"' : '') . '>' . str_pad($minute, 2, '0', STR_PAD_LEFT) . '</option>';

            $output .= '
                </select>
            ';

        }

        // if the seconds picker is to be shown
        if ($seconds_position !== false) {

            // generate the seconds picker
            $output .= '
                <select name="' . $attributes['name'] . '_seconds" id="' . $attributes['name'] . '_seconds" ' . $this->_render_attributes() . '>
                    <option value="">-</option>';

            foreach ($attributes['seconds'] as $second)

                $output .= '<option value="' . str_pad($second, 2, '0', STR_PAD_LEFT) . '"' . (isset($selected_second) && ltrim($selected_second, '0') == ltrim($second, '0') ? ' selected="selected"' : '') . '>' . str_pad($second, 2, '0', STR_PAD_LEFT) . '</option>';

            $output .= '
                </select>
            ';

        }

        // if am/pm picker is to be shown
        if (isset($ampm)) {

            // generate the AM/PM picker
            $output .= '
                <select name="' . $attributes['name'] . '_ampm" id="' . $attributes['name'] . '_ampm" ' . $this->_render_attributes() . '>
                    <option value="">-</option>';

            $output .= '<option value="AM"' . ($ampm === 'am' ? ' selected="selected"' : '') . '>AM</option>';
            $output .= '<option value="PM"' . ($ampm === 'pm' ? ' selected="selected"' : '') . '>PM</option>';

            $output .= '
                </select>
            ';

        }

        $output .= '<div class="clear"></div>';

        return $output;

    }

}

?>
