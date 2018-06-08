<?php
class zip{

 var $datasec, $ctrl_dir = array();
 var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
 var $old_offset = 0; var $dirs = Array(".");
 var $chk_zip=true;
 function zip(){
 	if (!function_exists('gzopen')){
		$this->chk_zip=false;
    }
 }
 function get_List($zip_name)
 {
   $zip = @fopen($zip_name, 'rb');
   if(!$zip) return(0);
   $centd = $this->ReadCentralDir($zip,$zip_name);

    @rewind($zip);
    @fseek($zip, $centd['offset']);

   for ($i=0; $i<$centd['entries']; $i++)
   {
    $header = $this->ReadCentralFileHeaders($zip);
    $header['index'] = $i;$info['filename'] = $header['filename'];
    $info['stored_filename'] = $header['stored_filename'];
    $info['size'] = $header['size'];$info['compressed_size']=$header['compressed_size'];
    $info['crc'] = strtoupper(dechex( $header['crc'] ));
    $info['mtime'] = $header['mtime']; $info['comment'] = $header['comment'];
    $info['folder'] = ($header['external']==0x41FF0010||$header['external']==16)?1:0;
    $info['index'] = $header['index'];$info['status'] = $header['status'];
    $ret[]=$info; unset($header);
   }
  return $ret;
 }

 function Add($files,$compact)
 {
  if(!is_array($files[0])) $files=Array($files);

  for($i=0;$files[$i];$i++){
    $fn = $files[$i];
    if(!in_Array(dirname($fn[0]),$this->dirs))
     $this->add_Dir(dirname($fn[0]));
    if(basename($fn[0]))
     $ret[basename($fn[0])]=$this->add_File($fn[1],$fn[0],$compact);
  }
  return $ret;
 }

 function get_file()
 {
   $data = implode('', $this -> datasec);
   $ctrldir = implode('', $this -> ctrl_dir);

   return $data . $ctrldir . $this -> eof_ctrl_dir .
    pack('v', sizeof($this -> ctrl_dir)).pack('v', sizeof($this -> ctrl_dir)).
    pack('V', strlen($ctrldir)) . pack('V', strlen($data)) . "\x00\x00";
 }

 function add_dir($name)
 {
   $name = str_replace("\\", "/", $name);
   $fr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00";

   $fr .= pack("V",0).pack("V",0).pack("V",0).pack("v", strlen($name) );
   $fr .= pack("v", 0 ).$name.pack("V", 0).pack("V", 0).pack("V", 0);
   $this -> datasec[] = $fr;

   $new_offset = strlen(implode("", $this->datasec));

   $cdrec = "\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00";
   $cdrec .= pack("V",0).pack("V",0).pack("V",0).pack("v", strlen($name) );
   $cdrec .= pack("v", 0 ).pack("v", 0 ).pack("v", 0 ).pack("v", 0 );
   $ext = "\xff\xff\xff\xff";
   $cdrec .= pack("V", 16 ).pack("V", $this -> old_offset ).$name;

   $this -> ctrl_dir[] = $cdrec;
   $this -> old_offset = $new_offset;
   $this -> dirs[] = $name;
 }

 function add_File($data, $name, $compact = 1)
 {
   $name     = str_replace('\\', '/', $name);
   $dtime    = dechex($this->DosTime());
	if($data==''&&file_exists($name)&&!is_dir($name)){
		$dfp=fopen($name,"rb");
		$data=fread($dfp,filesize($name));
		fclose($dfp);
	}
   $hexdtime = '\x' . $dtime[6] . $dtime[7].'\x'.$dtime[4] . $dtime[5]
     . '\x' . $dtime[2] . $dtime[3].'\x'.$dtime[0].$dtime[1];
   eval('$hexdtime = "' . $hexdtime . '";');

   if($compact)
   $fr = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$hexdtime;
   else $fr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00".$hexdtime;
   $unc_len = strlen($data); $crc = crc32($data);

   if($compact){
     $zdata = gzcompress($data); $c_len = strlen($zdata);
     $zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
   }else{
     $zdata = $data;
   }
   $c_len=strlen($zdata);
   $fr .= pack('V', $crc).pack('V', $c_len).pack('V', $unc_len);
   $fr .= pack('v', strlen($name)).pack('v', 0).$name.$zdata;

   $fr .= pack('V', $crc).pack('V', $c_len).pack('V', $unc_len);

   $this -> datasec[] = $fr;
   $new_offset        = strlen(implode('', $this->datasec));
   if($compact)
        $cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00";
   else $cdrec = "\x50\x4b\x01\x02\x14\x00\x0a\x00\x00\x00\x00\x00";
   $cdrec .= $hexdtime.pack('V', $crc).pack('V', $c_len).pack('V', $unc_len);
   $cdrec .= pack('v', strlen($name) ).pack('v', 0 ).pack('v', 0 );
   $cdrec .= pack('v', 0 ).pack('v', 0 ).pack('V', 32 );
   $cdrec .= pack('V', $this -> old_offset );

   $this -> old_offset = $new_offset;
   $cdrec .= $name;
   $this -> ctrl_dir[] = $cdrec;
   return true;
 }

 function DosTime() {
   $timearray = getdate();
   if ($timearray['year'] < 1980) {
     $timearray['year'] = 1980; $timearray['mon'] = 1;
     $timearray['mday'] = 1; $timearray['hours'] = 0;
     $timearray['minutes'] = 0; $timearray['seconds'] = 0;
   }
   return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) |     ($timearray['mday'] << 16) | ($timearray['hours'] << 11) |
    ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
 }

function Extract ( $zn, $to, $index = Array(-1) )
	{
		$ok = 0;
		$zip = @fopen($zn,'rb');
		if(!$zip) return(-1);
		$cdir = $this->ReadCentralDir($zip,$zn);
		$pos_entry = $cdir['offset'];
		if(!is_array($index)){ $index = array($index);  }
		for($i=0; $index[$i];$i++)
		{
			if(intval($index[$i])!=$index[$i]||$index[$i]>$cdir['entries'])
			return(-1);
		}
		for ($i=0; $i<$cdir['entries']; $i++)
		{
			@fseek($zip, $pos_entry);
			$header = $this->ReadCentralFileHeaders($zip);
			$header['index'] = $i; $pos_entry = ftell($zip);
			@rewind($zip); fseek($zip, $header['offset']);
			if(in_array("-1",$index)||in_array($i,$index))
			$stat[$header['filename']]=$this->ExtractFile($header, $to, $zip);
		}
		fclose($zip);
		return $stat;
	}



function ReadFileHeader($zip)
{
	$binary_data = fread($zip, 30);
	$data = unpack('vchk/vid/vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $binary_data);

	$header['filename'] = fread($zip, $data['filename_len']);
	if ($data['extra_len'] != 0)
	{
		$header['extra'] = fread($zip, $data['extra_len']);
	}
	else
	{
		$header['extra'] = '';
	}

	$header['compression'] = $data['compression'];
	$header['size'] = $data['size'];
	$header['compressed_size'] = $data['compressed_size'];
	$header['crc'] = $data['crc'];
	$header['flag'] = $data['flag'];
	$header['mdate'] = $data['mdate'];
	$header['mtime'] = $data['mtime'];

	if ($header['mdate'] && $header['mtime'])
	{
		$hour=($header['mtime']&0xF800)>>11;$minute=($header['mtime']&0x07E0)>>5;
		$seconde=($header['mtime']&0x001F)*2;$year=(($header['mdate']&0xFE00)>>9)+1980;
		$month=($header['mdate']&0x01E0)>>5;$day=$header['mdate']&0x001F;
		$header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
	}
	else
	{
		$header['mtime'] = time();
	}

	$header['stored_filename'] = $header['filename'];
	$header['status'] = "ok";
	return $header;
}

 function ReadCentralFileHeaders($zip)
{
	$binary_data = fread($zip, 46);
	$header = unpack('vchkid/vid/vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);

	if ($header['filename_len'] != 0)
	$header['filename'] = fread($zip,$header['filename_len']);
	else $header['filename'] = '';

	if ($header['extra_len'] != 0)
	$header['extra'] = fread($zip, $header['extra_len']);
	else $header['extra'] = '';

	if ($header['comment_len'] != 0)
	$header['comment'] = fread($zip, $header['comment_len']);
	else $header['comment'] = '';

	if ($header['mdate'] && $header['mtime'])
	{
		$hour = ($header['mtime'] & 0xF800) >> 11;
		$minute = ($header['mtime'] & 0x07E0) >> 5;
		$seconde = ($header['mtime'] & 0x001F)*2;
		$year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
		$month = ($header['mdate'] & 0x01E0) >> 5;
		$day = $header['mdate'] & 0x001F;
		$header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
	}
	else
	{
		$header['mtime'] = time();
	}
	$header['stored_filename'] = $header['filename'];
	$header['status'] = 'ok';
	if (substr($header['filename'], -1) == '/')
	$header['external'] = 0x41FF0010;
	return $header;
}

function ReadCentralDir($zip,$zip_name)
	{
		$size = filesize($zip_name);
		if ($size < 277) $maximum_size = $size;
		else $maximum_size=277;

		@fseek($zip, $size-$maximum_size);
		$pos = ftell($zip);
		$bytes = 0x00000000;

		while ($pos < $size)
		{
			$byte = @fread($zip, 1); $bytes=($bytes << 8) | ord($byte);
			if (($bytes & 0xFFFFFFFF) == 0x504b0506){ $pos++;break;} $pos++;
		}

		$fdata=fread($zip,18);

		$data=@unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size',$fdata);

		if ($data['comment_size'] != 0) $centd['comment'] = fread($zip, $data['comment_size']);
		else $centd['comment'] = ''; $centd['entries'] = $data['entries'];
		$centd['disk_entries'] = $data['disk_entries'];
		$centd['offset'] = $data['offset'];$centd['disk_start'] = $data['disk_start'];
		$centd['size'] = $data['size'];  $centd['disk'] = $data['disk'];
		return $centd;
	}

 	function ExtractFile($header,$to,$zip)
	{
		$header = $this->readfileheader($zip);

		if(substr($to,-1)!="/") $to.="/";
		if($to=='./') $to = '';
		$pth = explode("/",$to.$header['filename']);
		if('/'==substr($to,0,1)){
			$pth[0]="/".$pth[0];
		}
		$mydir = '';
		for($i=0;$i<count($pth)-1;$i++)
		{
			if(!$pth[$i]) continue;
			$mydir .= $pth[$i]."/";
			if((!is_dir($mydir) && @mkdir($mydir,0777)) || (($mydir==$to.$header['filename'] || ($mydir==$to && $this->total_folders==0)) && is_dir($mydir)) )
			{
				@chmod($mydir,0777);
			}
		}

		if(strrchr($header['filename'],'/')=='/') return;

		if (!($header['external']==0x41FF0010)&&!($header['external']==16)){
		if ($header['compression']==0){
			$fp = @fopen($to.$header['filename'], 'wb');
			if(!$fp) return(-1);
			$size = $header['compressed_size'];

			while ($size != 0){
				$read_size = ($size < 2048 ? $size : 2048);
				$buffer = fread($zip, $read_size);
				$binary_data = pack('a'.$read_size, $buffer);
				@fwrite($fp, $binary_data, $read_size);
				$size -= $read_size;
			}
			fclose($fp);
			touch($to.$header['filename'], $header['mtime']);
		}
		else
		{
			$fp = @fopen($to.$header['filename'].'.gz','wb');
			if(!$fp) return(-1);
			$binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($header['compression']),
			Chr(0x00), time(), Chr(0x00), Chr(3));

			fwrite($fp, $binary_data, 10);
			$size = $header['compressed_size'];

			while ($size != 0){
				$read_size = ($size < 1024 ? $size : 1024);
				$buffer = fread($zip, $read_size);
				$binary_data = pack('a'.$read_size, $buffer);
				@fwrite($fp, $binary_data, $read_size);
				$size -= $read_size;
			}

			$binary_data = pack('VV', $header['crc'], $header['size']);
			fwrite($fp, $binary_data,8); fclose($fp);

			$gzp = @gzopen($to.$header['filename'].'.gz','rb');
			if(!$gzp)return false;
			if(!$gzp) return(-2);
			$fp = @fopen($to.$header['filename'],'wb');
			if(!$fp) return(-1);
			$size = $header['size'];

			while ($size != 0){
				$read_size = ($size < 2048 ? $size : 2048);
				$buffer = gzread($gzp, $read_size);
				$binary_data = pack('a'.$read_size, $buffer);
				@fwrite($fp, $binary_data, $read_size);
				$size -= $read_size;
				}
			fclose($fp); gzclose($gzp);

			touch($to.$header['filename'], $header['mtime']);
			@unlink($to.$header['filename'].'.gz');

			}
		}
	return true;
	}
	
	function zip_dir($dirs, $zipfilename,$zipdir=''){
		if(is_array($dirs)){
			foreach($dirs as $key=>$dir){
				if(is_dir($dir)){
					$this->dir_Tree($dir,$zipdir[$key]);
				}
			}
		}else{
			$this->dir_Tree($dirs);
		}
		$out = $this->get_file();
		$fp = fopen($zipfilename, "wb");
		fwrite($fp, $out, strlen($out));
		fclose($fp);
	}
	
	function dir_Tree($directory,$zipdir=''){
		if(''===$zipdir){
			$zipdir=$directory;
		}
		$myDir=dir($directory);
		while($file=$myDir->read()){
			if($file=="." || $file==".."){
				continue;
			}
			$pathfile=$directory.'/'.$file;
			$zipfile=$zipdir.'/'.$file;
			if(is_dir($pathfile)){
				$this->dir_Tree($pathfile,$zipfile);
			}else{
				$fd = fopen ($pathfile , "rb");
				$filecontent = fread ($fd, filesize ($pathfile));
				fclose ($fd);
				$this -> add_File($filecontent, $zipfile);
			}
		}
		$myDir->close();
	}
	
}
?>