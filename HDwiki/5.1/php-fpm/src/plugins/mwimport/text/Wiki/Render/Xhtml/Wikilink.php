<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Wikilink rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Wikilink.php,v 1.22 2006/12/08 21:25:24 justinpatrin Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * This class renders wiki links in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 */
class Text_Wiki_Render_Xhtml_Wikilink extends Text_Wiki_Render {

    var $conf = array(
        'pages' => array(), // set to null or false to turn off page checks
        'view_url' => '',
        'new_url'  => 'index.php?doc-innerlink-%s',
        'new_text' => '?',
        'new_text_pos' => 'after', // 'before', 'after', or null/false
        'css' => 'innerlink',
        'css_new' => 'innerlink',
        'exists_callback' => array('Text_Wiki_Render_Xhtml_Wikilink','get_url') // call_user_func() callback
    );


    /**
    *
    * Renders a token into XHTML.
    *
    * @access public
    *
    * @param array $options The "options" portion of the token (second
    * element).
    *
    * @return string The text rendered from the token options.
    *
    */

    function token($options)
    {
	
	//var_dump($options);die;
        // make nice variable names (page, anchor, text)
        extract($options);

        // is there a "page existence" callback?
        // we need to access it directly instead of through
        // getConf() because we'll need a reference (for
        // object instance method callbacks).
        if (isset($this->conf['exists_callback'])) {
            $callback =& $this->conf['exists_callback'];
        } else {
        	$callback = false;
        }

        if ($callback) {
            // use the callback function
            $exists = call_user_func($callback, $page);
        } else {
            // no callback, go to the naive page array.
            $list = $this->getConf('pages');
            if (is_array($list)) {
                // yes, check against the page list
                $exists = in_array($page, $list);
            } else {
                // no, assume it exists
                $exists = true;
            }
        }

        $anchor = '#'.$this->urlEncode(substr($anchor, 1));

        // does the page exist?
        if ($exists) {

            // PAGE EXISTS.
            // link to the page view, but we have to build
            // the HREF.  we support both the old form where
            // the page always comes at the end, and the new
            // form that uses %s for sprintf()
            $href = $this->getConf('view_url');
	    if(!$href){
		$href = $exists;
	    }
            // get the CSS class and generate output
            //$css = ' class="'.$this->textEncode($this->getConf('css')).'"';
            $start = '<a'.$css.' href="'.$this->textEncode($href).'">';
            $end = '</a>';
	    if(in_array(substr($text,-3), array(jpg,png,gif,bmp))){
		$strtext = '<img src= '.$href.'>';
		//此处可以添加导入图片到数据库的操作。
	    }else{
		$strtext = $text;
	    }
	    $output = $start.$strtext.$end;
        } else {
            // PAGE DOES NOT EXIST.
            // link to a create-page url, but only if new_url is set
            $href = $this->getConf('new_url', null);

            // set the proper HREF
            if (! $href || trim($href) == '') {

                // no useful href, return the text as it is
                //TODO: This is no longer used, need to look closer into this branch
                $output = $text;

            } else {

                // yes, link to the new-page href, but we have to build
                // it.  we support both the old form where
                // the page always comes at the end, and the new
                // form that uses sprintf()
                if (strpos($href, '%s') === false) {
                    // use the old form
                    $href = $href . $this->urlEncode($page);
                } else {
                    // use the new form
                    $href = sprintf($href, $this->urlEncode($page));
                }
            }
            $css = ' class="'.$this->textEncode($this->getConf('css_new')).'"';
	    $new = $text;
	    $start = '';
            $end = '<a'.$css.' href="'.$this->textEncode($href).'">'.$this->textEncode($new).'</a>';
	    $output = $start.$end;
        }
	return $output;
    }

    function get_url($pic_title){
	$pos = strpos($pic_title,':');
	if($pos){
	    $pic_title = substr($pic_title,$pos+1);
	    if($pic_title){
		$hash = md5($pic_title);
		$img_path =  'mwimages/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . '/'.$pic_title;
		if(is_file($img_path)){
		    return $img_path;
		}
	    }
	}
	return FALSE;
    }
}
?>
