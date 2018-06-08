/**
* Multiple Selects - jQuery plugin for converting a multiple <select> into two, adding the ability to move items between the boxes.
* http://code.google.com/p/jqmultiselects/
*
* Copyright (c) 2007 Rob Desbois
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
*
* Version: 0.2
*
* Changelog
* 0.1
* initial release
*
* 0.2
* <option> elements can be automatically selected upon parent form submission
* plugin options now passed as an array
* all element identifiers now taken as jQuery selectors instead
* added beforeMove and afterMove callback functions
*/

/** Adds multiple select behaviour to a <select> element.
* This allows options to be transferred to a different select using mouse double-clicks, or multiple options at a time via another element.
*
* Syntax:
* $(source).multiSelect(dest, options);
*
* Options:
* * trigger:    Selector for elements which will trigger the move on a click event. Default is none.
* * autoSubmit: Whether to automatically select <option>s for submission with parent form submit. Default true.
* * beforeMove: Before move callback function. Return true to continue/false to cancel the move operation.
* * afterMove:  After move callback function.
*
* @example $("#my_select_left").multiSelect("#my_select_right");
* @desc Sets up double-clicks on #my_select_left's options to move the option to #my_select_right
* @example $("#my_select_left").multiSelect("#my_select_right", {trigger: "#my_move_right_button"});
* @desc Sets up double-clicks as above and also sets up #my_move_right_button to transfer multiple elements on click.
*
* @example
* <form action="test-handler.php" method="get">
*    <table>
*       <tr>
*          <td><select name="left[]" id="select_left" multiple="multiple" size="6">
*             <option>Item 1</option>
*             <option>Item 2</option>
*             <option>Item 3</option>
*             <option>Item 4</option>
*          </select></td>
*          
*          <td>
*             <p><a id="options_right" href="#">
*                <img src="arrow_right.gif" alt="&gt;" />
*             </a></p>

*            <p><a id="options_left" href="#"> 
*                <img src="arrow_left.gif" alt="&lt;" />
*             </a></p>
*          </td>

*          <td><select name="right[]" id="select_right" multiple="multiple" size="6">
*             <option>Item 5</option>
*             <option>Item 6</option>
*             <option>Item 7</option>
*             <option>Item 8</option>
*          </select></td>
*       </tr>
*    </table>
*    
*    <input type="submit" />
* </form>
*
*
* <script type="text/javascript"><!--
* $(function() {
*    $("#select_left").multiSelect("#select_right", {trigger: "#options_right"});
*    $("#select_right").multiSelect("#select_left", {trigger: "#options_left"});
* });
* // --></script>
*/
jQuery.fn.multiSelect = function(to, options) {
	// support v0.1 syntax
	if (typeof options == "string")
		options = {trigger: "#"+options};
	
	options = $.extend({
		trigger: null,     // selector of elements whose 'click' event should invoke a move
		autoSubmit: true,  // true => select all child <option>s on parent form submit (if any)
		beforeMove: null,  // before move callback function
		afterMove: null    // after move callback
	}, options);

	// for closures
	var $select = this;
	
	// make form submission select child <option>s
	if (options.autoSubmit)
		this.parents("form").submit(function() { selectChildOptions($select); });
	

	// create the closure
	var moveFunction = function() { moveOptions($select, to, options.beforeMove, options.afterMove); };
	
	// attach double-click behaviour
	this.dblclick(moveFunction);
	
	// trigger element behaviour
	if (options.trigger)
		jQuery(options.trigger).click(moveFunction);
	
	return this;

	
   
	// moves the options
	function moveOptions(from, toSel, beforeMove, afterMove) {
      if (beforeMove && !beforeMove())
			return;
		
		jQuery("option:selected", from).each(function() {
         jQuery(this)
            .attr("selected", false)
            .appendTo(toSel);
      });
		
		afterMove && afterMove();
   }
	
	
	// selects all child options
	function selectChildOptions($select) {
		$select.children("option").each(function() {
			this.selected = true;
		});
	}
};