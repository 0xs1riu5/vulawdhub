<?php
namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;
use DB;
use Log;

class AffiliateLog extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'affiliate_log';

    public    $timestamps = false;
    
    protected $guarded = [];
    
    // AFFILIATE_STSTUA 
    const SIGNUP = 0;   //  注册分成
	const ORDER  = 1; 	//  订单分成

	// AFFILIATE_TYPE
    const WAIT    = 0;  //  等待处理
    const FINISH  = 1;  //  已分成
    const CANCEL  = 2;  //  已取消
    const REVOKE  = 3;   //  已撤销

    public static function expireUnit()
    {
        return [ 
            'hour' => trans('message.affiliate.expire.hour'),
            'day'  => trans('message.affiliate.expire.day'),
            'week' => trans('message.affiliate.expire.week'),
        ];
    }

    public static function affiliateIntro()
    {
        return [ 
            trans('message.affiliate.intro.separate_by_0'),
            trans('message.affiliate.intro.separate_by_1'),
        ];
    }

    public static function getAffiliateConfig()
    {
    	$value = null;
    	if ($row = ShopConfig::where('code', 'affiliate')->first()) {
    		$value = unserialize($row->value);	
    	}
    	return $value;
    }

    public static function getList(array $attributes)
    {	
    	$user_id = Token::authorization();
    	/* 检查是否开启 */
    	$on = self::checkOpen();
    	if ($on != 1 || empty($on)) {
    		return false;
    	}

        extract($attributes); 

        $prefix = config('database.connections.shop.prefix');
    	
        $affiliate = self::getAffiliateConfig();

        if ($affiliate['config']['separate_by'] == self::SIGNUP) {
            //推荐注册分成
            $num = count($affiliate['item']);
            $all_uid = [$user_id];
            $up_uid = [$user_id];
            for ($i = 1 ; $i <= $num ;$i++)
            {
                if ($up_uid)
                {
                    $rts = Member::select('user_id')->whereIn('parent_id', $up_uid)->get();
                    $up_uid = [];
                    foreach ($rts  as $rt)
                    {
                        array_push($up_uid, $rt->user_id);
                        if($i < $num)
                        {
                            array_push($all_uid, $rt->user_id);
                        }
                    }
                }
            }

            $model = AffiliateOrder::select('order_info.order_sn as order_sn', 'affiliate_log.money as cash', 'affiliate_log.point as score', 'affiliate_log.separate_type', 'order_info.is_separate', DB::raw('IFNULL('.$prefix.'affiliate_log.time, '.$prefix.'order_info.add_time) as time'))
                ->leftJoin('users', 'users.user_id', '=', 'order_info.user_id')
                ->leftJoin('affiliate_log', 'affiliate_log.order_id', '=', 'order_info.order_id')
                ->where('order_info.user_id', '>', 0)
                ->where(function ($query) use($all_uid, $user_id) {
                        $query->whereIn('users.parent_id', $all_uid)
                              ->where('order_info.is_separate', 0)
                              ->orWhere('affiliate_log.user_id', $user_id)
                              ->where('order_info.is_separate', '>', 0)
                              ->orWhereIn('users.parent_id', $all_uid)
                              ->where('order_info.is_separate', '=', 2);
                    })
                ->orderBy('order_info.order_id', 'desc');

        } else {

            // 推荐订单分成
            $model = AffiliateOrder::select('order_info.order_sn as order_sn', 'affiliate_log.money as cash', 'affiliate_log.point as score', 'affiliate_log.separate_type', 'order_info.is_separate', DB::raw('IFNULL('.$prefix.'affiliate_log.time, '.$prefix.'order_info.add_time) as time'))
            ->leftJoin('users', 'users.user_id', '=', 'order_info.user_id')
            ->leftJoin('affiliate_log', 'affiliate_log.order_id', '=', 'order_info.order_id')
            ->where('order_info.user_id', '>', 0)
             ->where(function ($query) use($user_id) {
                        $query->where('users.parent_id', $user_id)
                              ->where('order_info.is_separate', 0)
                              ->orWhere('affiliate_log.user_id', $user_id)
                              ->where('order_info.is_separate', '>', 0);
                    })
            ->orderBy('order_info.order_id', 'desc');
        }	

        $data = $model->paginate($per_page)->toArray();

        foreach ($data['data'] as $key => $value) {
            if ( !isset($value['is_separate']) == 0 ) {
                $data['data'][$key]['status'] = self::WAIT;
            }
            if ($value['is_separate'] == 1) {
                $data['data'][$key]['status'] = self::FINISH;
            }
            if ($value['is_separate'] == 2) {
                $data['data'][$key]['status'] = self::CANCEL;
            }
            if ($value['separate_type'] == -1 || $value['separate_type'] == -2) {
                $data['data'][$key]['status'] = self::REVOKE;
            }
            $data['data'][$key]['type'] = $affiliate['config']['separate_by']; // 注册分成
            $data['data'][$key]['order_sn'] = substr($data['data'][$key]['order_sn'], 0, strlen($data['data'][$key]['order_sn']) - 5) . "***" . substr($data['data'][$key]['order_sn'], -2, 2);
        }

        $total = $model->count();

    	return self::formatBody(['paged' => self::formatPaged($page, $per_page, $total), 'bonues' => $data['data']]);
    }

    public static function info()
    {
        $affiliate = self::getAffiliateConfig();
        $user_id = Token::authorization();
        
        $separate_by = $affiliate['config']['separate_by'];

        $data = $aff_db = array();
        
        $all_amount = 0;

        if ($affiliate['config']['separate_by'] == 0) {
            //推荐注册分成
            /* rules */
            $num = count($affiliate['item']);
            $all_uid = [$user_id];
            $up_uid = [$user_id];

            for ($i = 1 ; $i <= $num ;$i++)
            {
                $count = 0;
                if ($up_uid)
                {
                    $rts = Member::select('user_id')->whereIn('parent_id', $up_uid)->get();
                    $up_uid = [];
                    foreach ($rts  as $rt)
                    {
                        array_push($up_uid, $rt->user_id);
                        if($i < $num)
                        {
                            array_push($all_uid, $rt->user_id);
                        }
                        $count++;
                    }
                }
                $aff_db[$i]['rank'] = $i;
                $aff_db[$i]['recommend_amount'] = $count;
                $aff_db[$i]['score_percentage'] = $affiliate['item'][$i-1]['level_point'];
                $aff_db[$i]['price_percentage'] = $affiliate['item'][$i-1]['level_money'];
            }

            // 已推荐注册总人数
            foreach ($aff_db as $key => $value) {
                $all_amount += $value['recommend_amount'];
            }

            /* recommend_nums */
            $sql_finished = AffiliateOrder::leftJoin('users', 'users.user_id', '=', 'order_info.user_id')
                    ->leftJoin('affiliate_log', 'affiliate_log.order_id', '=', 'order_info.order_id')
                    ->where('order_info.user_id', '>', 0)
                    ->where(function ($query) use($all_uid, $user_id) {
                        $query->whereIn('users.parent_id', $all_uid)
                              ->where('affiliate_log.separate_type', 0)
                              ->Where('affiliate_log.user_id', $user_id)
                              ->where('order_info.is_separate', 1);
                    });

            /* rule_desc */
            $rule_desc = nl2br(sprintf(self::affiliateIntro()[$separate_by], $affiliate['config']['expire'], self::expireUnit()[$affiliate['config']['expire_unit']], $affiliate['config']['level_register_all'], $affiliate['config']['level_register_up'], $affiliate['config']['level_money_all'], $affiliate['config']['level_point_all']));

        } else {
            //推荐订单分成
            $sql_all = AffiliateOrder::leftJoin('users', 'users.user_id', '=', 'order_info.user_id')
                    ->leftJoin('affiliate_log', 'affiliate_log.order_id', '=', 'order_info.order_id')
                    ->where('order_info.user_id', '>', 0)
                    ->where(function ($query) use($user_id) {
                        $query->where('users.parent_id', $user_id)
                              ->where('order_info.is_separate', 0)
                              ->orWhere('affiliate_log.user_id', $user_id)
                              ->where('order_info.is_separate', '>', 0);
                    });
            $all_amount = $sql_all->count(); // 已推荐总订单数

            $sql_finished = AffiliateOrder::leftJoin('users', 'users.user_id', '=', 'order_info.user_id')
                    ->leftJoin('affiliate_log', 'affiliate_log.order_id', '=', 'order_info.order_id')
                    ->where('order_info.user_id', '>', 0)
                    ->where(function ($query) use($user_id) {
                        $query->where('users.parent_id', $user_id)
                              ->where('order_info.is_separate', 1)
                              ->where('affiliate_log.user_id', $user_id);
                    });

            /* rule_desc */
            $rule_desc = nl2br(sprintf(self::affiliateIntro()[$separate_by], $affiliate['config']['expire'], self::expireUnit()[$affiliate['config']['expire_unit']], $affiliate['config']['level_money_all'], $affiliate['config']['level_point_all']));
        }

        $data['recommend_amount'] = $all_amount;
        $data['bonus_amount'] = $sql_finished->count();
        $data['rules'] = array_values($aff_db);
        $data['rule_desc'] = $rule_desc;
        $data['shared_link'] = config('app.shop_h5'). '?u=' . $user_id;
        
        return self::formatBody(['bonus_info' => $data]);
    }

    public static function checkOpen()
    {
    	$value = self::getAffiliateConfig();
    	if (!empty($value)) {
    		return $value['on'];
    	}
    	else{
    		return false;
    	}
    }

    public static function writeAffiliateLog($oid, $uid, $username, $money, $point, $separate_by)
    {
        $model = new AffiliateLog;
        $model->order_id = $oid;
        $model->user_id = $uid;
        $model->user_name = $username;
        $model->time = time(); 
        $model->money = $money;
        $model->point = $point;
        $model->separate_type = $separate_by;

        $model->save();
    }

    // 计算推荐分成
    public static function affiliate($order_id)
    {
        $affiliate = self::getAffiliateConfig();

        empty($affiliate) && $affiliate = array();

        $separate_by = $affiliate['config']['separate_by'];

        $order = AffiliateOrder::leftJoin('users', 'users.user_id', '=', 'order_info.user_id')
                    ->where('order_info.order_id', $order_id)
                    ->first();

        if ($order) {
            $order_sn = $order->order_sn;
            $goods_amount = $order->goods_amount - $order->discount;
            if (empty($order->is_separate))
            {
                $affiliate['config']['level_point_all'] = (float)$affiliate['config']['level_point_all'];
                $affiliate['config']['level_money_all'] = (float)$affiliate['config']['level_money_all'];
                if ($affiliate['config']['level_point_all'])
                {
                    $affiliate['config']['level_point_all'] /= 100;
                }
                if ($affiliate['config']['level_money_all'])
                {
                    $affiliate['config']['level_money_all'] /= 100;
                }
                $money = round($affiliate['config']['level_money_all'] * $goods_amount, 2);
                $integral = OrderGoods::integralToGive($order_id);
                $point = round($affiliate['config']['level_point_all'] * intval($integral), 0);

                Log::debug("推荐分成", ['money' => $money, 'integral' => $integral, 'point' => $point]);

                if(empty($separate_by))
                {
                    //推荐注册分成
                    $num = count($affiliate['item']);
                    $user_id = $order->user_id;

                    for ($i=0; $i < $num; $i++)
                    {
                        $affiliate['item'][$i]['level_point'] = (float)$affiliate['item'][$i]['level_point'];
                        $affiliate['item'][$i]['level_money'] = (float)$affiliate['item'][$i]['level_money'];
                        if ($affiliate['item'][$i]['level_point'])
                        {
                            $affiliate['item'][$i]['level_point'] /= 100;
                        }
                        if ($affiliate['item'][$i]['level_money'])
                        {
                            $affiliate['item'][$i]['level_money'] /= 100;
                        }
                        $setmoney = round($money * $affiliate['item'][$i]['level_money'], 2);
                        $setpoint = round($point * $affiliate['item'][$i]['level_point'], 0);

                        if ($order_user = Member::where('user_id', $user_id)->first()) {
                            if ($order_user->parent_id) {
                                if ($up_user = Member::where('user_id', $order_user->parent_id)->first()) {
                                    $up_uid = $up_user->user_id;
                                    $up_user_name = $up_user->user_name;
                                    $user_id = $up_user->user_id;
                                }
                            } else {
                                break;
                            }
                        }

                        if (empty($up_uid) || empty($up_user_name))
                        {
                            break;
                        }
                        else
                        {
                            $info = sprintf('订单号 %s, 分成:金钱 %s 积分 %s', $order_sn, $setmoney, $setpoint);
                            AccountLog::logAccountChange($setmoney, 0, $setpoint, 0, $info, 99, $up_uid);
                            AffiliateLog::writeAffiliateLog($order_id, $up_uid, $up_user_name, $setmoney, $setpoint, $separate_by);
                            unset($up_uid);
                            unset($up_user_name);
                        }
                    }
                }
                else
                {
                    //推荐订单分成
                    if ($order->parent_id) {
                        $up_user = Member::where('user_id', $order->parent_id)->first();
                        $up_uid = $up_user->user_id;
                        $up_user_name = $up_user->user_name;
                    }

                    if (!empty($up_uid) && !empty($up_user_name))
                    {
                        $info = sprintf('订单号 %s, 分成:金钱 %s 积分 %s', $order_sn, $money, $point);
                        AccountLog::logAccountChange($money, 0, $point, 0, $info, 99, $up_uid);
                        AffiliateLog::writeAffiliateLog($order_id, $up_uid, $up_user_name, $money, $point, $separate_by);
                    }
                }

                Order::where('order_id', $order_id)->update(['is_separate' => 1, 'lastmodify' => time()]);
            }
        }
    }
}