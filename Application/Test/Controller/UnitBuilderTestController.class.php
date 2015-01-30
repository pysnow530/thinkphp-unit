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

    protected $_tableName;

    public function setUp() {
        $this->_tableName = $this->_generateTableName();
    }

    public function tearDown() {
        $this->_tableName = null;
    }

    /**
     * 测试创建表函数
     */
    public function testCreateTable() {
        $testBuilder  = $this->_getBuilder();
        $tableBuilder = $this->_getBuilder();

        // 如果数据表不存在时创建数据表，builder析构时会自动删除该测试表
        $tableBuilder->createTable($this->_tableName, array(
            "id"        => "int auto_increment primary key",
            "name"      => "varchar(255)",
            "gender",       // 如果类型不存在，默认为varchar(255)
        ));
        $this->assert($testBuilder->isTableExists($this->_tableName));
        unset($tableBuilder);
        $this->assert(!$testBuilder->isTableExists($this->_tableName));
    }

    /**
     * 测试数据插入函数
     */
    public function testInsertData() {
        // 创建测试表
        $tableBuilder = $this->_getBuilder();
        $tableBuilder->createTable($this->_tableName, array(
            "id"        => "int auto_increment primary key",
            "name",
            "gender",
        ));

        // 添加测试数据
        $dataBuilder = $this->_getBuilder();
        $data = array(
            "name"      => "pysnow",
            "gender"    => "man",
        );
        $dataBuilder->insertData($this->_tableName, $data);

        // 析构前
        $isDataExists = $tableBuilder->isDataExists($this->_tableName, $data);
        $this->assert($isDataExists);

        // 析构后
        unset($dataBuilder);
        $isDataExists = $tableBuilder->isDataExists($this->_tableName, $data);
        $this->assert(empty($member));
    }

    /**
     * 测试判断数据表存在函数
     */
    public function testIsTableExists() {
        $tableBuilder = $this->_getBuilder();

        $tableExists  = $tableBuilder->isTableExists($this->_tableName);
        $this->assert(!$tableExists);

        $tableBuilder->createTable($this->_tableName, array("id", "name"));
        $tableExists  = $tableBuilder->isTableExists($this->_tableName);
        $this->assert($tableExists);
    }

    /**
     * 测试判断数据存在函数
     */
    public function testIsDataExists() {
        $tableBuilder = $this->_getBuilder();
        $tableBuilder->createTable($this->_tableName, array(
            "id" => "int auto_increment primary key",
            "name",
            "gender",
        ));

        $data = array("id" => "1", "name" => "pysnow", "gender" => "man");
        $dataExists = $tableBuilder->isDataExists($this->_tableName, $data);
        $this->assert(!$dataExists);

        $tableBuilder->insertData($this->_tableName, $data);
        $dataExists = $tableBuilder->isDataExists($this->_tableName, $data);
        $this->assert($dataExists);
    }


    /**
     * 获取builder
     */
    protected function _getBuilder() {
        $builder = new \Library\UnitBuilder("DB_CONFIG_UNIT_TEST");
        return $builder;
    }

    /**
     * 生成随机表名
     */
    protected function _generateTableName() {
        $str = "pysnowpysnowpysnowpysnow";
        $tableName = str_shuffle($str);

        return $tableName;
    }

}
