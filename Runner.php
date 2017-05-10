<?php

use Stringy\Stringy as S;

class Runner
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

        if(isset($this->config['include_paths'])) {
            $this->queueWithIncludePaths();
        } else {
            $this->queuePaths($dir, $prefix, $this->config['exclude_paths']);
        }

        $this->server->process_work(false);
    }

    public function queueWithIncludePaths() {
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

    public function queuePaths($dir, $prefix = '', $exclusions = []) {
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

    public function filterByExtension($f, $prefix = '') {
        foreach ($this->fileExtensions() as $file_extension) {
            if (S::create($f)->endsWith($file_extension)) {
                $prefix = ltrim($prefix, "\\/");
                $this->server->addwork(array("$prefix$f"));
            }
        }
    }

    private function fileExtensions() {
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

            $extra_config_options = array('--report=json', '--report-file=' . $resultFile);

            if (isset($this->config['config']['standard'])) {
                $extra_config_options[] = '--standard=' . $this->config['config']['standard'];
            } else {
                $extra_config_options[] = '--standard=PSR1,PSR2';
            }

            if (isset($this->config['config']['ignore_warnings'])) {
                $extra_config_options[] = '-n';
            }

            if (isset($this->config['config']['encoding'])) {
                $extra_config_options[] = '--encoding=' . $this->config['config']['encoding'];
            }

            foreach ($files as $file) {
                $extra_config_options[] = $file;
            }

            // prevent any stdout leakage
            ob_start();

            // setup the code sniffer
            $cli = new PHP_CodeSniffer_CLI();
            $cli->setCommandLineValues($extra_config_options);

            // start the code sniffing
            PHP_CodeSniffer_Reporting::startTiming();
            $cli->checkRequirements();
            $cli->process();

            // clean up the output buffers (might be more that one)
            while (ob_get_level()) {
                ob_end_clean();
            }

            return $resultFile;
        } catch (\Throwable $e) {
            error_log("Exception: " . $e->getMessage() . " in " . $e->getFile() . "\n" . $e->getTraceAsString());
            return $e;
        }
    }
}
