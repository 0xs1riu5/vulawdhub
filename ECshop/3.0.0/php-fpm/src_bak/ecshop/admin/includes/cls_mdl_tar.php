<?php
/*
=======================================================================
Name:
    tar Class

Author:
    Josh Barger <joshb@npt.com>

Description:
    This class reads and writes Tape-Archive (TAR) Files and Gzip
    compressed TAR files, which are mainly used on UNIX systems.
    This class works on both windows AND unix systems, and does
    NOT rely on external applications!! Woohoo!

Usage:
    Copyright (C) 2002  Josh Barger

    This library is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.

    This library is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details at:
        http://www.gnu.org/copyleft/lesser.html

    If you use this script in your application/website, please
    send me an e-mail letting me know about it :)

Bugs:
    Please report any bugs you might find to my e-mail address
    at joshb@npt.com.  If you have already created a fix/patch
    for the bug, please do send it to me so I can incorporate it into my release.

Version History:
    1.0    04/10/2002    - InitialRelease

    2.0    04/11/2002    - Merged both tarReader and tarWriter
                  classes into one
                - Added support for gzipped tar files
                  Remember to name for .tar.gz or .tgz
                  if you use gzip compression!
                  :: THIS REQUIRES ZLIB EXTENSION ::
                - Added additional comments to
                  functions to help users
                - Added ability to remove files and
                  directories from archive
    2.1    04/12/2002    - Fixed serious bug in generating tar
                - Created another example file
                - Added check to make sure ZLIB is
                  installed before running GZIP
                  compression on TAR
    2.2    05/07/2002    - Added automatic detection of Gzipped
                  tar files (Thanks go to Jrgen Falch
                  for the idea)
                - Changed "private" functions to have
                  special function names beginning with
                  two underscores
=======================================================================
*/

class mdl_tar {
    // Unprocessed Archive Information
    var $filename;
    var $isGzipped;
    var $tar_file;

    // Processed Archive Information
    var $files;
    var $directories;
    var $numFiles;
    var $numDirectories;
    var $targetDirectory;
    var $tarFile;


    // Class Constructor -- Does nothing...
//  function tar() {
//    return true;
//  }


    // Computes the unsigned Checksum of a file's header
    // to try to ensure valid file
    // PRIVATE ACCESS FUNCTION
    function __computeUnsignedChecksum($bytestring) {
        for($i=0; $i<512; $i++)
            $unsigned_chksum += ord($bytestring[$i]);
        for($i=0; $i<8; $i++)
            $unsigned_chksum -= ord($bytestring[148 + $i]);
        $unsigned_chksum += ord(" ") * 8;

        return $unsigned_chksum;
    }


    // Converts a NULL padded string to a non-NULL padded string
    // PRIVATE ACCESS FUNCTION
    function __parseNullPaddedString($string) {
        $position = strpos($string,chr(0));
        return substr($string,0,$position);
    }


    function __fileSubstr(&$fp,$offset,$len){
        fseek($fp,$offset);
        return fread($fp,$len);
    }

    // This function parses the current TAR file
    // PRIVATE ACCESS FUNCTION
    function __parseTar() {
        if(!$this->tar_file_name) return false;
        // Read Files from archive
        $tar_length = filesize($this->tar_file_name);
        $main_offset = 0;
        $this->tar_file = fopen($this->tar_file_name,'r');
        while($main_offset < $tar_length) {
            // If we read a block of 512 nulls, we are at the end of the archive
            if($this->__fileSubstr($this->tar_file,$main_offset,512) == str_repeat(chr(0),512))
                break;

            // Parse file name
            $file_name        = $this->__parseNullPaddedString($this->__fileSubstr($this->tar_file,$main_offset,100));

            // Parse the file mode
            $file_mode        = $this->__fileSubstr($this->tar_file,$main_offset + 100,8);

            // Parse the file user ID
            $file_uid        = octdec($this->__fileSubstr($this->tar_file,$main_offset + 108,8));

            // Parse the file group ID
            $file_gid        = octdec($this->__fileSubstr($this->tar_file,$main_offset + 116,8));

            // Parse the file size
            $file_size        = octdec($this->__fileSubstr($this->tar_file,$main_offset + 124,12));

            // Parse the file update time - unix timestamp format
            $file_time        = octdec($this->__fileSubstr($this->tar_file,$main_offset + 136,12));

            // Parse Checksum
            $file_chksum        = octdec($this->__fileSubstr($this->tar_file,$main_offset + 148,6));

            // Parse user name
            $file_uname        = $this->__parseNullPaddedString($this->__fileSubstr($this->tar_file,$main_offset + 265,32));

            // Parse Group name
            $file_gname        = $this->__parseNullPaddedString($this->__fileSubstr($this->tar_file,$main_offset + 297,32));

            // Make sure our file is valid
            if($this->__computeUnsignedChecksum($this->__fileSubstr($this->tar_file,$main_offset,512)) != $file_chksum)
                return false;

            // Parse File Contents
            //$file_contents        = $this->__fileSubstr($this->tar_file,$main_offset + 512,$file_size);

            /*    ### Unused Header Information ###
                $activeFile["typeflag"]        = substr($this->tar_file,$main_offset + 156,1);
                $activeFile["linkname"]        = substr($this->tar_file,$main_offset + 157,100);
                $activeFile["magic"]        = substr($this->tar_file,$main_offset + 257,6);
                $activeFile["version"]        = substr($this->tar_file,$main_offset + 263,2);
                $activeFile["devmajor"]        = substr($this->tar_file,$main_offset + 329,8);
                $activeFile["devminor"]        = substr($this->tar_file,$main_offset + 337,8);
                $activeFile["prefix"]        = substr($this->tar_file,$main_offset + 345,155);
                $activeFile["endheader"]    = substr($this->tar_file,$main_offset + 500,12);
            */

            if($file_size > 0) {
                // Increment number of files
                $this->numFiles++;

                // Create us a new file in our array
                $activeFile = &$this->files[];

                // Asign Values
                $activeFile["name"]        = $file_name;
                $activeFile["mode"]        = $file_mode;
                $activeFile["size"]        = $file_size;
                $activeFile["time"]        = $file_time;
                $activeFile["member_id"]        = $file_uid;
                $activeFile["group_id"]        = $file_gid;
                $activeFile["user_name"]    = $file_uname;
                $activeFile["group_name"]    = $file_gname;
                $activeFile["checksum"]        = $file_chksum;
                $activeFile["offset"]        = $main_offset + 512;
                //$activeFile["file"]        = $file_contents;

            } else {
                // Increment number of directories
                $this->numDirectories++;

                // Create a new directory in our array
                $activeDir = &$this->directories[];

                // Assign values
                $activeDir["name"]        = $file_name;
                $activeDir["mode"]        = $file_mode;
                $activeDir["time"]        = $file_time;
                $activeDir["member_id"]        = $file_uid;
                $activeDir["group_id"]        = $file_gid;
                $activeDir["user_name"]        = $file_uname;
                $activeDir["group_name"]    = $file_gname;
                $activeDir["checksum"]        = $file_chksum;
            }

            // Move our offset the number of blocks we have processed
            $main_offset += 512 + (ceil($file_size / 512) * 512);
        }

        return true;
    }


    // Read a non gzipped tar file in for processing
    // PRIVATE ACCESS FUNCTION
    function __readTar($filename='') {
        // Set the filename to load
        if(!$filename)
            $filename = $this->filename;

        // Read in the TAR file
        $fp = fopen($filename,'r');
        $header = fread($fp,3);
        fclose($fp);
        $this->tar_file_name = $filename;

        if($header[0] == chr(31) && $header[1] == chr(139) && $header == chr(8)) {
            return false;
        }

        // Parse the TAR file
        $this->__parseTar();

        return true;
    }


    // Generates a TAR file from the processed data
    // PRIVATE ACCESS FUNCTION
    function __generateTAR($type='file') {
        // Clear any data currently in $this->tar_file    
        unset($this->tar_file);

        // Generate Records for each directory, if we have directories
        if($this->numDirectories > 0) {
            foreach($this->directories as $key => $information) {
                unset($header);

                // Generate tar header for this directory
                // Filename, Permissions, UID, GID, size, Time, checksum, typeflag, linkname, magic, version, user name, group name, devmajor, devminor, prefix, end
                $header .= str_pad($information["name"],100,chr(0));
                $header .= str_pad(decoct($information["mode"]),7,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(substr(decoct($information["member_id"]),0,7),7,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(decoct($information["group_id"]),7,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(decoct(0),11,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(decoct($information["time"]),11,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_repeat(" ",8);
                $header .= "5";
                $header .= str_repeat(chr(0),100);
                $header .= str_pad("ustar",6,chr(32));
                $header .= chr(32) . chr(0);
                $header .= str_pad("",32,chr(0));
                $header .= str_pad("",32,chr(0));
                $header .= str_repeat(chr(0),8);
                $header .= str_repeat(chr(0),8);
                $header .= str_repeat(chr(0),155);
                $header .= str_repeat(chr(0),12);

                // Compute header checksum
                $checksum = str_pad(decoct($this->__computeUnsignedChecksum($header)),6,"0",STR_PAD_LEFT);
                for($i=0; $i<6; $i++) {
                    $header[(148 + $i)] = substr($checksum,$i,1);
                }
                $header[154] = chr(0);
                $header[155] = chr(32);

                // Add new tar formatted data to tar file contents
                //$this->tar_file .= $header;
                $this->output($header,$type);

            }
        }

        // Generate Records for each file, if we have files (We should...)
        if($this->numFiles > 0) {
            foreach($this->files as $key => $information) {
                unset($header);

                // Generate the TAR header for this file
                // Filename, Permissions, UID, GID, size, Time, checksum, typeflag, linkname, magic, version, user name, group name, devmajor, devminor, prefix, end
                $header .= str_pad($information["name"],100,chr(0));
                $header .= str_pad(decoct($information["mode"]),7,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(substr(decoct($information["member_id"]),0,7),7,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(decoct($information["group_id"]),7,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(decoct($information["size"]),11,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_pad(decoct($information["time"]),11,"0",STR_PAD_LEFT) . chr(0);
                $header .= str_repeat(" ",8);
                $header .= "0";
                $header .= str_repeat(chr(0),100);
                $header .= str_pad("ustar",6,chr(32));
                $header .= chr(32) . chr(0);
                $header .= str_pad($information["user_name"],32,chr(0));    // How do I get a file's user name from PHP?
                $header .= str_pad($information["group_name"],32,chr(0));    // How do I get a file's group name from PHP?
                $header .= str_repeat(chr(0),8);
                $header .= str_repeat(chr(0),8);
                $header .= str_repeat(chr(0),155);
                $header .= str_repeat(chr(0),12);

                // Compute header checksum
                $checksum = str_pad(decoct($this->__computeUnsignedChecksum($header)),6,"0",STR_PAD_LEFT);
                for($i=0; $i<6; $i++) {
                    $header[(148 + $i)] = substr($checksum,$i,1);
                }
                $header[154] = chr(0);
                $header[155] = chr(32);

                // Pad file contents to byte count divisible by 512
                if($information["contents"]){
                    $contents = $information["contents"];
                }elseif($information["srcfile"]){
                        $contents = $this->_readFile($information["srcfile"]);
                }else{
                    $contents = $this->_readFile(getcwd().'/'.$information["name"]);
                }
                $file_contents = str_pad($contents,(ceil($information["size"] / 512) * 512),chr(0));

                // Add new tar formatted data to tar file contents
                //$this->tar_file .= $header . $file_contents;
                $this->output($header . $file_contents,$type);
            }
        }

        // Add 512 bytes of NULLs to designate EOF
        //$this->tar_file .= str_repeat(chr(0),512);
        $this->output(str_repeat(chr(0),512),$type);
        return true;
    }
    function _readFile($file){
        if(is_file($file) && filesize($file)){
            $handle=fopen($file, "r");
            $contents = "";
            while (!feof($handle)) {
              $contents .= fread($handle, 500000);
            }
            fclose($handle);
            return $contents;
        }
    }
    function output($string,$type){
        switch($type){
            case 'file':
                if(!$this->tar_file) $this->tar_file = fopen($this->filename,'wb');
                fwrite($this->tar_file,$string);
                break;
            case 'var':
                $this->tar_file .=$string;
                break;
            case 'output':
                echo $string;
                break;
        }
    }


    // Open a TAR file
    function openTAR($filename,$target) {
        // Clear any values from previous tar archives
        unset($this->filename);
        unset($this->isGzipped);
        unset($this->tar_file);
        unset($this->files);
        unset($this->directories);
        unset($this->numFiles);
        unset($this->numDirectories);

        // If the tar file doesn't exist...
        if(!file_exists($filename))
            return false;

        $this->filename = $filename;

        // Parse this file
        $this->targetDirectory = $target;
        $this->__readTar();

        return true;
    }

    function closeTAR(){
        if($this->tar_file) fclose($this->tar_file);
        return true;
    }


    // Appends a tar file to the end of the currently opened tar file
    function appendTar($filename) {
        // If the tar file doesn't exist...
        if(!file_exists($filename))
            return false;

        $this->__readTar($filename);

        return true;
    }


    // Retrieves information about a file in the current tar archive
    function getFile($filename) {
        if($this->numFiles > 0) {
            foreach($this->files as $key => $information) {
                if($information["name"] == $filename)
                    return $information;
            }
        }

        return false;
    }

    //Retrieves file contents
    function getContents($information){
        return $this->__fileSubstr($this->tar_file,$information['offset'],$information['size']);
    }


    // Retrieves information about a directory in the current tar archive
    function getDirectory($dirname) {
        if($this->numDirectories > 0) {
            foreach($this->directories as $key => $information) {
                if($information["name"] == $dirname)
                    return $information;
            }
        }

        return false;
    }


    // Check if this tar archive contains a specific file
    function containsFile($filename) {
        if($this->numFiles > 0) {
            foreach($this->files as $key => $information) {
                if($information["name"] == $filename)
                    return true;
            }
        }

        return false;
    }


    // Check if this tar archive contains a specific directory
    function containsDirectory($dirname) {
        if($this->numDirectories > 0) {
            foreach($this->directories as $key => $information) {
                if($information["name"] == $dirname)
                    return true;
            }
        }

        return false;
    }


    // Add a directory to this tar archive
    function addDirectory($dirname) {
        if (strpos($dirname, '.\\') === 0 || strpos($dirname, './') === 0) {
            $dirname = substr($dirname, 2);
        }
        if(!file_exists($dirname))
            return false;

        // Get directory information
        $file_information = stat($dirname);

        // Add directory to processed data
        $this->numDirectories++;
        $activeDir        = &$this->directories[];
        $activeDir["name"]    = $dirname;
        $activeDir["mode"]    = $file_information["mode"];
        $activeDir["time"]    = $file_information["time"];
        $activeDir["member_id"]    = $file_information["uid"];
        $activeDir["group_id"]    = $file_information["gid"];
        $activeDir["checksum"]    = $checksum;

        return true;
    }


    // Add a file to the tar archive
    function addFile($filename,$file_contents=false,$file_information=null) {
        if (strpos($filename, '.\\') === 0 || strpos($filename, './') === 0) {
            $filename = substr($filename, 2);
        }
        // Make sure the file we are adding exists!
        if(!file_exists($filename) && !$file_contents)
            return false;

        // Make sure there are no other files in the archive that have this same filename
        if($this->containsFile($filename))
            return false;

        // Read in the file's contents
        if(!$file_contents){
            $file_information = stat($filename);
            //$file_contents = file_get_contents($filename);
        }else{
            if(count($this->files)>0){
                $file_information = $this->files[0];
            }
//      $file_information['mode'] = 33188;
            $file_information['size'] = strlen($file_contents);
            $file_information['time'] = time();
        }

        // Add file to processed data
        $this->numFiles++;
        $activeFile            = &$this->files[];
        $activeFile["name"]        = $filename;
        $activeFile["mode"]        = $file_information["mode"];
        $activeFile["member_id"]        = $file_information["uid"];
        $activeFile["group_id"]        = $file_information["gid"];
        $activeFile["size"]        = $file_information["size"];
        $activeFile["time"]        = $file_information["mtime"];
        $activeFile["checksum"]        = $checksum;
        $activeFile["user_name"]    = "";
        $activeFile["group_name"]    = "";
        if($file_contents){
            $activeFile["contents"]    = $file_contents;
        }

        return true;
    }


    // Remove a file from the tar archive
    function removeFile($filename) {
        if($this->numFiles > 0) {
            foreach($this->files as $key => $information) {
                if($information["name"] == $filename) {
                    $this->numFiles--;
                    unset($this->files[$key]);
                    return true;
                }
            }
        }

        return false;
    }


    // Remove a directory from the tar archive
    function removeDirectory($dirname) {
        if($this->numDirectories > 0) {
            foreach($this->directories as $key => $information) {
                if($information["name"] == $dirname) {
                    $this->numDirectories--;
                    unset($this->directories[$key]);
                    return true;
                }
            }
        }

        return false;
    }


    // Write the currently loaded tar archive to disk
    function saveTar() {
        if(!$this->filename)
            return false;

        // Write tar to current file using specified gzip compression
        //$this->toTar($this->filename,$this->isGzipped);
        $this->__generateTar();

        return true;
    }


    function getTar($type='output'){
        $this->__generateTar($type);
        if($type!='output') {
            return $this->tar_file;
        }
    }

    // Saves tar archive to a different file than the current file
    function toTar($filename,$useGzip) {
        if(!$filename)
            return false;
        return file_put_contents($filename,$this->getTar($useGzip));
    }

    function gzcompressfile($source,$level=false){ 
        $dest=$source.'.gz'; 
        $mode='wb'.$level; 
        $error=false; 
        if($fp_out=gzopen($dest,$mode)){ 
           if($fp_in=fopen($source,'rb')){ 
               while(!feof($fp_in)) 
                   gzwrite($fp_out,fread($fp_in,1024*512)); 
               fclose($fp_in); 
               } 
             else $error=true; 
           gzclose($fp_out); 
           } 
         else $error=true; 
       if($error) return false; 
         else return $dest; 
   }

}

?>
