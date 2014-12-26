<?php
/**
 * @file UBuilderController.class.php
 * @description UBuilder测试类
 *
 * @author wujm, wujm@zhsh-168.com
 * @date Fri Dec 19 16:00:46 2014
 */
namespace Home\Controller;

class UBuilderTestController extends \Library\UnitController {

    /**
     * 测试数据插入函数
     */
    public function testAdd() {
        $builder = new \Library\UBuilder;
        $table = "Member";
        $data = $where = array(
            "weixin_openid" => "helloworld",
            "nickname"      => "worldhello",
            "mobile"        => "18888888833",
            "password"      => "password",
            "email"         => "a@b.com",
        );
        $builder->add($table, $data);
        $member = M("Member")->where($where)->find();
        $this->assert(!empty($member));
        unset($builder);
        $member = M("Member")->where($where)->find();
        $this->assert(empty($member));
    }

}
