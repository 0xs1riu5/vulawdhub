<?php

/**
 *  Sanitizes data so that Cross Site Scripting Hacks can be prevented.
 *
 *  @package    XSS_Clean
 *  @author     EllisLab Dev Team
 *  @copyright  Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 *  @license    http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *  @link       http://codeigniter.com
 */
class XSS_Clean
{

	/**
	 * Random Hash for protecting URLs
	 *
	 * @var string
	 *
     *  @access private
	 */
	var $_xss_hash =	'';

	/**
	 * List of never allowed strings
	 *
	 * @var array
	 *
     *  @access private
	 */
	var $_never_allowed_str =	array(
		'document.cookie'	=> '[removed]',
		'document.write'	=> '[removed]',
		'.parentNode'		=> '[removed]',
		'.innerHTML'		=> '[removed]',
		'window.location'	=> '[removed]',
		'-moz-binding'		=> '[removed]',
		'<!--'				=> '&lt;!--',
		'-->'				=> '--&gt;',
		'<![CDATA['			=> '&lt;![CDATA[',
		'<comment>'			=> '&lt;comment&gt;'
	);

	/**
	 * List of never allowed regex replacement
	 *
	 * @var array
	 *
     *  @access private
	 */
	var $_never_allowed_regex = array(
		'javascript\s*:',
		'expression\s*(\(|&\#40;)', // CSS and IE
		'vbscript\s*:', // IE, surprise!
		'Redirect\s+302',
		"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
	);


    /**
     *  Sanitizes submitted data so that Cross Site Scripting Hacks can be prevented.
     *
     *  This class is taken from the {@link http://codeigniter.com/ CodeIgniter PHP Framework},
     *  version 2.1.2.
     *
     *  <i>This method is automatically run for each control when calling {@link Zebra_Form::validate() validate()}, unless
     *  specifically disabled by {@link Zebra_Form_Control::disable_xss_filters() disable_xss_filters()})!</i>
     *
     *  <b>Following is the original documentation of the class, as found in CodeIgniter:</b>
     *
     *  Sanitizes data so that Cross Site Scripting Hacks can be prevented. This function does a fair amount of work but
     *  it is extremely thorough, designed to prevent even the most obscure XSS attempts. Nothing is ever 100% foolproof,
     *  of course, but I haven't been able to get anything passed the filter.
     *
     *  Note: This function should only be used to deal with data upon submission. It's not something that should be used
     *  for general runtime processing.
     *
     *  This function was based in part on some code and ideas I got from Bitflux:
     *  {@link http://blog.bitflux.ch/wiki/XSS_Prevention}
     *
     *  To help develop this script I used this great list of vulnerabilities along with a few other hacks I've
     *  harvested from examining vulnerabilities in other programs: {@link http://ha.ckers.org/xss.html}
     *
     *  @param  string  $str    String to be filtered
     *
     *  @return string          Returns filtered string
     */
	function sanitize($str)
	{

		// Remove Invisible Characters
		$str = $this->_remove_invisible_characters($str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Use rawurldecode() so it does not remove plus signs
		 */
		$str = rawurldecode($str);

		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 */
		$str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
		$str = preg_replace_callback('/<\w+.*?(?=>|<|$)/si', array($this, '_decode_entity'), $str);

		// Remove Invisible Characters Again!
		$str = $this->_remove_invisible_characters($str);

		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja	vascript
		 * NOTE: we deal with spaces between characters later.
		 * NOTE: preg_replace was found to be amazingly slow here on
		 * large blocks of data, so we use str_replace.
		 */
		$str = str_replace("\t", ' ', $str);

		// Capture converted string for later comparison
		$converted_string = $str;

		// Remove Strings that are never allowed
		$str = $this->_do_never_allowed($str);

		/*
		 * Makes PHP tags safe
		 *
		 * Note: XML tags are inadvertently replaced too:
		 *
		 * <?xml
		 *
		 * But it doesn't seem to pose a problem.
		 */
		$str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str);

		/*
		 * Compact any exploded words
		 *
		 * This corrects words like:  j a v a s c r i p t
		 * These words are compacted back to their correct state.
		 */
		$words = array(
			'javascript', 'expression', 'vbscript', 'script', 'base64',
			'applet', 'alert', 'document', 'write', 'cookie', 'window'
		);


		foreach ($words as $word)
		{
			$word = implode('\s*', str_split($word)).'\s*';

			// We only want to do this when it is followed by a non-word character
			// That way valid stuff like "dealer to" does not become "dealerto"
			$str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
		}

		/*
		 * Remove disallowed Javascript in links or img tags
		 * We used to do some version comparisons and use of stripos for PHP5,
		 * but it is dog slow compared to these simplified non-capturing
		 * preg_match(), especially if the pattern exists in the string
		 */
		do
		{
			$original = $str;

			if (preg_match('/<a/i', $str))
			{
				$str = preg_replace_callback('#<a\s+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
			}

			if (preg_match('/<img/i', $str))
			{
				$str = preg_replace_callback('#<img\s+([^>]*?)(?:\s?/?\>|$)#si', array($this, '_js_img_removal'), $str);
			}

			if (preg_match('/script|xss/i', $str))
			{
				$str = preg_replace('#</*(?:script|xss).*?\>#si', '[removed]', $str);
			}
		}
		while ($original !== $str);

		unset($original);

		// Remove evil attributes such as style, onclick and xmlns
		$str = $this->_remove_evil_attributes($str, false);

		/*
		 * Sanitize naughty HTML elements
		 *
		 * If a tag containing any of the words in the list
		 * below is found, the tag gets converted to entities.
		 *
		 * So this: <blink>
		 * Becomes: &lt;blink&gt;
		 */
		$naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
		$str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);

		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed. Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example:	eval('some code')
		 * Becomes:	eval&#40;'some code'&#41;
		 */
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
					'\\1\\2&#40;\\3&#41;',
					$str);

		// Final clean up
		// This adds a bit of extra precaution in case
		// something got through the above filters
		$str = $this->_do_never_allowed($str);

		return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Random Hash for protecting URLs
	 *
	 * @return	string
	 *
     *  @access private
	 */
	function xss_hash()
	{
		if ($this->_xss_hash === '')
		{
			mt_srand();
			$this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
		}

		return $this->_xss_hash;
	}

	// --------------------------------------------------------------------

	/**
	 * HTML Entities Decode
	 *
	 * This function is a replacement for html_entity_decode()
	 *
	 * The reason we are not using html_entity_decode() by itself is because
	 * while it is not technically correct to leave out the semicolon
	 * at the end of an entity most browsers will still interpret the entity
	 * correctly. html_entity_decode() does not convert entities without
	 * semicolons, so we are left with our own little solution here. Bummer.
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 *
     *  @access private
	 */
	function entity_decode($str, $charset = NULL)
	{
		if (strpos($str, '&') === FALSE)
		{
			return $str;
		}

		if (empty($charset))
		{
			$charset = 'UTF-8';
		}

		$str = html_entity_decode($str, ENT_COMPAT, $charset);
		$str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
		return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
	}

	// ----------------------------------------------------------------

	/**
	 * Compact Exploded Words
	 *
	 * Callback function for xss_clean() to remove whitespace from
	 * things like j a v a s c r i p t
	 *
	 * @param	array
	 * @return	string
	 *
     *  @access private
	 */
	function _compact_exploded_words($matches)
	{
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}

	// --------------------------------------------------------------------

	/**
	 * Remove Evil HTML Attributes (like event handlers and style)
	 *
	 * It removes the evil attribute and either:
	 * 	- Everything up until a space
	 *		For example, everything between the pipes:
	 *		<a |style=document.write('hello');alert('world');| class=link>
	 * 	- Everything inside the quotes
	 *		For example, everything between the pipes:
	 *		<a |style="document.write('hello'); alert('world');"| class="link">
	 *
	 * @param string $str The string to check
	 * @param boolean $is_image TRUE if this is an image
	 * @return string The string with the evil attributes removed
	 *
     *  @access private
	 */
	function _remove_evil_attributes($str, $is_image)
	{
		// All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
		$evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction');

		if ($is_image === TRUE)
		{
			/*
			 * Adobe Photoshop puts XML metadata into JFIF images,
			 * including namespacing, so we have to allow this for images.
			 */
			unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
		}

		do {
			$count = 0;
			$attribs = array();

			// find occurrences of illegal attribute strings without quotes
			preg_match_all('/('.implode('|', $evil_attributes).')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);

			foreach ($matches as $attr)
			{

				$attribs[] = preg_quote($attr[0], '/');
			}

			// find occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
			preg_match_all('/('.implode('|', $evil_attributes).')\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is', $str, $matches, PREG_SET_ORDER);

			foreach ($matches as $attr)
			{
				$attribs[] = preg_quote($attr[0], '/');
			}

			// replace illegal attribute strings that are inside an html tag
			if (count($attribs) > 0)
			{
				$str = preg_replace('/<(\/?[^><]+?)([^A-Za-z<>\-])(.*?)('.implode('|', $attribs).')(.*?)([\s><])([><]*)/i', '<$1 $3$5$6$7', $str, -1, $count);
			}

		} while ($count);

		return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Sanitize Naughty HTML
	 *
	 * Callback function for xss_clean() to remove naughty HTML elements
	 *
	 * @param	array
	 * @return	string
	 *
     *  @access private
	 */
	function _sanitize_naughty_html($matches)
	{
		return '&lt;'.$matches[1].$matches[2].$matches[3] // encode opening brace
			// encode captured opening or closing brace to prevent recursive vectors:
			.str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);
	}

	// --------------------------------------------------------------------

	/**
	 * JS Link Removal
	 *
	 * Callback function for xss_clean() to sanitize links
	 * This limits the PCRE backtracks, making it more performance friendly
	 * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
	 * PHP 5.2+ on link-heavy strings
	 *
	 * @param	array
	 * @return	string
	 *
     *  @access private
	 */
	function _js_link_removal($match)
	{
		return str_replace($match[1],
					preg_replace('#href=.*?(?:alert\(|alert&\#40;|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
							'',
							$this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]))
					),
					$match[0]);
	}

	// --------------------------------------------------------------------

	/**
	 * JS Image Removal
	 *
	 * Callback function for xss_clean() to sanitize image tags
	 * This limits the PCRE backtracks, making it more performance friendly
	 * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
	 * PHP 5.2+ on image tag heavy strings
	 *
	 * @param	array
	 * @return	string
	 *
     *  @access private
	 */
	function _js_img_removal($match)
	{
		return str_replace($match[1],
					preg_replace('#src=.*?(?:alert\(|alert&\#40;|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
							'',
							$this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]))
					),
					$match[0]);
	}

	// --------------------------------------------------------------------

	/**
	 * Attribute Conversion
	 *
	 * Used as a callback for XSS Clean
	 *
	 * @param	array
	 * @return	string
	 *
     *  @access private
	 */
	function _convert_attribute($match)
	{
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}

	// --------------------------------------------------------------------

	/**
	 * Filter Attributes
	 *
	 * Filters tag attributes for consistency and safety
	 *
	 * @param	string
	 * @return	string
	 *
     *  @access private
	 */
	function _filter_attributes($str)
	{
		$out = '';
		if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$out .= preg_replace('#/\*.*?\*/#s', '', $match);
			}
		}

		return $out;
	}

	// --------------------------------------------------------------------

	/**
	 * HTML Entity Decode Callback
	 *
	 * Used as a callback for XSS Clean
	 *
	 * @param	array
	 * @return	string
	 *
     *  @access private
	 */
	function _decode_entity($match)
	{
		return $this->entity_decode($match[0], strtoupper('UTF-8'));
	}

	// --------------------------------------------------------------------

	/**
	 * Validate URL entities
	 *
	 * Called by xss_clean()
	 *
	 * @param 	string
	 * @return 	string
	 *
     *  @access private
	 */
	function _validate_entities($str)
	{
		/*
		 * Protect GET variables in URLs
		 */

		// 901119URL5918AMP18930PROTECT8198
		$str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->xss_hash().'\\1=\\2', $str);

		/*
		 * Validate standard character entities
		 *
		 * Add a semicolon if missing.  We do this to enable
		 * the conversion of entities to ASCII later.
		 */
		$str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', '\\1;\\2', $str);

		/*
		 * Validate UTF16 two byte encoding (x00)
		 *
		 * Just as above, adds a semicolon if missing.
		 */
		$str = preg_replace('#(&\#x?)([0-9A-F]+);?#i', '\\1\\2;', $str);

		/*
		 * Un-Protect GET variables in URLs
		 */
		return str_replace($this->xss_hash(), '&', $str);
	}

	// ----------------------------------------------------------------------

	/**
	 * Do Never Allowed
	 *
	 * A utility function for xss_clean()
	 *
	 * @param 	string
	 * @return 	string
	 *
     *  @access private
	 */
	function _do_never_allowed($str)
	{
		$str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

		foreach ($this->_never_allowed_regex as $regex)
		{
			$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
		}

		return $str;
	}

	/**
	 * Remove Invisible Characters
	 *
	 * This prevents sandwiching null characters
	 * between ascii characters, like Java\0script.
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function _remove_invisible_characters($str)
	{
		static $non_displayables;

		if ( ! isset($non_displayables))
		{
			// every control character except newline (dec 10), carriage return (dec 13), and horizontal tab (dec 09),
			$non_displayables = array(
										'/%0[0-8bcef]/',			// url encoded 00-08, 11, 12, 14, 15
										'/%1[0-9a-f]/',				// url encoded 16-31
										'/[\x00-\x08]/',			// 00-08
										'/\x0b/', '/\x0c/',			// 11, 12
										'/[\x0e-\x1f]/'				// 14-31
									);
		}

		do
		{
			$cleaned = $str;
			$str = preg_replace($non_displayables, '', $str);
		}
		while ($cleaned != $str);

		return $str;
	}

}

?>