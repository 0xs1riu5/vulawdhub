<?php


class Mysql{
    private $db_name;
    private $db_host;
    private $db_user;
    private $db_pwd;
    private $conn;
    private $db_code;
    private $sql_id;

    //创建构造函数 数据库名 主机名 用户名 密码  编码方式
    function __construct($dbname,$dbhost,$dbuser,$dbpwd,$code='utf8'){
        $this->db_name=$dbname;
        $this->db_host=$dbhost;
        $this->db_pwd=$dbpwd;
        $this->db_user=$dbuser;
        $this->db_code=$code;
        $this->dbConnect();
        $this->selectDb();
    }
    //连接数据库
    private function dbConnect(){
        $this->conn=mysql_connect($this->db_host,$this->db_user,$this->db_pwd) or die("无法连接到mysql数据库");
        mysql_query("SET NAMES '$this->db_code'");
    }
    private function selectDb(){
        mysql_select_db($this->db_name) or die("切换失败");
    }
    function query($sql){
        return $this->sql_id=mysql_query($sql);
    }

    function fetchArray($query, $result_type = 1) {
        return mysql_fetch_array($query, $result_type);
    }

    /**
     * getOne  获取单条记录
     * @param $sql
     * @return array
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    function getOne($sql) {
        $query = $this->query($sql);
        return $this->fetchArray($query);
    }

    /**
     * getAll  获取多条记录
     * @param $sql
     * @param string $id
     * @return array
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    function getAll($sql, $id = '') {
        $arr = array();
        $query = $this->query($sql);
        while($data = $this->fetchArray($query)) {
            $id ? $arr[$data[$id]] = $data : $arr[] = $data;
        }
        return $arr;
    }

}

?>