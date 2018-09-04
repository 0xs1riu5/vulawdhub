<?php
include('leancloud_client.php');
function delivery_msg_push($id,$db,$ecs){
  //判断开启状态
  $config = get_config($db,$ecs);
  if (!$config || !json_decode($config['config'],true)) {
    return false;
  }

  $delivery_info = $db->getRow("SELECT * FROM ".$ecs->table('delivery_order')." WHERE delivery_id = '$id'");
  if($delivery_info){
    $user_id = $delivery_info['user_id'];
    $time = date('Y-m-d H:i:s');
    $title = '您的订单已发货';
    $content = '您的订单号：'.$delivery_info['order_sn'].',已经由'.$delivery_info['shipping_name'].'发出,物流单号'.$delivery_info['invoice_no'];
    $link = 'deeplink://goto/shipping/:'.$id;
    $sql ="INSERT INTO ".$ecs->table('push')." (`user_id`,`title`,`content`,`link`,`platform`,`push_type`,`message_type`,`push_at`,`created_at`,`updated_at`) VALUES ('$user_id','$title','$content','$link','0','0','2','$time','$time','$time')";
    $db->query($sql);
    $msg_id = $db->getRow("SELECT id FROM ".$ecs->table('push')." ORDER BY `id` DESC LIMIT 1");
    $is_push = push($msg_id['id'],$db,$ecs);
    return $is_push;
  }
  return false;

}

function push($message_id,$db,$ecs) {
  //判断开启状态
  $config = get_config($db,$ecs);
  if (!$config || !json_decode($config['config'],true)) {
    return false;
  }
  $config = json_decode($config['config'],true);
  leancloud_client::initialize($config['app_id'], $config['app_key']);

  $messageInfo = get_msginfo($message_id,$db,$ecs);
  if (!$messageInfo) {
    return false;
  }
  //物流信息定向推送
  if($messageInfo['user_id'] != '0'){
    $user_id = $messageInfo['user_id'];
    $user_info = $db->getRow("SELECT * FROM ".$ecs->table('device')." WHERE user_id = '$user_id'");
    if($user_info){
      $messageInfo['platform'] = $user_info['platform_type'];
      $device_id = $user_info['device_id'];
    }
  }

  // 推送内容
  $data  = json_encode(['badge' => 'Increment','alert' => $messageInfo['content'],'title' => $messageInfo['title'],'link' => $messageInfo['link'],'action' => $config['package_name']]);
  // 推送条件
  $where = null; //
  switch($messageInfo['platform']){
    case '1':
      $where = json_encode(['deviceType' => 'ios']);
          break;
    case '2':
      $where = json_encode(['deviceType' => 'android']);
      break;
    case '3':
      $where = json_encode(['deviceType' => 'all']);
      break;
  }

  //推送时间
  $push_time = strtotime($messageInfo['push_at']);
  if ( $push_time < time()) {
    $push_time = time();
  }
  if ($messageInfo['push_type'] == 0) {
    $push_time = null; //即时推送时间不需要
  }else{
    date_default_timezone_set('UTC'); //转换UTC时间给Leancloud
    $push_time = date('Y-m-d\TH:i:s.\0\0\0\Z',$push_time);
  }

  $post = array(
    // "channels" => ['public'], //推送给哪些频道，将作为条件加入 where 对象。
      "data" => json_decode($data,true),// 推送的内容数据，JSON 对象
      "expiration_interval" => 86400, // 消息过期的相对时间，从调用 API 的时间开始算起，单位是「秒」。
    // "expiration_time" => (time() + 86400 * 7), // 消息过期的绝对日期时间
      "prod" => 'prod', // 开发证书（dev）还是生产证书（prod）
      "push_time" => $push_time, // 定期推送时间
  );
  if($device_id){
    $post['deviceToken'] = $device_id;
  }

  if ($where) {
    $post['where'] = json_decode($where,true);// 检索 _Installation 表使用的查询条件，JSON 对象。
  }
  $res = leancloud_client::post("/push",$post);
  $res_objectId = $res['objectId'];
  if ($res_objectId) {
    if ($messageInfo['push_type'] == 0) {
      $sql ="UPDATE ".$ecs->table('push')." SET `isPush`='1',`objectId`='$res_objectId' WHERE id = '$message_id'";
    }else{
      $sql ="UPDATE ".$ecs->table('push')." SET `isPush`='0',`objectId`='$res_objectId' WHERE id = '$message_id'";
    }
    $db->query($sql);
    return true;
  }else{
    return false;
  }
}

function get_config($db,$ecs){
  $sql = "SELECT * FROM ".$ecs->table('config')." WHERE code = 'leancloud' AND status = '1'";
  $res = $db->getAll($sql);
  if($res){
    return $res[0];
  }else{
    return false;
  }
}

function get_msginfo($msg_id,$db,$ecs){
  $sql = "SELECT * FROM ".$ecs->table('push')." WHERE id = '$msg_id'";
  $res = $db->getAll($sql);
  if($res){
    return $res[0];
  }else{
    return false;
  }
}
/**
 * GET
 * 查询定时任务
 */
function scheduledPushMessages($message_id)
{
  $config = Config::where('type','cloud')->where('code','leancloud')->where('status',1)->first();
  if (!$config) {
    throw new \RuntimeException("没有可用的key");
  }

  if (!json_decode($config['config'],true)) {
    throw new \RuntimeException("key不正确");
  }

  $config = json_decode($config['config'],true);

  Client::initialize($config['app_id'], $config['app_key']);

  $messageInfo = Push::findOne($message_id);
  if (!$messageInfo) {
    return false;
  }

  $header = ['X-LC-Id' => $config['app_id'] ,'X-LC-Key' => $config['master_key'].',master' ,'Content-Type' => 'application/json'];

  $res = Client::get("/scheduledPushMessages",[],$header);
  if (!empty($res['results'])) {
    return $res['results'][0]['id'];
  }
  return false;
}


/**
 * DELETE
 * 删除定时
 */
function deleteScheduledPushMessages($message_id)
{
  $config = Config::where('type','cloud')->where('code','leancloud')->where('status',1)->first();
  if (!$config) {
    throw new \RuntimeException("没有可用的key");
  }

  if (!json_decode($config['config'],true)) {
    throw new \RuntimeException("key不正确");
  }

  $config = json_decode($config['config'],true);

  Client::initialize($config['app_id'], $config['app_key']);

  $messageInfo = Push::findOne($message_id);
  if (!$messageInfo) {
    return false;
  }
  // 取消定时任务根据返回结果中最外层的 id
  $result_id = self::scheduledPushMessages($message_id);
  if (!$result_id) {
    return false;
  }

  $header = ['X-LC-Id' => $config['app_id'] ,'X-LC-Key' => $config['master_key'].',master' ,'Content-Type' => 'application/json'];

  Client::delete("/scheduledPushMessages".'/'.$result_id,$header);

  return Push::findOne($message_id);
}


/**
 * GET
 * 查询状态
 */
function notifications($message_id)
{

  $config = Config::where('type','cloud')->where('code','leancloud')->where('status',1)->first();
  if (!$config) {
    throw new \RuntimeException("没有可用的key");
  }

  if (!json_decode($config['config'],true)) {
    throw new \RuntimeException("key不正确");
  }

  $config = json_decode($config['config'],true);

  Client::initialize($config['app_id'], $config['app_key']);

  $messageInfo = Push::findOne($message_id);
  if (!$messageInfo) {
    return false;
  }

  $header = ['X-LC-Id' => $config['app_id'] ,'X-LC-Key' => $config['master_key'] ,'Content-Type' => 'application/json'];
  $res = Client::get("/tables/Notifications/".$messageInfo['objectId'],$header);
  if (($res) && $res["status"] == 'done') {
    Push::where('id',$message_id)->update(['status' => 2]);
  }
  return $messageInfo;
}


