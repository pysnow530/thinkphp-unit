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

    public function add($table, $data) {
        $id = M($table)->add($data);
        $data = M($table)->find($id);
        $this->_transactions[] = array(
            "table"   => $table,
            "operate" => "add",
            "data"    => $data,
        );

        return $data;
    }

    function __destruct() {
        $transactions = array_reverse($this->_transactions, true);
        foreach ($transactions as $transaction) {
            switch ($transaction["operate"]) {
            case "add":
                M($transaction["table"])->delete($transaction["data"]["id"]);
                break;
            default:
                break;
            }
        }
    }

}
