<?php
//
namespace App\Helper;

class Header
{
	public static function getUserAgent($key = false)
    {
        $arr = [];

        if ($ua = app('request')->header('X-'.config('app.name').'-UserAgent')) {

            $items = @explode(', ',$ua);
            if (is_array($items)) {
                foreach ($items as $property) {
                    if (strpos($property, '/') !== false) {
                        $property = explode('/', $property);
                        $arr[$property[0]] = $property[1];
                    }
                }
            }
        }

        if ($key) {
            return (isset($arr[$key]) && $arr[$key]) ? strtolower($arr[$key]) : '';
        }
        
        return $arr;
    }

	public static function getVer()
	{
		if ($ver = app('request')->header('X-'.config('app.name').'-Ver')) {

			$rule = '/^[(\d)+.(\d)+.(\d)+]+$/';
			if(preg_match($rule, $ver)){
				return $ver;
			}
		}
		return null;
	}

    public static function getUDID()
    {
        if ($UDID = app('request')->header('X-'.config('app.name').'-UDID')) {
            return $UDID;
        }
        return null;
    }

}
