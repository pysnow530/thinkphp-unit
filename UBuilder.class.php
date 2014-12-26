<?php
/**
 * @file UBuilder.class.php
 * @description 测试数据构造器
 *
 * @author pysnow530, pysnow530@163.com
 * @date Fri Dec 19 16:28:28 2014
 */
namespace Library;

class UBuilder {

    protected $_transactions = array();

    /**
     * 添加新数据到数据表
     * @param $table 表名
     * @param $data  数据数组
     * @return @param 添加后获得的数据条目，包含数据库默认值
     */
    public function insertData($table, $data) {
        $id = M($table)->add($data);
        $data = M($table)->find($id);
        $this->_transactions[] = array(
            "table"   => $table,
            "operate" => "insert data",
            "data"    => $data,
        );

        return $data;
    }

    function __destruct() {
        $transactions = array_reverse($this->_transactions, true);
        foreach ($transactions as $transaction) {
            switch ($transaction["operate"]) {
            case "insert data":
                M($transaction["table"])->delete($transaction["data"]["id"]);
                break;
            default:
                break;
            }
        }
    }

}
