<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：ip.class.php
 * $author：lucks
 */
if(!defined('IN_BLUE'))
{
	die('Access Denied!');
}

class ip {
	var $db;
	var $table;
	var $timestamp;
	var $fp;
	var $firstip;
	var $lastip;
	var $totalip;

    function __construct($act = '') {
		global $db, $pre, $timestamp;
		if (empty($act))
		{
			$this->db = &$db;
			$this->table = $pre.'ipbanned';
			$this->timestamp = $timestamp;
			$this->clean();
		}
		elseif ($act == 'getarea')
		{
			$datfile = BLUE_ROOT . 'include/table/QQWry.Dat';
			if (!file_exists($datfile))
			{
				return false;
			}
			$this->fp = @fopen($datfile,'rb');
			$this->firstip = $this->get4b();
			$this->lastip = $this->get4b();
			$this->totalip = ($this->lastip - $this->firstip)/7 ;
			register_shutdown_function(array($this,"closefp"));
		}
    }

	function ip($act = '')
	{
		$this->__construct($act);
	}

	function add_ip($ip, $exp)
	{
		if (empty($ip) || empty($exp)) return false;
		return $this->db->query("INSERT INTO ".$this->table." (ip, add_time, exp) VALUES ('$ip', '".$this->timestamp."', '$exp')");
	}

	function edit_ip($old_ip, $new_ip, $exp)
	{
		if (empty($new_ip) || empty($exp)) return false;
		return $this->db->query("UPDATE ".$this->table." SET ip='$new_ip', exp='$exp' WHERE ip='$old_ip'");
	}

	function del_ip($ip)
	{
		if (empty($ip)) return false;
		return $this->db->query("DELETE FROM ".$this->table." WHERE ip='$ip'");
	}
	
	function check_exists($ip)
	{
		if (empty($ip)) return false;
		$result = $this->db->getfirst("SELECT COUNT(*) FROM ".$this->table." WHERE ip = '$ip'");
		if ($result >0) return true;
		else return false;
	}

	function list_ip()
	{
		return $this->db->getall("SELECT * FROM ".$this->table." ORDER BY add_time DESC");
	}

	function get_ip($ip)
	{
		if (empty($ip)) return false;
		return $this->db->getone("SELECT * FROM ".$this->table." WHERE ip='$ip'");
	}

	function clean()
	{
		return $this->db->query("DELETE FROM ".$this->table." WHERE add_time+exp*24*3600<".$this->timestamp);
	}
	
	function closefp()
	{
		fclose($this->fp);
	}

	function get4b()
	{
		$str = unpack("V",fread($this->fp,4));
		return $str[1];
	}

	function getoffset()
	{
		$str = unpack("V",fread($this->fp,3).chr(0));
		return $str[1];
	}

	function getstr(){
		$split = fread($this->fp,1);
		while(ord($split)!= 0)
		{
			$str .= $split;
			$split = fread($this->fp,1);
		}
		return $str;
	}

	function iptoint($ip)
	{
		$ip = explode('.', $ip);
		$ip = $ip[0] * pow(256, 3) + $ip[1] * pow(256, 2) + $ip[2] * pow(256, 1) + $ip[3] * pow(256, 0);
		return pack("N",intval($ip));
	}

	function readaddress()
	{
		$now_offset = ftell($this->fp);
		$flag = $this->getflag();
		switch(ord($flag))
		{
			case 0:
				$address="";
				break;
			case 1:
			case 2:
				fseek($this->fp,$this->getoffset());
				$address=$this->getstr();
				break;
			default:
				fseek($this->fp,$now_offset);
				$address = $this->getstr();
				break;
		}
		return $address;
	}

	function getflag()
	{
		return fread($this->fp,1);
	}

	function searchip($ip)
	{
		$ip = gethostbyname($ip);
		$ip_offset["ip"] = $ip;
		$ip = $this->iptoint($ip);
		$firstip = 0;
		$lastip = $this->totalip;
		$ipoffset = $this->lastip;
		while ($firstip <= $lastip)
		{
			$i = floor(($firstip + $lastip) / 2);
			fseek($this->fp,$this->firstip + $i * 7);
			$startip = strrev(fread($this->fp,4));
			if ($ip < $startip)
			{
				$lastip = $i - 1;
			}
			else
			{
				fseek($this->fp,$this->getoffset());
				$endip = strrev(fread($this->fp,4));
				if($ip > $endip)
				{
					$firstip = $i + 1;
				}
				else
				{
					$ip_offset["offset"] = $this->firstip + $i * 7;
					break;
				}
			}
		}
		return $ip_offset;
	}

	function getaddress($ip)
	{
		$ip_offset = $this->searchip($ip);
		$ipoffset = $ip_offset["offset"];
		$address["ip"] = $ip_offset["ip"];
		fseek($this->fp,$ipoffset);
		$address["startip"] = long2ip($this->get4b());
		$address_offset = $this->getoffset();
		fseek($this->fp,$address_offset);
		$address["endip"] = long2ip($this->get4b());
		$flag = $this->getflag();
		switch (ord($flag))
		{
			case 1:
				$address_offset = $this->getoffset();
				fseek($this->fp,$address_offset);
				$flag = $this->getflag();
				switch(ord($flag))
				{
					case 2:
						fseek($this->fp,$this->getoffset());
						$address["area1"] = $this->getstr();
						fseek($this->fp,$address_offset+4);
						$address["area2"] = $this->readaddress();
						break;
					default:
						fseek($this->fp,$address_offset);
						$address["area1"] = $this->getstr();
						$address["area2"] = $this->readaddress();
						break;
				}
				break;
			case 2:
				$address1_offset = $this->getoffset();
				fseek($this->fp,$address1_offset);  
				$address["area1"] = $this->getstr();
				fseek($this->fp,$address_offset+8);
				$address["area2"] = $this->readaddress();
				break;
			default:
				fseek($this->fp,$address_offset+4);
				$address["area1"] = $this->getstr();
				$address["area2"] = $this->readaddress();
				break;
		}
		if(strpos($address["area1"],"CZ88.NET") != false)
		{
			$address["area1"] = "";
		}
		if(strpos($address["area2"],"CZ88.NET") != false)
		{
			$address["area2"] = "";
		}
		return $address;
	}
}

?>