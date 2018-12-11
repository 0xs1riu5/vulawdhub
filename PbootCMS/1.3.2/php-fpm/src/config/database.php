<?php
/**
 * 主数据库连接参数，未配置的参数使用框架惯性配置
 * 如果修改为mysql数据库，请同时修改type和dbname两个参数
 */
return array(
    
    'database' => array(
        
        'type' => 'mysqli', // 数据库连接驱动类型: mysqli,sqlite,pdo_mysql,pdo_sqlite
        
        'host' => '127.0.0.1', // 数据库服务器
        
        'user' => 'root', // 数据库连接用户名
        
        'passwd' => 'shadow', // 数据库连接密码
        
        'port' => '3306', // 数据库端口
                          
        'dbname' => 'pbootcms' // 去掉注释，启用mysql数据库，注意修改前面的连接信息及type为mysqli
        
        // 'dbname' => '/data/#pbootcms.db' // 去掉注释，启用Sqlite数据库，注意修改type为sqlite
    )

);