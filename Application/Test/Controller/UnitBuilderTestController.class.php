<?php
/**
 * @file UnitBuilderController.class.php
 * @description UnitBuilder测试类
 *
 * @author wujm, wujm@zhsh-168.com
 * @date Fri Dec 19 16:00:46 2014
 */
namespace Test\Controller;

class UnitBuilderTestController extends \Library\UnitController {

    /**
     * 测试创建表函数
     */
    public function testCreateTable() {
        $testBuilder  = new \Library\UnitBuilder;
        $tableBuilder = new \Library\UnitBuilder;

        // 如果数据表不存在时创建数据表，builder析构时删除该测试表
        $tableBuilder->createTable("foo_bar", array(
            "id" => "int auto_increment primary key",
            "foo" => "varchar(255)",
            "bar"
        ));
        $this->assert($testBuilder->tableExists("foo_bar"));
        unset($tableBuilder);
        $this->assert(!$testBuilder->tableExists("foo_bar"));
    }

    /**
     * 测试数据插入函数
     */
    public function testInsertData() {
        $testBuilder  = new \Library\UnitBuilder;
        $tableBuilder = new \Library\UnitBuilder;

        $table = str_shuffle("pysnowpysnowpysnowpysnow");
        $tableBuilder->createTable($table, array(
            "id" => "int auto_increment primary key",
            "name",
            "gender",
        ));

        // 测试
        $builder = new \Library\UnitBuilder;
        $data = $where = array(
            "name" => "pysnow",
            "gender" => "man",
        );
        $builder->insertData($table, $data);
        $member = M($table)->where($where)->find();
        $this->assert(!empty($member));
        unset($builder);
        $member = M($table)->where($where)->find();
        $this->assert(empty($member));

        unset($tableBuilder);
    }

}
