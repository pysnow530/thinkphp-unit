<?php
/**
 * @file UnitTestTestController.class.php
 * @description 测试单元测试控制器
 *
 * @author pysnow530, pysnow530@163.com
 * @date 2014年12月26日 13:25:58
 */
namespace Test\Controller;

class UnitTestTestController extends \Library\UnitTestController {

    public function setUp() {
        $this->class = "UnitTestController";
    }

    public function tearDown() {
        $this->hello = "hello, world";
    }

    public function testSetUp() {
        $this->assert($this->class == "UnitTestController");
    }

    public function testTearDown() {
        $this->assert($this->hello == "hello, world");
    }

    public function testAssert() {
        $this->assert(1 === 1);
        $this->assert(true);
    }

}
