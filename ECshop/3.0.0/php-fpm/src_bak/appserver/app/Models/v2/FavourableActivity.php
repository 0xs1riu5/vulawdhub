<?php

namespace App\Models\v2;
use App\Models\BaseModel;

use App\Helper\Token;
use DB;


class FavourableActivity extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'favourable_activity';
    public    $timestamps = false;

	const 	FAT_GOODS                 = 0; // 送赠品或优惠购买
	const 	FAT_PRICE                 = 1; // 现金减免
	const 	FAT_DISCOUNT              = 2; // 价格打折优惠

	/* 优惠活动的优惠范围 */
	const   FAR_ALL                   = 0; // 全部商品
	const   FAR_CATEGORY              = 1; // 按分类选择
	const   FAR_BRAND                 = 2; // 按品牌选择
	const   FAR_GOODS                 = 3; // 按商品选择

    public static function getPromoByGoods($goods_id, $cat_id, $brand_id)
    {
        $data = [];
        $now = time();
	
	$cat_parent_id = Category::where('cat_id', $cat_id)->value('parent_id');	
	$sql = '';
        $user_rank = UserRank::getUserRankByUid();
        if (!empty($user_rank)) {
            $sql = ' AND FIND_IN_SET(' . $user_rank['rank_id'] . ', `user_rank`)';
        }
        $model = DB::connection('shop')->table('favourable_activity')->whereRaw('(`start_time` <= '.$now.' AND `end_time` >= '.$now.') AND (`act_range` = 0 OR (`act_range` = 1 AND FIND_IN_SET('.$cat_id.',`act_range_ext`) OR FIND_IN_SET('.$cat_parent_id.',`act_range_ext`)) OR (`act_range` = 2 AND FIND_IN_SET('.$brand_id.',`act_range_ext`)) OR (`act_range` = 3 AND FIND_IN_SET('.$goods_id.',`act_range_ext`)))' . $sql)->get();

        if (!empty($model)) {

            foreach ($model as $key => $value) {
                switch ($value->act_type) {
                    case 0:
                        // 满赠
                        $data[$key]['promo'] = '满'.$value->min_amount.'送赠品';
                        break;
                    
                    case 1:
                        // 满减
                        $data[$key]['promo'] = '满'.$value->min_amount.'减'.$value->act_type_ext;
                        break;
                    
                    case 2:
                        // 满折
                        $data[$key]['promo'] = '满'.$value->min_amount.'打'.($value->act_type_ext/10).'折';
                        break;
                    
                    default:
                        $data[$key]['promo'] =  $value->act_name;
                        break;
                }

                $data[$key]['name'] = $value->act_name;
		$data[$key]['start_at'] = $value->start_time;
                $data[$key]['end_at']   = $value->end_time;
            }
        }
        return $data;
    }
}
