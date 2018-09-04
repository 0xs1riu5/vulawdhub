<?php
namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;
use App\Models\v2\AccountLog;
use DB;

class UserAccount extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'user_account';

    public    $timestamps = false;

    protected $appends = ['id', 'cash', 'member_memo', 'admin_memo', 'update_at', 'create_at', 'status'];

    protected $visible = ['id', 'cash', 'member_memo', 'admin_memo', 'update_at', 'create_at', 'status'];

    const WAIT    = 0;    //  待处理     待处理的时候  可以做取消操作
    const FINISH  = 1;    //  已完成
    const CANCEL  = 2;    //  已取消   // 此处没有取消

    public static $process_type = 1;  // 0：充值 1:提现

    public function getIdAttribute()
    {
        return $this->attributes['id'];
    }

    public function getCashAttribute()
    {
        return str_replace('-', '', $this->attributes['amount']);
    }

    public function getMemberMemoAttribute()
    {
        return $this->attributes['user_note'];
    }

    public function getAdminMemoAttribute()
    {
        return $this->attributes['admin_note'];
    }
    public function getUpdateAtAttribute()
    {
        if (empty($this->attributes['paid_time'])) {
            return null;
        }
        return $this->attributes['paid_time'];
    }
    
    public function getCreateAtAttribute()
    {
        return $this->attributes['add_time'];
    }

    public function getStatusAttribute()
    {
        return $this->attributes['is_paid'];
    }

    /**
    * 申请提现
    */
    public static function submit(array $attributes)
    {
        $user_id = Token::authorization();

        extract($attributes);
        
        if ($cash <= 0) {
            return self::formatError(self::BAD_REQUEST, trans('message.account.cash'));
        }

        /* 判断是否有足够的余额的进行退款的操作 */
        $sur_amount = self::getUserSurplus($user_id);
        if ($cash > $sur_amount['amount'])
        {
            return self::formatError(self::BAD_REQUEST, trans('message.account.amount'));
        }

        if ($id = self::insertUserAccount($user_id, $cash, $memo)) {
            return self::formatBody(['withdraw' => self::find($id)]);
        }
        return self::formatError(self::BAD_REQUEST, trans('message.account.process'));
    }

    /**
     * 取消提现
     */
    public static function cancel(array $attributes)
    {
        extract($attributes);
        $user_id = Token::authorization();

        if (!$result = self::find($id)) {
            return self::formatError(self::NOT_FOUND);
        }
        // 'DELETE FROM ' .$GLOBALS['ecs']->table('user_account').
        //    " WHERE is_paid = 0 AND id = '$rec_id' AND user_id = '$user_id'";

        if (self::where(['is_paid' => 0, 'id' => $id, 'user_id' => $user_id])->delete()) {
            return self::formatBody(['withdraw' => $result->toArray()]);
        }
        return self::formatError(self::BAD_REQUEST, trans('message.account.process'));
    }

    /**
     * 提现记录列表
     */
    public static function getList(array $attributes)
    {
        extract($attributes);
        $user_id = Token::authorization();

        $lists = self::where(['user_id'=> $user_id, 'process_type' => self::$process_type]);

        $model = $lists->orderBy('add_time', 'desc')->paginate($per_page)->toArray();

        $total = $lists->count();

        return self::formatBody(['paged' => self::formatPaged($page, $per_page, $total), 'withdraws' => $model['data']]);
    }

    /**
     * 提现详情
     */
    public static function getDetail(array $attributes)
    {
        extract($attributes);
        $user_id = Token::authorization();
        
        if ($list = self::find($id)) {
            return self::formatBody(['withdraw' => $list]);
        }
        return self::formatError(self::NOT_FOUND);
    }

    /**
     * 查询会员余额的数量
     * @access  public
     * @param   int     $user_id        会员ID
     * @return  int
     */
    public static function getUserSurplus($user_id)
    {
        $money = AccountLog::where('user_id',$user_id)->sum('user_money');
        return self::formatBody(['amount' => $money]);
    }

    /**
     * 插入会员账目明细
     *
     * @access  public
     * @param   string    $user_note  会员备注
     * @param   string    $user_id    会员ID
     * @param   string    $amount     余额
     *
     * @return  int
     */
    public static function insertUserAccount($user_id, $amount, $user_note)
    {
        return self::insertGetId([
            'user_id' => $user_id,
            'admin_user' => '',
            'amount' => '-' . $amount,
            'add_time' => time(),
            'admin_note' => '',
            'user_note' => $user_note,
            'process_type' => self::$process_type,
            'payment' => '',
            'is_paid' => self::WAIT,
        ]);
    }
}