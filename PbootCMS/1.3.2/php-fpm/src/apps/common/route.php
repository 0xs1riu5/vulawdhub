<?php
return array(
    // URL地址路由，如：// 'home/index' => 'home/index/index'
    'url_route' => array(
        // =======管理端路由============
        // 系统模块路由
        'admin/Area' => 'admin/system.Area',
        'admin/Menu' => 'admin/system.Menu',
        'admin/Role' => 'admin/system.Role',
        'admin/User' => 'admin/system.User',
        'admin/Type' => 'admin/system.Type',
        'admin/Syslog' => 'admin/system.Syslog',
        'admin/Database' => 'admin/system.Database',
        'admin/Config' => 'admin/system.Config',
        'admin/Upgrade' => 'admin/system.Upgrade',
        
        // 内容发布模块路由
        'admin/Site' => 'admin/content.Site',
        'admin/Company' => 'admin/content.Company',
        'admin/Label' => 'admin/content.Label',
        'admin/Model' => 'admin/content.Model',
        'admin/ExtField' => 'admin/content.ExtField',
        'admin/ContentSort' => 'admin/content.ContentSort',
        'admin/Content' => 'admin/content.Content',
        'admin/Single' => 'admin/content.Single',
        'admin/Message' => 'admin/content.Message',
        'admin/Slide' => 'admin/content.Slide',
        'admin/Link' => 'admin/content.Link',
        'admin/Form' => 'admin/content.Form',
        
        // =======前端路由============为前端美观，使用了小写URL，此处也用小写
        'home/index' => 'home/index/index',
        'home/list' => 'home/list/index/scode',
        'home/about' => 'home/about/index/scode',
        'home/content' => 'home/content/index/id',
        'home/sitemap.xml' => 'home/Sitemap/index',
        
        // =======接口路由============API路径统一小写URL，此处也用小写
        'api/list' => 'api/list/index/scode',
        'api/content' => 'api/content/index/id',
        'api/about' => 'api/about/index/scode',
        'api/search' => 'api/search/index'
    
    )
);