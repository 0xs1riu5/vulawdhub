<?php

/**
 * Project:     XMLParser: A library for parsing XML feeds
 * File:        XMLParser.class.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.phpinsider.com/php/code/XMLParser/
 * @copyright 2004-2005 New Digital Group, Inc.
 * @author Monte Ohrt <monte at newdigitalgroup dot com>
 * @package XMLParser
 * @version 1.0-dev
 */


class XMLParser{

   /**
    * holds the expat object
    *
    * @var obj
    */
   var $xml_obj = null;

   /**
    * holds the output array
    *
    * @var array
    */
   var $output = array();

   /**
    * the XML file character set
    *
    * @var array
    */
   var $char_set = 'UTF-8';

    /**#@-*/
    /**
     * The class constructor.
     */
   function XMLParser(){ }


    /**
     * parse the XML file (or URL)
     *
     * @param string $path the XML file path, or URL
     */
   function parse($path){

       $this->output = array();

       $this->xml_obj = xml_parser_create($this->char_set);
       xml_set_object($this->xml_obj,$this);
       xml_set_character_data_handler($this->xml_obj, 'dataHandler');
       xml_set_element_handler($this->xml_obj, "startHandler", "endHandler");

       if (!($fp = fopen($path, "r"))) {
           die("Cannot open XML data file: $path");
           return false;
       }

       while ($data = fread($fp, 4096)) {
           if (!xml_parse($this->xml_obj, $data, feof($fp))) {
               die(sprintf("XML error: %s at line %d",
               xml_error_string(xml_get_error_code($this->xml_obj)),
               xml_get_current_line_number($this->xml_obj)));
               xml_parser_free($this->xml_obj);
           }
       }

       return $this->output;
   }

    /**
     * define the start tag handler
     *
     * @param obj $parser the expat parser object
     * @param string $name the XML tag name
     * @param array $attribs the XML tag attributes
     */
   function startHandler($parser, $name, $attribs){
       $_content = array('name' => $name);
       if(!empty($attribs))
         $_content['attrs'] = $attribs;
       array_push($this->output, $_content);
   }

    /**
     * define the tag data handler
     *
     * @param obj $parser the expat parser object
     * @param string $data the XML data
     */
   function dataHandler($parser, $data){
       if(!empty($data)) {
           $_output_idx = count($this->output) - 1;
           if(!isset($this->output[$_output_idx]['content']))
             $this->output[$_output_idx]['content'] = $data;
           else
             $this->output[$_output_idx]['content'] .= $data;
       }
   }

    /**
     * define the end tag handler
     *
     * @param obj $parser the expat parser object
     * @param string $name the XML tag name
     */
   function endHandler($parser, $name){
       if(count($this->output) > 1) {
           $_data = array_pop($this->output);
           $_output_idx = count($this->output) - 1;
           $this->output[$_output_idx]['child'][] = $_data;
       }
   }

}



//$parser = new XMLParser;
//
//
//$output = $parser->parse('index.xml');
//
//foreach ($output[0]['child'] as $node){
//	var_dump($node['child'][0]['content']);
//	var_dump($node['child'][1]['content']);
//	var_dump($node['child'][2]['content']);
//	var_dump($node['child'][3]['content']);
//	var_dump($node['child'][4]['content']);
//	var_dump($node['child'][5]['content']);
//}


?>
