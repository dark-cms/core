#!/usr/bin/env php
<?php

/*
 * Constants to run tests
 */
if (!defined('SELENIUM_CLIENTS_COUNT'))
    define('SELENIUM_CLIENTS_COUNT', 5);

/**
 * @param array $array
 * @param closure $callback
 * @return bool
 */
function array_any(array $array, $callback)
{
    foreach ($array as $element) {
        if (call_user_func($callback, $element))
            return true;
    }
    return false;
}

/**
 * @param array $array
 * @param closure $callback
 * @return bool
 */
function array_all(array $array, $callback)
{
    return !array_any(
        $array,
        function ($el) use ($callback)
        {
            return !call_user_func($callback, $el);
        });
}


define('PATH_TESTS', realpath(__DIR__ . '/tests'));
define('PATH_ROOT', realpath(__DIR__ . '/..'));

define('PATH_SRC', realpath(PATH_ROOT . '/src'));

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . PATH_SRC . '/classes'
    . PATH_SEPARATOR . PATH_SRC . '/var/run/classes'
    . PATH_SEPARATOR . PATH_SRC
);

require_once PATH_SRC . '/top.inc.php';


function xlite_restore_sql_from_backup($path = null, $verbose = true, $drop = true, &$message = null)
{
    !$verbose && ob_start();

    echo (PHP_EOL . 'DB restore ... ');

    \Includes\Utils\FileManager::copyRecursive(__DIR__ . '/images', LC_DIR_IMAGES);

    $result = true;

    if (!isset($path)) {
        $path = dirname(__FILE__) . LC_DS . 'dump.sql';
    }

    if (file_exists($path)) {

        $config = \XLite::getInstance()->getOptions('database_details');

        $cmd = defined('TEST_MYSQL_BIN') ? TEST_MYSQL_BIN : 'mysql';
        $cmd .= ' -h' . $config['hostspec'];

        if ($config['port']) {
            $cmd .= ' -P' . $config['port'];
        }

        $cmd .= ' -u' . $config['username'] . ('' == $config['password'] ? '' : (' -p' . $config['password']));

        if ($config['socket']) {
            $cmd .= ' -S' . $config['socket'];
        }

        $message = '';

        if ($drop) {

            // Drop&Create database

            exec($cmd . ' -e"drop database ' . $config['database'] . '"', $message);

            if (empty($message)) {
                exec($cmd . ' -e"create database ' . $config['database'] . '"', $message);
            }
        }

        if (empty($message)) {
            exec($cmd . ' ' . $config['database'] . ' < ' . $path, $message);
        }

        if (empty($message)) {
            echo ('done' . PHP_EOL);

        } else {
            $result = false;
            echo ('failed: ' . $message . PHP_EOL);
        }

    } else {
        echo ('ignored (sql-dump file not found)' . PHP_EOL);
        $result = false;
    }

    !$verbose && ob_end_clean();

    return $result;
}

function xlite_make_sql_backup($path = null)
{
    // DB backup
    echo (PHP_EOL . 'DB backup ... ');

    \Includes\Utils\FileManager::unlinkRecursive(__DIR__ . '/images');
    \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images');
    \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images/product');
    \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images/category');
    \Includes\Utils\FileManager::copyRecursive(LC_DIR_IMAGES, __DIR__ . '/images');

    $result = true;

    if (!isset($path)) {
        $path = dirname(__FILE__) . LC_DS . 'dump.sql';
    }

    if (file_exists(dirname($path))) {

        if (file_exists($path)) {
            unlink($path);
        }

        $config = \XLite::getInstance()->getOptions('database_details');

        $cmd = defined('TEST_MYSQLDUMP_BIN') ? TEST_MYSQLDUMP_BIN : 'mysqldump';
        $cmd .= ' --opt -h' . $config['hostspec'];

        if ($config['port']) {
            $cmd .= ' -P' . $config['port'];
        }

        $cmd .= ' -u' . $config['username'] . ('' == $config['password'] ? '' : (' -p' . $config['password']));

        if ($config['socket']) {
            $cmd .= ' -S' . $config['socket'];
        }

        $cmd .= ' ' . $config['database'];

        exec('echo "SET autocommit=0;
        SET unique_checks=0;
        SET foreign_key_checks=0;" > ' . $path . '
        ' . $cmd  . ' >> ' . $path . '
        echo "COMMIT;" >> ' . $path);

        echo ('done' . PHP_EOL);

        sleep(1);

    } else {
        $result = false;
    }

    if (!$result) {
        echo ('ignored' . PHP_EOL);
    }

    return $result;
}


class testRunner
{

    /**
     * @var TestTask[]
     */
    protected $tests;
    /**
     * @var ResourcePool
     */
    protected $resources;
    /**
     * @var int
     */
    protected $clientsCount;

    /**
     * @var bool
     */
    protected $block_all;

    static private function getTests()
    {

        if (!defined('DIR_TESTS')) {
            define('DIR_TESTS', 'tests' . DIRECTORY_SEPARATOR . 'Web');
        }
        $classesDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . constant('DIR_TESTS') . DIRECTORY_SEPARATOR;

        $pattern = '/^' . preg_quote($classesDir, '/') . '(.*)\.php$/';

        $dirIterator = new RecursiveDirectoryIterator($classesDir);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
        $ds = preg_quote(DIRECTORY_SEPARATOR, '/');

        $tests = array();
        foreach ($iterator as $filePath => $fileObject) {

            if (
                preg_match($pattern, $filePath, $matches)
                && !empty($matches[1])
                && !preg_match('/' . $ds . '(\w+Abstract|A[A-Z]\w+)\.php/Ss', $filePath)
                && !preg_match('/' . $ds . '(\w+WebDriver\w+)\.php/Ss', $filePath)
                && !preg_match('/' . $ds . '(?:scripts|skins)' . $ds . '/Ss', $filePath)
            ) {

                $tests[] = new TestTask($filePath, $classesDir);
            }
        }
        return $tests;

    }

    function __construct()
    {
        $this->tests = self::getTests();
        $this->resources = new ResourcePool();
        array_map(function (TestTask $test)
            {
                print $test->toString();
            }, $this->tests);
    }

    function start($clientsCount)
    {
        $time = microtime(true);
        xlite_make_sql_backup();
        exec("rm /tmp/output*");
        $this->clientsCount = $clientsCount;
        while (true)
        {
            $this->run();

            sleep(5);

            $this->clean();

            if ($this->isComplete())
                break;
        }
        $this->cleanResources();
        print PHP_EOL . " Total time: " . round(microtime(true) - $time, 2) . "sec";
    }

    private function isComplete()
    {
        return array_all(
            $this->tests,
            function($test)
            {
                return $test->status == 'complete' || $test->status == 'error' || $test->status == 'abstract';
            }
        );
    }

    private function isRunning()
    {
        return array_any(
            $this->tests,
            function($test)
            {
                return $test->status == 'running';
            }
        );
    }

    private function isBlocked()
    {
        return $this->resources->isBlocked();
    }

    private function clean()
    {
        foreach ($this->tests as $test)
        {
            if ($test->isForClean()) {
                $test->stop($this->resources);
                if ($test->block_all)
                    $this->block_all = false;
                $this->clientsCount++;
            }
        }
    }

    private function run()
    {
        foreach ($this->tests as $test)
        {
            if ($this->block_all)
                return;
            if ($this->clientsCount == 0)
                return;

            if ($this->isBlocked())
                if (!$this->isRunning())
                    $this->cleanResources();
                else
                    return;

            if ($test->isForRun($this->resources)) {
                $test->run($this->resources);
                if ($test->block_all)
                    $this->block_all = true;
                $this->clientsCount--;
            }
        }
    }

    private function cleanResources()
    {
        xlite_restore_sql_from_backup();
        sleep(1);
        $this->resources->reset();
    }
}


class TestTask
{
    public $name;
    public $resources = array();
    public $uses = array();
    public $block_all = false;
    public $status = 'init';
    /**
     * @var Resource
     */
    public $process = null;

    function __construct($filePath, $classesDir)
    {
        $this->name = substr(str_replace($classesDir, '', $filePath), 0, -4);
        $source = file_get_contents($filePath);

        $comments = token_get_all($source);

        $resources = array();
        $uses = array();
        $block_all = false;
        foreach ($comments as $key => $comment) {
            if ($comment[0] == T_DOC_COMMENT) {
                $resources = array_merge($resources, self::getResources($comment[1], 'resource'));
                $uses = array_merge($uses, self::getResources($comment[1], 'use'));
                $block_all = $block_all || preg_match('/^.*\@block_all\s*$/Sm', $comment[1]) > 0;
            }
        }

        if ($block_all) {
            $this->block_all = true;
        }
        else {
            $this->resources = $resources;
            $this->uses = $uses;
        }
    }

    private static function getResources($comment, $res_string)
    {
        preg_match_all('/^.*\@' . $res_string . '\s+([a-zA-Z_-]+)\s*$/Sm', $comment, $result);
        $resources = $result[1];
        foreach ($resources as $resource) {
            if (strpos($resource, '_')) {
                $parts = explode('_', $resource);
                $resource = $parts[0];
            }
        }
        return $resources;
    }

    function run(ResourcePool $resources)
    {
        $pipes = null;
        $testName = str_replace('/', '_', $this->name);
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array("file", "/tmp/output-" . $testName . ".txt", "a"),
            2 => array("file", "/tmp/errors-" . $testName . ".txt", "a")
        );
        //Fake run
        //$this->process = proc_open("sleep " . rand(2, 4), $descriptorspec, $pipes);
        //Real run
        $this->process = proc_open('./phpunit_no_restore.sh ' . $this->name, $descriptorspec, $pipes);
        if ($this->process) {
            print PHP_EOL . "Running test: " . $this->name;
            $this->status = 'running';
            foreach ($this->resources as $resource) {
                $resources->addResource($resource);
            }
        }
        else {
            $this->status = 'error';
        }

    }

    function stop(ResourcePool $resources)
    {
        print PHP_EOL . "Stopping test: " . $this->name;
        $this->status = 'complete';
        proc_close($this->process);
        foreach (array_merge($this->resources, $this->uses) as $resource) {
            $resources->cleanResource($resource);
        }
    }

    private function isRunning()
    {
        if ($this->status != 'running' || $this->process == null)
            return false;
        $status = proc_get_status($this->process);
        return $status['running'];
    }

    function isForClean()
    {
        return $this->status == 'running' && !$this->isRunning();
    }

    function isForRun(ResourcePool $resources)
    {
        if ($this->block_all && !$resources->isEmpty())
            return false;
        if ($this->status != 'init')
            return false;
        return $resources->checkAccess($this->resources, $this->uses);
    }

    function toString()
    {
        return PHP_EOL . "Name: " . $this->name .
               PHP_EOL . " Resources: {" . implode(';', $this->resources) . '} ' .
               PHP_EOL . " Uses: {" . implode(';', $this->uses) . '}' .
               PHP_EOL . " Blocks all: <" . ($this->block_all ? 'true' : 'false') . "> " .
               PHP_EOL . " Status: <" . $this->status . ">" . PHP_EOL;
    }
}

define('RESOURCE_RESERVED', 1);
define('RESOURCE_USED', 2);

class ResourcePool
{

    private $resources = array();

    public function isEmpty()
    {
        return empty($this->resources);
    }

    public function checkAccess($resources, $uses)
    {
        //        print PHP_EOL . "Reserved resources: ";
        //        print_r($this->resources);
        //        print PHP_EOL . "Resources: ";
        //        print_r($resources);
        //        print PHP_EOL . "Uses: ";
        //        print_r($uses);


        if (array_intersect(array_keys($this->resources), $resources)) {
            return false;
        }
        foreach ($uses as $use) {
            if (array_key_exists($use, $this->resources) && $this->resources[$use] != RESOURCE_USED)
                return false;
        }
        return true;
    }

    public function addUse($use)
    {
        if (array_key_exists($use, $this->resources) && $this->resources[$use] != RESOURCE_USED)
            throw new Exception("Resource " . $use . " is reserved!");
        $this->resources[$use] = RESOURCE_USED;
    }

    public function deleteUse($use)
    {
        if (array_key_exists($use, $this->resources) && $this->resources[$use] != RESOURCE_USED)
            throw new Exception("Resource " . $use . " is reserved!");
        unset($this->resources[$use]);
    }

    public function addResource($resource)
    {
        if (array_key_exists($resource, $this->resources))
            throw new Exception("Resource " . $resource . " is reserved or used!");
        $this->resources[$resource] = RESOURCE_RESERVED;
    }

    public function cleanResource($resource)
    {
        if (!array_key_exists($resource, $this->resources))
            return;
        //throw new Exception("No such resource: " .$resource);
        if ($this->resources[$resource] == RESOURCE_USED)
            unset($this->resources[$resource]);
        else
            $this->resources[$resource] = 0;
    }

    public function isBlocked()
    {
        if (!empty($this->resources) && array_all($this->resources, function ($res)
            {
                return $res != RESOURCE_RESERVED;
            })
        ) {
            print PHP_EOL . " BLOCKED";
            return true;
        }
        return false;
    }

    public function reset()
    {
        if (array_any($this->resources, function ($res)
            {
                $res != 0;
            })
        ) {
            print_r($this->resources);
            throw new Exception("There is some reserved or used resources");
        }
        $this->resources = array();
    }
}

$runner = new testRunner();
$runner->start(SELENIUM_CLIENTS_COUNT);


