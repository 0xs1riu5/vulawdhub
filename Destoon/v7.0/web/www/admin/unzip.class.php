<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class unzip {
	var $datasec = array();
	var $ctrl_dir = array();
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $old_offset = 0;

	function extract_zip($zipfile, $dir) {
		if(substr($dir, -1) != '/') $dir .= '/';
		if(function_exists('zip_open')) {
			$zip = zip_open($zipfile); 
			if($zip) {			 
				while($zip_entry = zip_read($zip)) {
					if(zip_entry_filesize($zip_entry) > 0) {						
						if(zip_entry_open($zip, $zip_entry, "r")) {
							$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							file_put($dir.zip_entry_name($zip_entry), $buf);
							zip_entry_close($zip_entry);
						}
					} else {
						dir_create($dir.zip_entry_name($zip_entry));
					}			 
				}			 
				zip_close($zip);			 
			}
		} else {
			$array = $this->list_zip($zipfile);
			$count = count($array);
			$f = 0;
			$d = 0;
			for($i = 0; $i < $count; $i++) {
				if($array[$i]['folder'] == 0) {
					if($this->extract_file($zipfile, $dir, $i) > 0) $f++;
				} else {
					$d++;
				}
			}
		}
		return true;
	}

	function list_zip($zip_name) {
		$zip = @fopen($zip_name, 'rb');
		if(!$zip) return 0;
		$centd = $this->rc_dir($zip, $zip_name);
		@rewind($zip);
		@fseek($zip, $centd['offset']);
		$ret = array();
		for($i = 0; $i < $centd['entries']; $i++) {
			$header = $this->rcf_header($zip);
			$header['index'] = $i;
			$info['filename'] = $header['filename'];
			$info['stored_filename'] = $header['stored_filename'];
			$info['size'] = $header['size'];
			$info['compressed_size'] = $header['compressed_size'];
			$info['crc'] = strtoupper(dechex( $header['crc']));
			$info['mtime'] = $header['mtime'];
			$info['comment'] = $header['comment'];
			$info['folder'] = ($header['external'] == 0x41FF0010 || $header['external'] == 16) ? 1 : 0;
			$info['index'] = $header['index'];
			$info['status'] = $header['status'];
			$ret[] = $info;
			unset($header);
		}
		return $ret;
	}

	function extract_file($zn, $to, $index = array(-1)) {
		$ok = 0;
		$zip = @fopen($zn,'rb');
		if(!$zip) return -1;
		$cdir = $this->rc_dir($zip,$zn);
		$pos_entry = $cdir['offset'];
		if(!is_array($index)) $index = array($index);
		for($i = 0; isset($index[$i]); $i++) {
			if(intval($index[$i]) != $index[$i] || $index[$i] > $cdir['entries']) return -1;
		}
		for($i = 0; $i < $cdir['entries']; $i++) {
			@fseek($zip, $pos_entry);
			$header = $this->rcf_header($zip);
			$header['index'] = $i;
			$pos_entry = ftell($zip);
			@rewind($zip);
			fseek($zip, $header['offset']);
			if(in_array("-1",$index) || in_array($i,$index)) $stat[$header['filename']] = $this->uncompress($header, $to, $zip);

		}
		fclose($zip);
		return $stat;
	}

	function rf_header($zip) { 
		$binary_data = fread($zip, 30);
		$data = unpack('vchk/vid/vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $binary_data);
		$header['filename'] = fread($zip, $data['filename_len']);
		$header['extra'] = $data['extra_len'] != 0 ? fread($zip, $data['extra_len']) : '';
		$header['compression'] = $data['compression'];
		$header['size'] = $data['size'];
		$header['compressed_size'] = $data['compressed_size'];
		$header['crc'] = $data['crc'];
		$header['flag'] = $data['flag'];
		$header['mdate'] = $data['mdate'];
		$header['mtime'] = $data['mtime'];
		if($header['mdate'] && $header['mtime']) {
			$hour = ($header['mtime'] & 0xF800) >> 11;
			$minute = ($header['mtime'] & 0x07E0) >> 5;
			$seconde = ($header['mtime'] & 0x001F)*2;
			$year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
			$month = ($header['mdate'] & 0x01E0) >> 5;
			$day = $header['mdate'] & 0x001F;
			$header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
		} else {
			$header['mtime'] = time();
		}
		$header['stored_filename'] = $header['filename'];
		$header['status'] = "ok";
		return $header;
	}

	function rcf_header($zip) {
		$binary_data = fread($zip, 46);
		$header = unpack('vchkid/vid/vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);
		$header['filename'] = $header['filename_len'] != 0 ? fread($zip,$header['filename_len']) : '';
		$header['extra'] = $header['extra_len'] != 0 ? fread($zip, $header['extra_len']) : '';
		$header['comment'] = $header['comment_len'] != 0 ? fread($zip, $header['comment_len']): '';
		if($header['mdate'] && $header['mtime']) {
			$hour = ($header['mtime'] & 0xF800) >> 11;
			$minute = ($header['mtime'] & 0x07E0) >> 5;
			$seconde = ($header['mtime'] & 0x001F)*2;
			$year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
			$month = ($header['mdate'] & 0x01E0) >> 5;
			$day = $header['mdate'] & 0x001F;
			$header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
		} else {
			$header['mtime'] = time();
		}
		$header['stored_filename'] = $header['filename'];
		$header['status'] = 'ok';
		if(substr($header['filename'], -1) == '/') $header['external'] = 0x41FF0010;
		return $header;
	}

	function rc_dir($zip, $zip_name) {
		$size = filesize($zip_name);
		$maximum_size = $size < 277 ? $size : 277;
		@fseek($zip, $size - $maximum_size);
		$pos = ftell($zip);
		$bytes = 0x00000000;
		while($pos < $size) {
			$byte = @fread($zip, 1);
			$bytes = ($bytes << 8) | Ord($byte);
			$pos++;
			if(substr(dechex($bytes), -8, 8) == '504b0506') break;
		}		
		$data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', fread($zip, 18));
		$centd['comment'] = $data['comment_size'] != 0 ? fread($zip, $data['comment_size']) : '';
		$centd['entries'] = $data['entries'];
		$centd['disk_entries'] = $data['disk_entries'];
		$centd['offset'] = $data['offset'];
		$centd['disk_start'] = $data['disk_start'];
		$centd['size'] = $data['size'];
		$centd['disk'] = $data['disk'];
		return $centd;
	}

	function uncompress($header, $to, $zip) {
		$header = $this->rf_header($zip);
		if(substr($to, -1) != "/") $to .= "/";
		if(!is_dir($to)) dir_create($to);
		$pth = explode("/", dirname($header['filename']));
		$pthss = '';
		for($i = 0; isset($pth[$i]); $i++) {
			if(!$pth[$i]) continue;
			$pthss .= $pth[$i]."/";
			if(!is_dir($to.$pthss)) dir_create($to.$pthss);
		}
		isset($header['external']) or $header['external'] = '';
		if(!($header['external'] == 0x41FF0010) && !($header['external'] == 16)) {
			if($header['compression'] == 0) {
				$fp = @fopen($to.$header['filename'], 'wb');
				if(!$fp) return -1;
				$size = $header['compressed_size'];
				while($size != 0) {
					$read_size = $size < 2048 ? $size : 2048;
					$buffer = fread($zip, $read_size);
					$binary_data = pack('a'.$read_size, $buffer);
					@fwrite($fp, $binary_data, $read_size);
					$size -= $read_size;
				}
				fclose($fp);
				if(DT_CHMOD) @chmod($to.$header['filename'], DT_CHMOD);
				touch($to.$header['filename'], $header['mtime']);
			} else {
				$fp = @fopen($to.$header['filename'].'.gz', 'wb');
				if(!$fp) return -1;
				$binary_data = pack('va1a1Va1a1', 0x8b1f, chr($header['compression']), chr(0x00), time(), chr(0x00), chr(3));
				fwrite($fp, $binary_data, 10);
				$size = $header['compressed_size'];
				while($size != 0) {
					$read_size = $size < 1024 ? $size : 1024;
					$buffer = fread($zip, $read_size);
					$binary_data = pack('a'.$read_size, $buffer);
					@fwrite($fp, $binary_data, $read_size);
					$size -= $read_size;
				}
				$binary_data = pack('VV', $header['crc'], $header['size']);
				fwrite($fp, $binary_data, 8);
				fclose($fp);
				$gzp = @gzopen($to.$header['filename'].'.gz', 'rb') or die('Can Not gzopen File');
				if(!$gzp) return -2;
				$fp = @fopen($to.$header['filename'], 'wb');
				if(!$fp) return -1;
				$size = $header['size'];
				while($size != 0) {
					$read_size = $size < 2048 ? $size : 2048;
					$buffer = gzread($gzp, $read_size);
					$binary_data = pack('a'.$read_size, $buffer);
					@fwrite($fp, $binary_data, $read_size);
					$size -= $read_size;
				}
				fclose($fp); 
				gzclose($gzp);
				if(DT_CHMOD) @chmod($to.$header['filename'], DT_CHMOD);
				touch($to.$header['filename'], $header['mtime']);
				@unlink($to.$header['filename'].'.gz');
			}
		}
		return true;
	}
}
?>