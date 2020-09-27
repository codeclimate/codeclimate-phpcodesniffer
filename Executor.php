<?php

use Stringy\Stringy as S;
use PHP_CodeSniffer\Runner;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Reporter;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Util\Timing;

require_once __DIR__.'/vendor/squizlabs/php_codesniffer/autoload.php';

class Executor
{
    const DEFAULT_EXTENSIONS = array("php", "inc", "module");

    private $config;
    private $server;

    public function __construct($config, $server)
    {
        $this->config = $config;
        $this->server = $server;
    }

    public function queueDirectory($dir, $prefix = '')
    {
        chdir("/code");

        if (isset($this->config['include_paths'])) {
            $this->queueWithIncludePaths();
        } else {
            $this->queuePaths($dir, $prefix, $this->config['exclude_paths']);
        }

        $this->server->process_work(false);
    }

    public function queueWithIncludePaths()
    {
        foreach ($this->config['include_paths'] as $f) {
            if ($f !== '.' and $f !== '..') {
                if (is_dir($f)) {
                    $this->queuePaths("$f", "$f/");
                } else {
                    $this->filterByExtension($f);
                }
            }
        }
    }

    public function queuePaths($dir, $prefix = '', $exclusions = [])
    {
        $dir = rtrim($dir, '\\/');

        foreach (scandir($dir) as $f) {
            if (in_array("$prefix$f", $exclusions)) {
                continue;
            }

            if ($f !== '.' and $f !== '..') {
                if (is_dir("$dir/$f")) {
                    $this->queuePaths("$dir/$f", "$prefix$f/", $exclusions);
                } else {
                    $this->filterByExtension($f, $prefix);
                }
            }
        }
    }

    public function filterByExtension($f, $prefix = '')
    {
        foreach ($this->fileExtensions() as $file_extension) {
            if (S::create($f)->endsWith($file_extension)) {
                $prefix = ltrim($prefix, "\\/");
                $this->server->addwork(array("$prefix$f"));
            }
        }
    }

    private function fileExtensions()
    {
        $extensions = $this->config['config']['file_extensions'];

        if (empty($extensions)) {
            return self::DEFAULT_EXTENSIONS;
        } else {
            return explode(",", $extensions);
        }
    }

    public function run($files)
    {
        try {
            $resultFile = tempnam(sys_get_temp_dir(), 'phpcodesniffer');
            $config_args = array( '-s', '-p' );

            if (isset($this->config['config']['ignore_warnings']) && $this->config['config']['ignore_warnings']) {
                $config_args[] = '-n';
            }

            Timing::startTiming();

            $runner = new Runner();
            $runner->config = new Config($config_args);

            if (isset($this->config['config']['standard'])) {
                $runner->config->standards = explode(',', $this->config['config']['standard']);
            } else {
                $runner->config->standards = array('PSR1', 'PSR2');
            }

            if (isset($this->config['config']['encoding'])) {
                $runner->config->encoding = $this->config['config']['encoding'];
            }

            $runner->config->reports = array( 'json' => null );
            $runner->config->reportFile = $resultFile;
            $runner->init();

            $runner->reporter = new Reporter($runner->config);

            foreach ($files as $file_path) {
                $file       = new DummyFile(file_get_contents($file_path), $runner->ruleset, $runner->config);
                $file->path = $file_path;
    
                $runner->processFile($file);
            }

            ob_start();

            $runner->reporter->printReports();
            $report = ob_get_clean();

            return $resultFile;
        } catch (\Throwable $e) {
            error_log("Exception: " . $e->getMessage() . " in " . $e->getFile() . "\n" . $e->getTraceAsString());
            return $e;
        }
    }
}
