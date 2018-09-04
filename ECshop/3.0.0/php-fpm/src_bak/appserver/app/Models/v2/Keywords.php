<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use DB;

class Keywords extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'keywords';

    public    $timestamps = false;


	public static function getHot()
    {
        $goods_search_history = Keywords::select('keyword', DB::raw(' sum(`count`) as count'))
                                        ->groupBy('keyword')
                                        ->orderBy('count', 'DESC')
                                        ->limit(10)
                                        ->get();

        $data = [];
        foreach ($goods_search_history as $key => $value) {
            $data[$key]['type'] = 1;
            $data[$key]['content'] = $value->keyword;
        }
        return self::formatBody(['keywords' => $data]);
    }

    public static function updateHistory($keyword)
    {
        $keyword = strip_tags($keyword);
        if(empty($keyword)){
            return false;
        }

        $model = self::where('keyword', $keyword)->where('date', date('Y-m-d', time()));
        if ($model->first()) {
            $model->increment('count', 1);
        } else {
            $keywords = new Keywords;
            $keywords->keyword = $keyword;
            $keywords->count = 1;
            $keywords->date = date('Y-m-d', time());
            $keywords->save();
        }
        return true;
    }
}
