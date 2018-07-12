<?php

class DirModel extends Model
{
    public $tableName = 'group_attachment';

    //删除文件
    public function delfile($id)
    {
        $id = intval($id);
        $fileInfo = $this->where('id='.$id)->find();
        if (empty($fileInfo)) {
            return false;
        }
        @unlink($fileInfo['fileurl']);        //删除文件
        return $this->where('id='.$id)->delete();
    }

    //获取文件
  /**
   * getGroupList.
   */
  public function getFileList($html = 1, $map = null, $fields = null, $order = null, $limit = null, $isDel = 0)
  {
      //处理where条件
      if (!$isDel) {
          $map[] = 'ga.is_del=0';
      } else {
          $map[] = 'ga.is_del=1';
      }

      $map = implode(' AND ', $map);
      //连贯查询.获得数据集
      // $result         = $this->where( $map )->field( $fields )->order( $order )->findPage( $limit);
      $map .= ' AND a.attach_id IS NOT NULL';
      $result = M()->Table('`'.C('DB_PREFIX').'group_attachment` AS ga LEFT JOIN `'.C('DB_PREFIX').'attach` AS a ON a.attach_id = ga.attachId')
                   ->field('ga.*')
                   ->where($map)
                   ->order($order)
                   ->findPage($limit);
      if ($html) {
          return $result;
      }

      return $result['data'];
  }

  //回收站 文件，包括附件
  public function remove($id)
  {
      $id = is_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收

    $attachIds = array();
      $files = $this->field('uid,attachId')->where('id IN'.$id)->findAll();

      $result = D('Dir')->where('id IN'.$id)->delete();
      if ($result) {
          foreach ($files as $k => $v) {
              $attachIds[] = $v['attachId'];
            // 积分
//         X('Credit')->setUserCredit($v['uid'], 'group_delete_file');
          }
      //处理附件
      model('Attach')->doEditAttach($attachIds, 'delAttach');

          return true;
      }

      return false;
  }
}
