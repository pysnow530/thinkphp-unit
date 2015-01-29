<?php
/**
 * @file UBuilderController.class.php
 * @description UBuilder测试类
 *
 * @author wujm, wujm@zhsh-168.com
 * @date Fri Dec 19 16:00:46 2014
 */
namespace Test\Controller;

class UBuilderTestController extends \Library\UnitController {

    /**
     * 选择测试数据库
     * @param $db_name 数据库名称
     */
    public function switchToDb($db_name) {
        C("DB_NAME", $db_name);
    }

    /**
     * 测试创建表函数
     */
    public function testCreateTable() {
        $builder = new \Library\UBuilder;

        // 如果数据表不存在时创建数据表，builder析构时删除该测试表
        $builder->createTable("foo_bar", array(
            "id" => "int auto_increment primary key",
            "foo" => "varchar(255)",
            "bar"
        ));
        $this->assert($this->_tableExists("foo_bar"));
        unset($builder);
        $this->assert(!$this->_tableExists("foo_bar"));
    }

    private function _tableExists($table) {
        return M()->query("SHOW TABLES LIKE '$table'") ? true : false;
    }

    /**
     * 测试数据插入函数
     */
    public function testInsertData() {
        // 生成测试表
        $tableBuilder = new \Library\UBuilder;
        $table = str_shuffle("pysnowpysnowpysnowpysnow");
        $tableBuilder->createTable($table, array(
            "id" => "int auto_increment primary key",
            "name",
            "gender",
        ));

        // 测试
        $builder = new \Library\UBuilder;
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
