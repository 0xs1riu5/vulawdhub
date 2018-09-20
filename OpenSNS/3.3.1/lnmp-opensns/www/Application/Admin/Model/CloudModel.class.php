<?php
/**
 * 云市场的本地操作Model
 * User: Yixiao Chen
 * Date: 2015/6/30 0030
 * Time: 下午 3:00
 */

namespace Admin\Model;

class CloudModel
{

    protected $curlModel;

    public function checkUpdateByList()
    {

    }

    /**通过Token获取版本信息
     * @param $token
     * @return bool
     */
    public function getVersion($token)
    {
        $return = $this->curl(appstoreU('Appstore/Install/getVersion', array('token' => $token)));
        if ($return === false) {
            return false;
        }
        $goods = json_decode($return, true);
        if ($goods['status'] == 1) {
            if(strpos($goods['version']['goods']['cover_url'],'http://')===false){
                $goods['version']['goods']['cover_url']=cloud_url().$goods['version']['goods']['cover_url'];
            }
            return $goods['version'];
        } else {
            $this->error = $goods['info'];
            return false;
        }
    }

    /**获取升级列表
     * @param $token 通过token获取升级列表
     */
    public function getUpdateList($token)
    {
        $new_versions = array();
        $return = $this->curl(appstoreU('Appstore/Install/getUpdateList', array('token' => $token)));
        $versions = json_decode($return, true);
        if ($versions['status'] == 1) {
            return $versions['updateList'];
        } else {
            $this->error = L('_UPDATED_VERSION_LIST_IS_NOT_PRESENT_WITH_PERIOD_');
            return false;
        }
    }

    /**打开远程连接
     * @param $url 链接地址
     * @return bool
     */
    private function curl($url)
    {
        $this->curlModel = D('Admin/Curl');
        $return = $this->curlModel->curl($url);
        if ($return === false) {
            $this->error = L('_DATA_CONNECTION_ERROR_WITH_PERIOD_') . $this->curlModel->getError();
            return false;
        }
        return $return;
    }

    /**获取版本信息
     * @param $list
     */
    public function getVersionInfoList($list)
    {
        foreach ($list as &$vo) {
            if (!empty($vo['token'])){
                $vo = $this->getVersionInfo($vo);
            }
        }
        return $list;
    }

    public function getVersionInfo($data)
    {
        $data['version_info'] = $this->getVersion($data['token']);
        $data['update_list'] = $this->getUpdateList($data['token']);
        return $data;
    }
}