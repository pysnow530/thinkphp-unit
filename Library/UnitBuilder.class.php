<?php
/**
 * @file UnitBuilder.class.php
 * @description 测试数据构造器
 *
 * @author pysnow530, pysnow530@163.com
 * @date Fri Dec 19 16:28:28 2014
 */
namespace Library;

class UnitBuilder {

    protected $_transactions = array();
    protected $_linkNum;
    protected $_dbConfig;
    protected static $_linkNumCounter = 10;

    /**
     * 构造方法，负责切换数据库
     * $dbConfig 测试数据库配置信息
     */
    public function __construct($dbConfig) {
        $this->_linkNum  = $this->_linkNumCounter++;
        $this->_dbConfig = $dbConfig;
        M()->db($this->_linkNum, $this->_dbConfig);
    }

    /**
     * 添加新数据到数据表
     * @param $table 表名
     * @param $data  数据数组
     * @return @param 添加后获得的数据条目，包含数据库默认值
     */
    public function insertData($table, $data) {
        if ($this->tableExists($table)) {
            $id = M()->db($this->_linkNum)->table($table)->add($data);
            $data = M()->db($this->_linkNum)->table($table)->find($id);
            $this->_transactions[] = array(
                "table"   => $table,
                "operate" => "insert data",
                "data"    => $data,
            );
        } else {
            $log = "Table '$table' is not exists!";
            \Think\Log::write($log, "ERR");
            E($log);
        }

        return $data;
    }

    /**
     * 检测数据是否存在
     * @param $table    表
     * @param $data     数据
     */
    public function dataExists($table, $data) {
        return !!M()->db($this->_linkNum)->table($table)->where($data)->find();
    }

    /**
     * 创建测试表
     * @param $table 表名
     * @param $fields 数据域及类型
     */
    public function createTable($table, $fields) {
        if ($this->tableExists($table)) {
            \Think\Log::write("Table '$table' is already exists!", "WARN");
        } else {
            $sql = "";
            $sql .= "CREATE TABLE `$table` (";
            foreach ($fields as $name => $type) {
                if (is_int($name)) {
                    $name = $type;
                    $type = "VARCHAR(255)";
                } else {
                    $type = strtoupper($type);
                }
                $sql .= "`$name` $type, ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= ")";
            M()->db($this->_linkNum)->execute($sql);

            $this->_transactions[] = array(
                "table" => $table,
                "operate" => "create table",
            );
        }
    }

    /**
     * 检测表是否存在
     * @param $table
     * @return boolean
     */
    public function tableExists($table) {
        return !!M()->db($this->_linkNum)->query("SHOW TABLES LIKE '$table'");
    }

    function __destruct() {
        $transactions = array_reverse($this->_transactions, true);
        foreach ($transactions as $transaction) {
            switch ($transaction["operate"]) {
            case "insert data":
                M()->db($this->_linkNum)->table($transaction["table"])->delete($transaction["data"]["id"]);
                break;
            case "create table":
                M()->db($this->_linkNum)->execute("drop table `$transaction[table]`");
                break;
            default:
                break;
            }
        }
    }

}
