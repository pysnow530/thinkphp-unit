<?php
return array(

    // 添加Library文件夹到命名空间
    "AUTOLOAD_NAMESPACE" => array(
        "Library" => APP_PATH . "../Library",
    ),

    // 测试数据库配置
    "DB_CONFIG_UNIT_TEST" => array(
        "db_type"   => "mysql",
        "db_user"   => "root",
        "db_pwd"    => "123456",
        "db_host"   => "localhost",
        "db_port"   => "3306",
        "db_name"   => "db_demo_unit_test",
    ),

);
