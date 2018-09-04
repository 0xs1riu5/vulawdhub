<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;

class UserAddress extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'user_address';

    protected $primaryKey = 'address_id';

    public    $timestamps = false;

    protected $appends = ['id', 'name', 'zip_code', 'regions', 'is_default'];

    protected $visible = ['id', 'name', 'mobile', 'tel', 'zip_code', 'regions', 'country','province','city', 'address', 'is_default'];

    public static function getList()
    {
        $uid = Token::authorization();
        $data = UserAddress::where('user_id', $uid)->get()->toArray();
        return self::formatBody(['consignees' => $data]);
    }

    public static function get_consignee($consignee)
    {
        $uid = Token::authorization();
        $arr = array();
        if ($consignee) {
            return self::where('address_id',$consignee)->first();
        }
        if ($uid > 0)
        {
            /* 取默认地址 */
            // $sql = "SELECT ua.*".
            //         " FROM " . $GLOBALS['ecs']->table('user_address') . "AS ua, ".$GLOBALS['ecs']->table('users').' AS u '.
            //         " WHERE u.user_id='$uid' AND ua.address_id = u.address_id";

            // $arr = $GLOBALS['db']->getRow($sql);
            $arr = self::join('users','user_address.address_id', '=', 'users.address_id')
                    ->where('users.user_id',$uid)
                    ->first()->toArray();
        }

        return $arr;
    }

    public static function remove(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();
	// UserAddress::where('address_id', $consignee)->where('user_id', $uid)->delete();
	if (UserAddress::where('address_id', $consignee)->where('user_id', $uid)->delete()) {
            if ($address = UserAddress::where('user_id', $uid)->first()) {
                $model = Member::where('user_id', $uid)->first();
                $model->address_id = $address->address_id;
                $model->save();
            }
        }
        return self::formatBody();
    }

    public static function add(array $attributes)
    {
        extract($attributes);

        $uid = Token::authorization();
        $arr = Region::getParentId($region);

        $model = new UserAddress;
        $model->user_id         = $uid;
        $model->consignee       = $name;
        $model->email           = '';
        $model->country         = !empty( $arr['country'] ) ? $arr['country'] : '';
        $model->province        = !empty( $arr['province'] ) ? $arr['province'] : '';
        $model->city            = !empty( $arr['city'] ) ? $arr['city'] : '';
        $model->district        = !empty( $arr['region'] ) ? $arr['region'] : '';
        $model->address         = $address;
        $model->mobile          = isset($mobile) ? $mobile : '';
        $model->tel             = isset($tel) ? $tel : '';
        $model->zipcode         = isset($zip_code) ? $zip_code : '';
        $model->address_name    = '';
        $model->sign_building   = '';
        $model->best_time       = '';

        if ($model->save()){

            $member = Member::where('user_id', $uid)->first();

            if (!UserAddress::where('address_id', $member->address_id)->first()) {
                $member->address_id = $model->address_id;
                $member->save();
            }

            return self::formatBody(['consignee' => self::formatBody($model->toArray())]);
        }

        return self::formatError(self::UNKNOWN_ERROR);

    }

    public static function modify(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        if ($model = UserAddress::where('address_id', $consignee)->where('user_id', $uid)->first()) {

            $arr = Region::getParentId($region);


            $model->user_id         = $uid;
            $model->consignee       = $name;
            $model->country         = !empty( $arr['country'] ) ? $arr['country'] : '';
            $model->province        = !empty( $arr['province'] ) ? $arr['province'] : '';
            $model->city            = !empty( $arr['city'] ) ? $arr['city'] : '';
            $model->district        = !empty( $arr['region'] ) ? $arr['region'] : '';
            $model->address         = $address;
            $model->mobile          = isset($mobile) ? $mobile : ' ';
            $model->tel             = isset($tel) ? $tel : ' ';
            $model->zipcode         = isset($zip_code) ? $zip_code : ' ';

            if ($model->save()){
                return self::formatBody(['consignee' => self::formatBody($model->toArray())]);
            }
        }

        return self::formatError(self::UNKNOWN_ERROR);

    }

    public static function setDefault(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        if (UserAddress::where('address_id', $consignee)->where('user_id', $uid)->first()) {
            if($model = Member::where('user_id', $uid)->first()){
                $model->address_id = $consignee;
                $model->save();
                return self::formatBody();
            }
        }

        return self::formatError(self::BAD_REQUEST, trans('message.address.error'));
    }

    public static function getRegionIdList($address_id)
    {
        $arr = [];
        if ($model = UserAddress::where('address_id', $address_id)->first()) {
            $arr['country'] = $model->country;
            $arr['province'] = $model->province;
            $arr['city'] = $model->city;
            $arr['district'] = $model->district;
        }

        return $arr;
    }

    public function getIdAttribute()
    {
        return $this->attributes['address_id'];
    }

    public function getNameAttribute()
    {
        return $this->attributes['consignee'];
    }

    public function getRegionsAttribute()
    {
        return Region::getRegionGroup($this->district?:$this->city?:$this->province?:$this->country);
    }

    public function getZipCodeAttribute()
    {
        return $this->attributes['zipcode'];
    }

    public function getIsDefaultAttribute()
    {
        $uid = Token::authorization();
        $flag = Member::where('user_id', $uid)->where('address_id',$this->address_id)->count() ? true : false;
        return  $flag;
    }

}