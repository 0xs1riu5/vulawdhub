<?php

/**
 *  Class for date controls.
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @copyright  (c) 2006 - 2014 Stefan Gabos
 *  @package    Controls
 */
class Zebra_Form_Date extends Zebra_Form_Control
{

    /**
     *  Adds a date control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Zebra_Form::add() add()} method instead!</b>
     *
     *  The output of this control will be a {@link Zebra_Form_Text textbox} control with an icon to the right of it.<br>
     *  Clicking the icon will open an inline JavaScript date picker.<br>
     *
     *  <code>
     *  // create a new form
     *  $form = new Zebra_Form('my_form');
     *
     *  // add a date control to the form
     *  $mydate = $form->add('date', 'my_date', date('Y-m-d'));
     *
     *  // set the date's format
     *  $mydate->format('M d, Y');
     *
     *  // don't forget to always call this method before rendering the form
     *  if ($form->validate()) {
     *
     *      // get the date in YYYY-MM-DD format so you can play with is easily
     *      $date = $mydate->get_date();
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
     *                                  // for a control named "my_date", one would use:
     *                                  echo $my_date;
     *                                  </code>
     *
     *  @param  string  $default        (Optional) Default date, formatted according to {@link format() format}.
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.4 input}
     *                                  controls (size, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  // setting the "readonly" attribute
     *                                  $obj = $form->add(
     *                                      'date',
     *                                      'my_date',
     *                                      '',
     *                                      array(
     *                                          'readonly' => 'readonly'
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
    function __construct($id, $default = '', $attributes = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'locked',
            'disable_xss_filters',
            'disable_zebra_datepicker',
            'date',
            'always_visible',
            'days',
            'days_abbr',
            'direction',
            'disabled_dates',
            'enabled_dates',
            'first_day_of_week',
            'format',
            'header_captions',
            'header_navigation',
            'inside_icon',
            'lang_clear_date',
            'months',
            'months_abbr',
            'offset',
            'pair',
            'readonly_element',
            'show_clear_date',
            'show_other_months',
            'show_select_today',
            'show_week_number',
            'select_other_months',
            'start_date',
            'strict',
            'view',
            'weekend_days',
            'zero_pad',

        );

        // set the javascript attributes of this control
        // these attributes will be used by the JavaScript date picker object
        $this->javascript_attributes = array(

            'always_visible',
            'days',
            'days_abbr',
            'direction',
            'disabled_dates',
            'enabled_dates',
            'first_day_of_week',
            'format',
            'header_captions',
            'header_navigation',
            'inside_icon',
            'lang_clear_date',
            'months',
            'months_abbr',
            'offset',
            'pair',
            'readonly_element',
            'show_clear_date',
            'show_other_months',
            'show_select_today',
            'show_week_number',
            'select_other_months',
            'start_date',
            'strict',
            'view',
            'weekend_days',
            'zero_pad',

        );

        // set the default attributes for the text control
        // put them in the order you'd like them rendered
        $this->set_attributes(

            array(

                'type'                      =>  'text',
                'name'                      =>  $id,
                'id'                        =>  $id,
                'value'                     =>  $default,
                'class'                     =>  'control text date',

                'always_visible'            =>  null,
                'days'                      =>  null,
                'days_abbr'                 =>  null,
                'direction'                 =>  null,
                'disable_zebra_datepicker'  =>  false,
                'disabled_dates'            =>  null,
                'enabled_dates'             =>  null,
                'first_day_of_week'         =>  null,
                'format'                    =>  'Y-m-d',
                'header_captions'           =>  null,
                'header_navigation'         =>  null,
                'inside_icon'               =>  null,
                'months'                    =>  null,
                'months_abbr'               =>  null,
                'offset'                    =>  null,
                'pair'                      =>  null,
                'readonly_element'          =>  null,
                'show_clear_date'           =>  null,
                'show_other_months'         =>  null,
                'show_select_today'         =>  null,
                'show_week_number'          =>  null,
                'select_other_months'       =>  null,
                'start_date'                =>  null,
                'strict'                    =>  null,
                'view'                      =>  null,
                'weekend_days'              =>  null,
                'zero_pad'                  =>  null,

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
     *  Should the date picker be always visible?
     *
     *  Setting this property to a jQuery element will result in the date picker being always visible, the indicated
     *  element acting as the date picker's container;
     *
     *  Note that when this property is set to TRUE, the {@link always_show_clear()} will be automatically set to TRUE.
     *
     *  <code>
     *  $date = $form->add('date', 'my_date');
     *
     *  // an element having the ID "container"
     *  // will be the date picker's container
     *  $date->always_visible('$("#container")');
     *  </code>
     *
     *  @param  string  $element    A jQuery selector pointing to an existing element from the page to be used as the
     *                              date picker's container.
     *
     *  @return void
     */
    function always_visible($element)
    {

        // set the date picker's attribute
        $this->set_attributes(array('always_visible' => $element));

    }

    /**
     *  Direction of the calendar.
     *
     *  <code>
     *  $obj = $form->add('date', 'mydate')
     *
     *  // calendar starts tomorrow and seven days after that are selectable
     *  $obj->direction(array(1, 7));
     *
     *  // calendar starts today and seven days after that are selectable
     *  $obj->direction(array(true, 7));
     *
     *  // calendar starts on January 1st 2013 and has no ending date
     *  // (assuming "format" is YYYY-MM-DD)
     *  $obj->direction(array('2013-01-01', false));
     *
     *  // calendar ends today and starts on January 1st 2012
     *  // assuming "format" is YYYY-MM-DD)
     *  $obj->direction(array(false, '2012-01-01'));
     *  </code>
     *
     *  @param  mixed   $direction      A positive or negative integer:
     *
     *                                  -   n (a positive integer) creates a future-only calendar beginning at n days
     *                                      after today;
     *
     *                                  -   -n (a negative integer) creates a past-only calendar ending at n days
     *                                      before today;
     *
     *                                  -   if n is 0, the calendar has no restrictions.
     *
     *                                  Use boolean TRUE for a future-only calendar starting with today and use boolean
     *                                  FALSE for a past-only calendar ending today.
     *
     *                                  You may also set this property to an array with two elements in the following
     *                                  combinations:
     *
     *                                  -   first item is boolean TRUE (calendar starts today), an integer > 0 (calendar
     *                                      starts n days after today), or a valid date given in the format defined by
     *                                      the "format" attribute (calendar starts at the specified date), and the second
     *                                      item is boolean FALSE (the calendar has no ending date), an integer > 0 (calendar
     *                                      ends n days after the starting date), or a valid date given in the format
     *                                      defined by the "format" attribute and which occurs after the starting date
     *                                      (calendar ends at the specified date)
     *
     *                                  -   first item is boolean FALSE (calendar ends today), an integer < 0 (calendar
     *                                      ends n days before today), or a valid date given in the format defined by the
     *                                      "format" attribute (calendar ends at the specified date), and the second item
     *                                      is an integer > 0 (calendar ends n days before the ending date), or a valid
     *                                      date given in the format defined by the "format" attribute and which occurs
     *                                      before the starting date (calendar starts at the specified date)
     *
     *
     *                                  Note that {@link disabled_dates()} will still apply!
     *
     *                                  Default is 0 (no restrictions).
     *
     *  @return void
     */
    function direction($direction)
    {

        // set the date picker's attribute
        $this->set_attributes(array('direction' => $direction));

    }

    /**
     *  Disables selection of specific dates or range of dates in the calendar.
     *
     *  <code>
     *  $obj = $form->add('date', 'mydate')
     *
     *  // disable January 1, 2012
     *  $obj->disabled_dates(array('1 1 2012'));
     *
     *  // disable all days in January 2012
     *  $obj->disabled_dates(array('* 1 2012'));
     *
     *  // disable January 1 through 10 in 2012
     *  $obj->disabled_dates(array('1-10 1 2012'));
     *
     *  // disable January 1 and 10 in 2012
     *  $obj->disabled_dates(array('1,10 1 2012'));
     *
     *  // disable 1 through 10, and then 20th, 22nd and 24th
     *  // of January through March for every year
     *  $obj->disabled_dates(array('1-10,20,22,24 1-3 *'));
     *
     *  // disable all Saturdays and Sundays
     *  $obj->disabled_dates(array('* * * 0,6'));
     *
     *  // disable 1st and 2nd of July 2012,
     *  // and all of August of 2012;
     *  $obj->disabled_dates(array('01 07 2012', '02 07 2012', '* 08 2012'));
     *  </code>
     *
     *  @param  array   $disabled_dates     An array of strings representing disabled dates. Values in the string have
     *                                      to be in the following format: "day month year weekday" where "weekday" is
     *                                      optional and can be 0-6 (Saturday to Sunday); The syntax is similar to
     *                                      cron's syntax: the values are separated by spaces and may contain * (asterisk)
     *                                      -&nbsp;(dash) and , (comma) delimiters.
     *
     *                                      Default is FALSE, no disabled dates.
     *
     *  @return void
     */
    function disabled_dates($disabled_dates) {

        // set the date picker's attribute
        $this->set_attributes(array('disabled_dates' => $disabled_dates));

    }

    /**
     *  By default, Zebra_Form relies on {@link http://stefangabos.ro/jquery/zebra-datepicker/ Zebra_DatePicker} for
     *  {@link Zebra_Form_Date Date} controls. If you want to use a different date picker, you have to disable the
     *  built-in one by calling this method.
     *
     *  <samp>Make sure the language used by the custom date picker is the same as the {@link language() language} of the
     *  library, or validation of the date will fail!
     *
     *  Also, note that {@link format() format}, {@link direction() direction} and {@link disabled_dates() disabled dates}
     *  will still apply and will be taken into account when validating the date, but the other properties will be ignored
     *  as are specific to Zebra_DatePicker!</samp>
     *
     *  @since  2.8.7
     *
     *  @return void
     */
    function disable_zebra_datepicker() {

        $this->set_attributes(array('disable_zebra_datepicker' => true));

    }

    /**
     *  Enables selection of specific dates or range of dates in the calendar, after dates have been previously disabled
     *  via {@link disabled_dates()}.
     *
     *  @param  array   $enabled_dates      An array of enabled dates in the same format as required for as argument for
     *                                      the {@link disabled_dates()} method. To be used together with
     *                                      {@link disabled_dates()} by first setting "disabled_dates" to something like
     *                                      array('* * * *') (which will disable everything) and then setting "enabled_dates"
     *                                      to, say, array('* * * 0,6') to enable just weekends.
     *
     *                                      Default is FALSE, all dates are enabled (unless, specificaly disabled via
     *                                      {@link disabled_dates()}).
     *
     *  @since 2.9.3
     *
     *  @return void
     */
    function enabled_dates($enabled_dates) {

        // set the date picker's attribute
        $this->set_attributes(array('enabled_dates' => $enabled_dates));

    }

    /**
     *  Week's starting day.
     *
     *  @param  integer $day    Valid values are 0 to 6, Sunday to Saturday.
     *
     *                          Default is 1, Monday.
     *
     *  @return void
     */
    function first_day_of_week($day)
    {

        // set the date picker's attribute
        $this->set_attributes(array('first_day_of_week' => $day));

    }

    /**
     *  Sets the format of the returned date.
     *
     *  @param  string  $format     Format of the returned date.
     *
     *                              Accepts the following characters for date formatting: d, D, j, l, N, w, S, F, m, M,
     *                              n, Y, y borrowing syntax from ({@link http://www.php.net/manual/en/function.date.php
     *                              PHP's date function})
     *
     *                              Note that when setting a date format without days (‘d’, ‘j’), the users will be able
     *                              to select only years and months, and when setting a format without months and days
     *                              (‘F’, ‘m’, ‘M’, ‘n’, ‘t’, ‘d’, ‘j’), the users will be able to select only years.
     *
     *                              Also note that the value of the "view" property (see below) may be overridden if it
     *                              is the case: a value of "days" for the "view" property makes no sense if the date
     *                              format doesn’t allow the selection of days.
     *
     *                              Default format is <b>Y-m-d</b>
     *
     *  @return void
     */
    function format($format) {

        // set the date picker's attribute
        $this->set_attributes(array('format' => $format));

    }

    /**
     *  <b>To be used after the form is submitted!</b>
     *
     *  Returns submitted date in the YYYY-MM-DD format so that it's directly usable with a database engine or with
     *  PHP's {@link http://php.net/manual/en/function.strtotime.php strtotime} function.
     *
     *  @return string  Returns submitted date in the YYYY-MM-DD format, or <b>an empty string</b> if control was
     *                  submitted with no value (empty).
     */
    function get_date()
    {

        $result = $this->get_attributes('date');

        // if control had a value return it, or return an empty string otherwise
        return (isset($result['date'])) ? $result['date'] : '';

    }

    /**
     *  Captions in the datepicker's header, for the 3 possible views: days, months, years.
     *
     *  For each of the 3 views the following special characters may be used borrowing from PHP's "date" function's
     *  syntax: m, n, F, M, y and Y; any of these will be replaced at runtime with the appropriate date fragment, depending
     *  on the currently viewed date. two more special characters are also available Y1 and Y2 (upper case representing
     *  years with 4 digits, lowercase representing years with 2 digits) which represent "currently selected year - 7"
     *  and "currently selected year + 4" and which only make sense used in the "years" view.
     *
     *  Even though any of these special characters may be used in any of the 3 views, you should use m, n, F, M for the
     *  "days" view and y, Y, Y1, Y2, y1, y2 for the "months" and "years" view or you may get unexpected results!
     *
     *  Text and HTML can also be used, and will be rendered as it is, as in the example below (the library is smart
     *  enough to not replace special characters when used in words or HTML tags):
     *
     *  <code>
     *  header_captions(array(
     *      'days'      =>  'Departure:<br>F, Y',
     *      'months'    =>  'Departure:<br>Y',
     *      'years'     =>  'Departure:<br>Y1 - Y2'
     *  ));
     *  </code>
     *
     *  Default is
     *
     *  <code>
     *  header_captions(array(
     *      'days'      =>  'F, Y',
     *      'months'    =>  'Y',
     *      'years'     =>  'Y1 - Y2'
     *  ));
     *  </code>
     *
     *  @param  $captions   An associative array containing captions in the datepicker's header, for the 3 possible
     *                      views: days, months, years.
     *
     *  @return void
     */
    function header_captions($captions)
    {

        // set the date picker's attribute
        $this->set_attributes(array('header_captions' => $captions));

    }

    /**
     *  Sets the HTML to be used for the previous month/next month buttons.
     *
     *  @param $navigation  An array with 2 elements containing the HTML to be used for the previous month/next month
     *                      buttons.
     *
     *                      Default is array('&#171;','&#187;')
     *
     *  @return void
     */
    function header_navigation($navigation)
    {

        // set the date picker's attribute
        $this->set_attributes(array('header_navigation' => $navigation));

    }

    /**
     *  Sets whether the icon for opening the datepicker should be inside or outside the element.
     *
     *  @param  boolean $value      If set to FALSE, the icon will be placed to the right of the parent element, while
     *                              if set to TRUE it will be placed to the right of the parent element, but *inside* the
     *                              element itself.
     *
     *                              Default is TRUE.
     *
     *  @return void
     */
    function inside($value) {

        // set the date picker's attribute
        // ("inside" is a "reserved" attribute so we'll pick something else)
        $this->set_attributes(array('inside_icon' => $value));

    }

    /**
     *  Sets the offset, in pixels (x, y), to shift the date picker’s position relative to the top-left of the icon that
     *  toggles the date picker.
     *
     *  @param  array  $value       An array indicating the offset, in pixels (x, y), to shift the date picker’s position
     *                              relative to the top-left of the icon that toggles the date picker.
     *
     *                              Default is array(5, -5).
     *
     *  @return void
     */
    function offset($value) {

        // set the date picker's attribute
        $this->set_attributes(array('offset' => $value));

    }

    /**
     *  Pairs the date element with another date element from the page, so that the other date element will use the current
     *  date element’s value as starting date.
     *
     *  <code>
     *  // let's assume this will be the starting date
     *  $date1 = $form->add('date', 'starting_date');
     *
     *  // dates are selectable in the future, starting with today
     *  $date1->direction(true);
     *
     *  // indicate another date element that will use this
     *  // element's value as starting date
     *  $date1->pair('ending_date');
     *
     *  // the other date element
     *  $date2 = $form->add('date', 'ending_date');
     *
     *  // start one day after the reference date
     *  // (that is, one day after whaterver is selected in the first element)
     *  $date2->direction(1);   
     *  </code>
     *
     *  @param  string  $value      The ID of another "date" element which will use the current date element's value as
     *                              starting date.
     *
     *                              Note that the rules set in the "direction" property will still apply, only that the
     *                              reference date will not be the current system date but the value selected in the
     *                              current date picker.
     *
     *                              Default is FALSE (not paired with another date picker)
     *
     *  @return void
     */
    function pair($value) {

        // set the date picker's attribute
        $this->set_attributes(array('pair' => '$(\'#' . $value . '\')'));

    }

    /**
     *  Sets whether the element the calendar is attached to should be read-only.
     *
     *  @param  boolean $value      The setting's value
     *
     *                              If set to TRUE, a date can be set only through the date picker and cannot be enetered
     *                              manually.
     *
     *                              Default is TRUE.
     *
     *  @return void
     */
    function readonly_element($value) {

        // set the date picker's attribute
        $this->set_attributes(array('readonly_element' => $value));

    }

    /**
     *  Should days from previous and/or next month be selectable when visible?
     *
     *  @param  string  $value      The setting's value
     *
     *                              Note that if set to TRUE, the value of {@link show_other_months()} will be considered
     *                              TRUE regardless of the actual value!
     *
     *                              Default is TRUE.
     *
     *  @since 2.9.3
     *
     *  @return void
     */
    function select_other_months($value) {

        // set the date picker's attribute
        $this->set_attributes(array('select_other_months' => $value));

    }

    /**
     *  Should the "Clear date" button be visible?
     *
     *  @param  string  $value      The setting's value
     *
     *                              Accepted values are:
     *
     *                              -   0 (zero) – the button for clearing a previously selected date is shown only if a
     *                                  previously selected date already exists; this means that if the input the date
     *                                  picker is attached to is empty, and the user selects a date for the first time,
     *                                  this button will not be visible; once the user picked a date and opens the date
     *                                  picker again, this time the button will be visible.
     *
     *                              -   TRUE will make the button visible all the time
     *
     *                              -   FALSE will disable the button
     *
     *                              Default is "0" (without quotes)
     *
     *  @return void
     */
    function show_clear_date($value = 0)
    {

        // set the date picker's attribute
        $this->set_attributes(array('show_clear_date' => $value));

    }

    /**
     *  Should days from previous and/or next month be visible?
     *
     *  @param  string  $value      The setting's value
     *
     *                              Default is TRUE.
     *
     *  @since 2.9.3
     *
     *  @return void
     */
    function show_other_months($value = true) {

        // set the date picker's attribute
        $this->set_attributes(array('show_other_months' => $value));

    }

    /**
     *  Should the "Today" button be visible?
     *
     *  @param  string  $value      The setting's value
     *
     *                              Setting this property to anything but a boolean FALSE will enable the button and
     *                              will use the property's value as caption for the button; setting it to FALSE will
     *                              disable the button.
     *
     *                              Default is "Today"
     *
     *  @since 2.9.4
     *
     *  @return void
     */
    function show_select_today($value = 'Today')
    {

        // set the date picker's attribute
        $this->set_attributes(array('show_select_today' => $value));

    }

    /**
     *  Sets whether an extra column should be shown, showing the number of each week.
     *
     *  @param  string  $value      Anything other than FALSE will enable this feature, and use the given value as column
     *                              title. For example, show_week_number: ‘Wk’ would enable this feature and have "Wk" as
     *                              the column’s title.
     *
     *                              Default is FALSE.
     *
     *  @return void
     */
    function show_week_number($value) {

        // set the date picker's attribute
        $this->set_attributes(array('show_week_number' => $value));

    }

    /**
     *  Sets a default date to start the date picker with.
     *
     *  @param  date    $value      A default date to start the date picker with,
     *
     *                              Must be specified in the format defined by the "format" property, or it will be
     *                              ignored!
     *
     *                              Note that this value is used only if there is no value in the field the date picker
     *                              is attached to!
     *
     *                              Default is FALSE.
     *
     *  @return void
     */
    function start_date($value) {

        // set the date picker's attribute
        $this->set_attributes(array('start_date' => $value));

    }

    /**
     *  Sets whether default values, in the input field the date picker is attached to, be deleted if they are not valid
     *  according to {@link direction() direction} and/or {@link disabled_dates() disabled_dates}.
     *
     *  @param  boolean $value      If set to TRUE, default values, in the input field the date picker is attached to,
     *                              will be deleted if they are not valid according to {@link direction() direction}
     *                              and/or {@link disabled_dates() disabled_dates}.
     *
     *                              Default is FALSE.
     *
     *  @return void
     */
    function strict($value) {

        // set the date picker's attribute
        $this->set_attributes(array('strict' => $value));

    }

    /**
     *  Sets how should the date picker start.
     *
     *  @param  string  $view       How should the date picker start.
     *
     *                              Valid values are "days", "months" and "years".
     *
     *                              Note that the date picker is always cycling days-months-years when clicking in the
     *                              date picker's header, and years-months-days when selecting dates (unless one or more
     *                              of the views are missing due to the date's format)
     *
     *                              Also note that the value of the "view" property may be overridden if the date's format
     *                              requires so! (i.e. "days" for the "view" property makes no sense if the date format
     *                              doesn't allow the selection of days)
     *
     *                              Default is "days".
     *
     *  @return void
     */
    function view($view) {

        // set the date picker's attribute
        $this->set_attributes(array('view' => $view));

    }

    /**
     *  Sets the days of the week that are to be considered  as "weekend days".
     *
     *  @param  array   $days       An array of days of the week that are to be considered  as "weekend days".
     *
     *                              Valid values are 0 to 6 (Sunday to Saturday).
     *
     *                              Default is array(0,6) (Saturday and Sunday).
     *
     *  @return void
     */
    function weekend_days($days) {

        // set the date picker's attribute
        $this->set_attributes(array('weekend_days' => $days));

    }

    /**
     *  Should day numbers < 10 be padded with zero?
     *
     *  @param  boolean $state      When set to TRUE, day numbers < 10 will be prefixed with 0.
     *
     *                              Default is FALSE.
     *
     *  @return void
     */
    function zero_pad($state) {

        // set the date picker's attribute
        $this->set_attributes(array('zero_pad' => $state));

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

        // all date controls must have the "date" rule set or we trigger an error
        if (!isset($this->rules['date'])) _zebra_form_show_error('The control named <strong>"' . $this->attributes['name'] . '"</strong> in form <strong>"' . $this->form_properties['name'] . '"</strong> must have the <em>"date"</em> rule set', E_USER_ERROR);

        return '
            <div>
                <input ' . $this->_render_attributes() . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '>
                <div class="clear"></div>
            </div>
        ';

    }

    /**
     *  Initializes the datepicker's data so calculations for disabled dates can be done.
     *
     *  Returns an array with two values: the first and the last selectable dates, as UNIX timestamps.
     *
     *  If a value does not apply (i.e. no starting or no ending date), the value will be "0".
     *
     *  @return array   Returns an array with two values: the first and the last selectable dates,
     *                  as UNIX timestamps.
     *
     *  @access private
     */
    function _init()
    {

        // do these calculations only once
        if (!isset($this->limits)) {

            // get current system date
            $system_date = time();

            // check if the calendar has any restrictions

            // calendar is future-only, starting today
            // it means we have a starting date (the current system date), but no ending date
            if ($this->attributes['direction'] === true) $this->first_selectable_date = $system_date;

            // calendar is past only, ending today
            // it means we have an ending date (the reference date), but no starting date
            else if ($this->attributes['direction'] === false) $this->last_selectable_date = $system_date;

            else if (

                // if direction is not given as an array and the value is an integer > 0
                (!is_array($this->attributes['direction']) && (int)($this->attributes['direction']) > 0) ||

                // or direction is given as an array
                (is_array($this->attributes['direction']) && (

                    // and first entry is boolean TRUE
                    $this->attributes['direction'][0] === true ||
                    // or an integer > 0
                    (is_numeric($this->attributes['direction'][0]) && $this->attributes['direction'][0] > 0) ||
                    // or a valid date
                    ($tmp_start_date = $this->_is_format_valid($this->attributes['direction'][0]))

                ) && (

                    // and second entry is boolean FALSE
                    $this->attributes['direction'][1] === false ||
                    // or integer >= 0
                    (is_numeric($this->attributes['direction'][1]) && $this->attributes['direction'][1] >= 0) ||
                    // or a valid date
                    ($tmp_end_date = $this->_is_format_valid($this->attributes['direction'][1]))

                ))

            ) {

                // if an exact starting date was given, use that as a starting date
                if (isset($tmp_start_date)) $this->first_selectable_date = $tmp_start_date;

                // otherwise
                else

                    // figure out the starting date
                    $this->first_selectable_date = strtotime('+' . (!is_array($this->attributes['direction']) ? (int)($this->attributes['direction']) : (int)($this->attributes['direction'][0] === true ? 0 : $this->attributes['direction'][0])) . ' day', $system_date);

                // if an exact ending date was given and the date is after the starting date, use that as a ending date
                if (isset($tmp_end_date) && $tmp_end_date >= $this->first_selectable_date) $this->last_selectable_date = $tmp_end_date;

                // if have information about the ending date
                else if (!isset($tmp_end_date) && $this->attributes['direction'][1] !== false && is_array($this->attributes['direction']))

                    // figure out the ending date
                    $this->last_selectable_date = strtotime('+' . (int)($this->attributes['direction'][1]) . ' day', $system_date);

            } else if (

                // if direction is not given as an array and the value is an integer < 0
                (!is_array($this->attributes['direction']) && is_numeric($this->attributes['direction']) && $this->attributes['direction'] < 0) ||

                // or direction is given as an array
                (is_array($this->attributes['direction']) && (

                    // and first entry is boolean FALSE
                    $this->attributes['direction'][0] === false ||
                    // or an integer < 0
                    (is_numeric($this->attributes['direction'][0]) && $this->attributes['direction'][0] < 0)

                ) && (

                    // and second entry is integer >= 0
                    (is_numeric($this->attributes['direction'][1]) && $this->attributes['direction'][1] >= 0) ||
                    // or a valid date
                    ($tmp_start_date = $this->_is_format_valid($this->attributes['direction'][1]))

                ))

            ) {

                // figure out the ending date
                $this->last_selectable_date = strtotime('+' . (!is_array($this->attributes['direction']) ? (int)($this->attributes['direction']) : (int)($this->attributes['direction'][0] === false ? 0 : $this->attributes['direction'][0])) . ' day', $system_date);

                // if an exact starting date was given, and the date is before the ending date, use that as a starting date
                if (isset($tmp_start_date) && $tmp_start_date < $this->last_selectable_date) $this->first_selectable_date = $tmp_start_date;

                // if have information about the starting date
                else if (!isset($tmp_start_date) && is_array($this->attributes['direction']))

                    // figure out the staring date
                    $this->first_selectable_date = strtotime('-' . (int)($this->attributes['direction'][1]) . ' day');

            }

            // if a first selectable date exists
            if (isset($this->first_selectable_date)) {

                // extract the date parts
                $first_selectable_year = date('Y', $this->first_selectable_date);
                $first_selectable_month = date('m', $this->first_selectable_date);
                $first_selectable_day = date('d', $this->first_selectable_date);

            }

            // if a last selectable date exists
            if (isset($this->last_selectable_date)) {

                // extract the date parts
                $last_selectable_year = date('Y', $this->last_selectable_date);
                $last_selectable_month = date('m', $this->last_selectable_date);
                $last_selectable_day = date('d', $this->last_selectable_date);

            }

            // if a first selectable date exists but is disabled, find the actual first selectable date
            if (isset($this->first_selectable_date) && $this->_is_disabled($first_selectable_year, $first_selectable_month, $first_selectable_day)) {

                // loop until we find the first selectable year
                while ($this->_is_disabled($first_selectable_year)) {

                    // if calendar is past-only, decrement the year
                    if ($this->first_selectable_date < 0 || $this->first_selectable_date === false) $first_selectable_year--;

                    // otherwise, increment the year
                    else $first_selectable_year++;

                    // because we've changed years, reset the month to January
                    $first_selectable_month = 1;

                }

                // loop until we find the first selectable month
                while ($this->_is_disabled($first_selectable_year, $first_selectable_month)) {

                    // if calendar is past-only, decrement the month
                    if ($this->first_selectable_date < 0 || $this->first_selectable_date === false) $first_selectable_month--;

                    // otherwise, increment the month
                    else $first_selectable_month++;

                    // if we moved to a following year
                    if ($first_selectable_month > 12) {

                        // increment the year
                        $first_selectable_year++;

                        // reset the month to January
                        $first_selectable_month = 1;

                    // if we moved to a previous year
                    } else if ($first_selectable_month < 1) {

                        // decrement the year
                        $first_selectable_year--;

                        // reset the month to January
                        $first_selectable_month = 1;

                    }

                    // because we've changed months, reset the day to the first day of the month
                    $first_selectable_day = 1;

                }

                // loop until we find the first selectable day
                while ($this->_is_disabled($first_selectable_year, $first_selectable_month, $first_selectable_day))

                    // if calendar is past-only, decrement the day
                    if ($this->first_selectable_date < 0 || $this->first_selectable_date === false) $first_selectable_day--;

                    // otherwise, increment the day
                    else $first_selectable_day++;

                // use mktime() to normalize the date
                // for example, 2011 05 33 will be transformed to 2011 06 02
                $this->first_selectable_date = mktime(12, 0, 0, $first_selectable_month, $first_selectable_day, $first_selectable_year);

                // re-extract date parts from the normalized date
                // as we use them in the current loop
                // extract the date parts
                $first_selectable_year = date('Y', $this->first_selectable_date);
                $first_selectable_month = date('m', $this->first_selectable_date);
                $first_selectable_day = date('d', $this->first_selectable_date);

            }

            // save first and last selectable dates, as UNIX timestamps (or "0" if does not apply)
            $this->limits = array(isset($this->first_selectable_date) ? $this->first_selectable_date : 0, isset($this->last_selectable_date) ? $this->last_selectable_date : 0);

        }

        // return first and last selectable dates, as UNIX timestamps (or "0" if does not apply)
        return $this->limits;

    }

    /**
     *  Checks if the enetered value is a valid date in the right format.
     *
     *  @return mixed   Returns the UNIX timestamp of the checked date, if the date has the correct format,
     *                  or FALSE otherwise.
     *
     *  @access private
     */
    function _is_format_valid($date)
    {

        // the format we expect the date to be
        // escape characters that would make sense as regular expression
        $format = preg_quote($this->attributes['format']);

        // parse the format and extract the characters that define the format
        // (note that we're also capturing the offsets)
        preg_match_all('/[dDjlNSwFmMnYyGHghaAisU]{1}/', $format, $matches, PREG_OFFSET_CAPTURE);

        $regexp = array();

        // iterate through the found characters
        // and create the regular expression that we will use to see if the entered date is ok
        foreach ($matches[0] as $match) {

            switch ($match[0]) {

                // day of the month, 2 digits with leading zeros, 01 to 31
                case 'd': $regexp[] = '0[1-9]|[12][0-9]|3[01]'; break;

                // a textual representation of a day, three letters, mon through sun
                case 'D': $regexp[] = '[a-z]{3}'; break;

                // day of the month without leading zeros, 1 to 31
                case 'j': $regexp[] = '[1-9]|[12][0-9]|3[01]'; break;

                // a full textual representation of the day of the week, sunday through saturday
                case 'l': $regexp[] = '[a-z]+'; break;

                // ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0), 1 (for Monday) through 7 (for Sunday)
                case 'N': $regexp[] = '[1-7]'; break;

                // english ordinal suffix for the day of the month, 2 characters: st, nd, rd or th. works well with j
                case 'S': $regexp[] = 'st|nd|rd|th'; break;

                // numeric representation of the day of the week, 0 (for sunday) through 6 (for saturday)
                case 'w': $regexp[] = '[0-6]'; break;

                // a full textual representation of a month, such as january or march
                case 'F': $regexp[] = '[a-z]+'; break;

                // numeric representation of a month, with leading zeros, 01 through 12
                case 'm': $regexp[] = '0[1-9]|1[012]+'; break;

                // a short textual representation of a month, three letters, jan through dec
                case 'M': $regexp[] = '[a-z]{3}'; break;

                // numeric representation of a month, without leading zeros, 1 through 12
                case 'n': $regexp[] = '[1-9]|1[012]'; break;

                // a full numeric representation of a year, 4 digits examples: 1999 or 2003
                case 'Y': $regexp[] = '[0-9]{4}'; break;

                // a two digit representation of a year examples: 99 or 03
                case 'y': $regexp[] = '[0-9]{2}'; break;

                // 24-hour format of an hour without leading zeros, 0 through 23
				case 'G': $regexp[] = '[0-9]|1[0-9]|2[0-3]'; break;

                // 24-hour format of an hour with leading zeros, 00 through 23
				case 'H': $regexp[] = '0[0-9]|1[0-9]|2[0-3]'; break;

                // 12-hour format of an hour without leading zeros, 1 through 12
				case 'g': $regexp[] = '[0-9]|1[0-2]'; break;

                // 12-hour format of an hour with leading zeros, 01 through 12
				case 'h': $regexp[] = '0[0-9]|1[0-2]'; break;

                // lowercase ante meridiem and post meridiem am or pm
				case 'a':
				case 'A': $regexp[] = '(am|pm)'; break;

                // minutes with leading zeros, 00 to 59
				case 'i': $regexp[] = '[0-5][0-9]'; break;

                // seconds, with leading zeros 00 through 59
				case 's': $regexp[] = '[0-5][0-9]'; break;

            }

        }

        // if format is defined
        if (!empty($regexp)) {

            // we will replace every format-related character in the format expression with
            // the appropriate regular expression in order to see that valid data was entered
            // as required by the character
            // we are replacing from finish to start so that we don't mess up the offsets
            // therefore, we need to reverse the array first
            $matches[0] = array_reverse($matches[0]);

            // how many characters to replace
            $chars = count($matches[0]);

            // iterate through the characters
            foreach ($matches[0] as $index => $char)

                // and replace them with the appropriate regular expression
                $format = substr_replace($format, '(' . $regexp[$chars - $index - 1] . ')', $matches[0][$index][1], 1);

            // the final regular expression to math the date against
            $format = '/^' . str_replace('/', '\/', $format) . '$/i';

            // if entered value seems to be ok
            if (preg_match($format, $date, $segments)) {

                $original_day = $original_month = $original_year = 0;
                $original_hour = $original_minute = $original_second = $original_format = -1;

                // english names for days and months
                $english_days   = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
                $english_months = array('January','February','March','April','May','June','July','August','September','October','November','December');

                // reverse the characters in the format (remember that we reversed them above)
                $matches[0] = array_reverse($matches[0]);

                $valid = true;

                // iterate through the characters in the format
                // to see if years, months, days, hours, minutes and seconds are correct
                // i.e. if for month we entered "abc" it would pass our regular expression but
                // now we will check if the three letter text is an actual month
                foreach ($matches[0] as $index => $match) {

                    switch ($match[0]) {

                        // numeric representation of a month, with leading zeros, 01 through 12
                        case 'm':
                        // numeric representation of a month, without leading zeros, 1 through 12
                        case 'n':

                            $original_month = (int)($segments[$index + 1] - 1);

                            break;

                        // day of the month, 2 digits with leading zeros, 01 to 31
                        case 'd':
                        // day of the month without leading zeros, 1 to 31
                        case 'j':

                            $original_day = (int)($segments[$index + 1]);

                            break;

                        // a textual representation of a day, three letters, mon through sun
                        case 'D':
                        // a full textual representation of the day of the week, sunday through saturday
                        case 'l':
                        // a full textual representation of a month, such as january or march
                        case 'F':
                        // a short textual representation of a month, three letters, jan through dec
                        case 'M':

                            // by default, we assume that the text is invalid
                            $valid = false;

                            // iterate through the values in the language file
                            foreach ($this->form_properties['language'][($match[0] == 'F' || $match[0] == 'M' ? 'months' : 'days')] as $key => $value) {

                                // if value matches the value from the language file
                                if (strtolower($segments[$index + 1]) == strtolower(substr($value, 0, ($match[0] == 'D' || $match[0] == 'M' ? 3 : strlen($value))))) {

                                    // replace with the english value
                                    // this is because later on we'll run strtotime of the entered value and strtotime parses english dates
                                    switch ($match[0]) {
                                        case 'D': $segments[$index + 1] = substr($english_days[$key], 0, 3); break;
                                        case 'l': $segments[$index + 1] = $english_days[$key]; break;
                                        case 'F': $segments[$index + 1] = $english_months[$key]; $original_month = $key; break;
                                        case 'M': $segments[$index + 1] = substr($english_months[$key], 0, 3); $original_month = $key; break;
                                    }

                                    // flag the value as valid
                                    $valid = true;

                                    // don't look further
                                    break;

                                }

                            }

                            // if an invalid was found don't look any further
                            if (!$valid) break 2;

                            break;

                        // a full numeric representation of a year, 4 digits examples: 1999 or 2003
                        case 'Y':

                            $original_year = (int)($segments[$index + 1]);

                            break;

                        // a two digit representation of a year examples: 99 or 03
                        case 'y':

                            $original_year = (int)('19' . $segments[$index + 1]);

                            break;

                        // 24-hour format of an hour without leading zeros, 0 through 23
        				case 'G':
                        // 24-hour format of an hour with leading zeros, 00 through 23
        				case 'H':
                        // 12-hour format of an hour without leading zeros, 1 through 12
        				case 'g':
                        // 12-hour format of an hour with leading zeros, 01 through 12
        				case 'h':

                            $original_hour = (int)($segments[$index + 1]);

                            break;

                        // lowercase ante meridiem and post meridiem am or pm
        				case 'a':
        				case 'A':

                            $original_format = $segments[$index + 1];

                            break;

                        // minutes with leading zeros, 00 to 59
        				case 'i':

                            $original_minute = (int)($segments[$index + 1]);

                            break;

                        // seconds, with leading zeros 00 through 59
        				case 's':

                            $original_second = (int)($segments[$index + 1]);

                            break;

                    }

                }

                // if entered value seems valid
                if ($valid) {

                    // if date format does not include day, make day = 1
                    if ($original_day == 0) $original_day = 1;

                    // if date format does not include month, make month = 0 (January)
                    if ($original_month == 0) $original_month = 0;

                    // if date format does not include year, use the current year
                    if ($original_year == 0) $original_year = date('Y');

                    // if date is still valid after we process it with strtotime
                    // (we do this because, so far, a date like "Feb 31 2010" would be valid
                    // but strtotime would turn that to "Mar 03 2010")
                    if (

                        $english_months[$original_month] . ' ' . str_pad($original_day, 2, '0', STR_PAD_LEFT) . ', ' . $original_year ==
                        date('F d, Y', strtotime($english_months[$original_month] . ' ' . $original_day . ', ' . $original_year))

                    ) {

                        // make sure we also return the date as YYYY-MM-DD so that it can be
                        // easily used with a database or with PHP's strtotime function
                        $this->attributes['date'] = $original_year . '-' . str_pad($original_month + 1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($original_day, 2, '0', STR_PAD_LEFT);

                        return strtotime($original_year . '-' . ($original_month + 1) . '-' . $original_day);

                    }

                }

            }

        }

        // if script gets this far, return FALSE as something must've been wrong
        return false;

    }

    /**
     *  Checks if, according to the restrictions of the calendar and/or the values defined by the "disabled_dates"
     *  property, a day, a month or a year needs to be disabled.
     *
     *  @param  integer     $year   The year to check
     *  @param  integer     $month  The month to check
     *  @param  integer     $day    The day to check
     *
     *  @return boolean         Returns TRUE if the given value is not disabled or FALSE otherwise
     *
     *  @access private
     */
    function _is_disabled($year, $month = '', $day = '')
    {

        // parse the rules for disabling dates and turn them into arrays of arrays
        if (!isset($this->disabled_dates)) {

            // array that will hold the rules for disabling dates
            $this->disabled_dates = array();

            // if disabled dates is an array and is not empty
            if (is_array($this->attributes['disabled_dates']) && !empty($this->attributes['disabled_dates']))

                // iterate through the rules for disabling dates
                foreach ($this->attributes['disabled_dates'] as $value) {

                    // split the values in rule by white space
                    $rules = explode(' ', $value);

                    // there can be a maximum of 4 rules (days, months, years and, optionally, day of the week)
                    for ($i = 0; $i < 4; $i++) {

                        // if one of the values is not available
                        // replace it with a * (wildcard)
                        if (!isset($rules[$i])) $rules[$i] = '*';

                        // if rule contains a comma, create a new array by splitting the rule by commas
                        // if there are no commas create an array containing the rule's string
                        $rules[$i] = (strpos($rules[$i], ',') !== false ? explode(',', $rules[$i]) : (array)$rules[$i]);

                        // iterate through the items in the rule
                        for ($j = 0; $j < count($rules[$i]); $j++)

                            // if item contains a dash (defining a range)
                            if (strpos($rules[$i][$j], '-') !== false) {

                                // get the lower and upper limits of the range
                                // if range is valid
                                if (preg_match('/^([0-9]+)\-([0-9]+)/', $rules[$i][$j], $limits) > 0) {

                                    // remove the range indicator
                                    array_splice($rules[$i], $j, 1);

                                    // iterate through the range
                                    for ($k = $limits[1]; $k <= $limits[2]; $k++)

                                        // if value is not already among the values of the rule
                                        // add it to the rule
                                        if (!in_array($k, $rules[$i])) $rules[$i][] = (int)$k;

                                }

                            // make sure to convert things like "01" to "1"
                            } elseif ($rules[$i][$j] != '*') $rules[$i][$j] = (int)$rules[$i][$j];

                    }

                    // add to the list of processed rules
                    $this->disabled_dates[] = $rules;

                }

        }

        // if calendar has direction restrictions
        if (!(!is_array($this->attributes['direction']) && (int)($this->attributes['direction']) === 0)) {

            // if a first selectable date exists
            if (isset($this->first_selectable_date)) {

                // extract the date parts
                $first_selectable_year = date('Y', $this->first_selectable_date);
                $first_selectable_month = date('m', $this->first_selectable_date);
                $first_selectable_day = date('d', $this->first_selectable_date);

            }

            // if a last selectable date exists
            if (isset($this->last_selectable_date)) {

                // extract the date parts
                $last_selectable_year = date('Y', $this->last_selectable_date);
                $last_selectable_month = date('m', $this->last_selectable_date);
                $last_selectable_day = date('d', $this->last_selectable_date);

            }

            // normalize and merge arguments then transform the result to an integer
            $now = $year . ($month != '' ? str_pad($month, 2, '0', STR_PAD_LEFT) : '') . ($day != '' ? str_pad($day, 2, '0', STR_PAD_LEFT) : '');

            // if we're checking days
            if (strlen($now) == 8 && (

                // day is before the first selectable date
                (isset($this->first_selectable_date) && $now < $first_selectable_year . $first_selectable_month . $first_selectable_day) ||

                // or day is after the last selectable date
                (isset($this->last_selectable_date) && $now > $last_selectable_year . $last_selectable_month . $last_selectable_day)

            // day needs to be disabled
            )) return true;

            // if we're checking months
            else if (strlen($now) == 6 && (

                // month is before the first selectable month
                (isset($this->first_selectable_date) && $now < $first_selectable_year . $first_selectable_month) ||

                // or month is after the last selectable month
                (isset($this->last_selectable_date) && $now > $last_selectable_year . $last_selectable_month)

            // month needs to be disabled
            )) return true;

            // if we're checking years
            else if (strlen($now) == 4 && (

                // year is before the first selectable year
                (isset($this->first_selectable_date) && $now < $first_selectable_year) ||

                // or year is after the last selectable year
                (isset($this->last_selectable_date) && $now > $last_selectable_year)

            // year needs to be disabled
            )) return true;

        }

        // if there are rules for disabling dates
        if (isset($this->disabled_dates)) {

            // by default, we assume the day/month/year is not to be disabled
            $disabled = false;

            // iterate through the rules for disabling dates
            foreach ($this->disabled_dates as $rule) {

                // if the date is to be disabled, don't look any further
                if ($disabled) return;

                // if the rules apply for the current year
                if (in_array($year, $rule[2]) || in_array('*', $rule[2], true))

                    // if the rules apply for the current month
                    if (($month != '' && in_array($month, $rule[1])) || in_array('*', $rule[1], true))

                        // if the rules apply for the current day
                        if (($day != '' && in_array($day, $rule[0])) || in_array('*', $rule[0], true)) {


                            // if day is to be disabled whatever the day
                            // don't look any further
                            if (in_array('*', $rule[3], true)) return ($disabled = true);

                            // get the weekday
                            $weekday = date('w', mktime(12, 0, 0, $month, $day, $year));

                            // if weekday is to be disabled
                            // don't look any further
                            if (in_array($weekday, $rule[3])) return ($disabled = true);

                        }

            }

            // if the day/month/year needs to be disabled
            if ($disabled) return true;

        }

        // if script gets this far it means that the day/month/year doesn't need to be disabled
        return false;

    }

}

?>
