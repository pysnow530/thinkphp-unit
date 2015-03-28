<?php
/**
 * @file UnitTestController.class.php
 * @description 单元测试类
 *
 * @author pysnow530, pysnow530@163.com
 * @date Wed, 20 Aug 2014 12:35:00 GMT
 */
namespace Library;

class UnitTestController extends \Think\Controller {

    /**
     * 执行时间
     */
    protected $_time;

    /**
     * test数目
     */
    protected $_tests;

    /**
     * assert数目
     */
    protected $_assertions;

    /**
     * 失败快照
     */
    protected $_failures;

    public function index() {
        $this->run();
    }

    public function run() {
        $start_time = microtime(true);
        $this->_init();
        $this->_run();
        $end_time = microtime(true);
        $this->_time = $end_time - $start_time;
        $isSuccess = $this->_show();

        return $isSuccess;
    }

    protected function _init() {
        $this->_time = 0;
        $this->_tests = 0;
        $this->_assertions = 0;
        $this->_failures = array();
    }

    protected function _run() {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                ++$this->_tests;
                $this->setUp();
                // 执行测试方法
                call_user_func(array(&$this, $method));

                $this->tearDown();
            }
        }
    }

    protected function _show() {
        if (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) {
            return $this->_showForCgi();
        } elseif (PHP_SAPI == 'cli') {
            return $this->_showForCli();
        } else {
            return $this->_showForCgi();
        }
    }

    protected function _showForCli() {
        echo sprintf('Running test class %s' . PHP_EOL, get_class($this));
        echo sprintf('Time: %.2f seconds' . PHP_EOL, $this->_time);
        if (count($this->_failures) === 0) {
            echo sprintf('OK (Tests: %d, Assertions: %d)' . PHP_EOL,
                    $this->_tests, $this->_assertions);
        } else {
            echo 'FAILURES!' . PHP_EOL;
            echo sprintf("Tests: %d, Assertions: %d, Failures: %d." . PHP_EOL,
                    $this->_tests, $this->_assertions, count($this->_failures));
            foreach (range(0, count($this->_failures) - 1) as $id) {
                echo sprintf('%d) %s:%s' . PHP_EOL,
                        $id, $this->_failures[$id][0]['file'], $this->_failures[$id][0]['line']);

                // print the error assertion statements
                $errcode = '';
                $fp = fopen($this->_failures[$id][0]['file'], 'r');
                $line = 0;
                while (!feof($fp)) {
                    $errcode = fgets($fp);
                    if (++$line == $this->_failures[$id][0]['line'])
                        break;
                }
                fclose($fp);
                echo sprintf('    %s' . PHP_EOL, trim($errcode));
            }
        }
        return (count($this->_failures) === 0);
    }

    protected function _showForCgi() {
        echo '<style>.ok{color:white;background-color:green}</style>';
        echo '<style>.failures{color:white;background-color:red}</style>';
        echo sprintf('Running test class %s<br>', get_class($this));
        echo sprintf('Time: %.2f seconds<br>', $this->_time);
        if (count($this->_failures) === 0) {
            echo sprintf('<span class="ok">OK (Tests: %d, Assertions: %d)</span><br>',
                    $this->_tests, $this->_assertions);
        } else {
            echo '<span class="failures">FAILURES!</span><br>';
            echo sprintf('Tests: %d, Assertions: %d, Failures: %d.<br><br>',
                    $this->_tests, $this->_assertions, count($this->_failures));
            foreach (range(0, count($this->_failures) - 1) as $id) {
                echo sprintf('%d) %s:%s<br>',
                        $id, $this->_failures[$id][0]['file'], $this->_failures[$id][0]['line']);

                // print the error assertion statements
                $errcode = '';
                $fp = fopen($this->_failures[$id][0]['file'], 'r');
                $line = 0;
                while (!feof($fp)) {
                    $errcode = fgets($fp);
                    if (++$line == $this->_failures[$id][0]['line'])
                        break;
                }
                fclose($fp);
                echo sprintf('&nbsp;&nbsp;&nbsp;&nbsp;%s<br>', str_replace('&lt;?php&nbsp;', '', highlight_string('<?php ' . trim($errcode), true)));
            }
        }
        return (count($this->_failures) === 0);
    }

    /**
     * 在每个函数testXxx执行前执行
     */
    public function setUp() { }

    /**
     * 在每个函数testXxx执行后执行
     */
    public function tearDown() { }

    /**
     * 严格测试$x变量是否为true
     * @param $x
     */
    public function assert($x) {
        ++$this->_assertions;
        if (!($x === true)) {
            $this->_failures[] = debug_backtrace();
        }
    }

}

