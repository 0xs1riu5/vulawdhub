<?php
    /**
     * SmileModel
     * 表情数据库.
     *
     * @uses BaseModel
     *
     * @version $id$
     *
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class SmileModel extends Model
    {
        public function getSmile($type)
        {
            $smile = ts_cache('smile_mini');
            if ($smile) {
                return $smile;
            } else {
                $data = $this->where("type='mini'")->findAll();

                return $data;

               //return $this->setCache( $data,$type );
            }
        }
    }
