<?php

use Stringy\Stringy as S;

class Runner
{
    private $config;
    private $server;

    public function __construct($config, $server)
    {
        $this->config = $config;
        $this->server = $server;
    }

    public function queueDirectory($dir, $prefix = '')
    {
        $dir = rtrim($dir, '\\/');

        foreach (scandir($dir) as $f) {
            if (in_array("$prefix$f", $this->config["exclude_paths"])) {
                continue;
            }

            if ($f !== '.' and $f !== '..') {
                if (is_dir("$dir/$f")) {
                    $this->queueDirectory("$dir/$f", "$prefix$f/");
                    continue;
                }

                if (isset($this->config['config']['file_extensions'])) {
                    foreach (explode(",", $this->config['config']['file_extensions']) as $file_extension) {
                        if (S::create($f)->endsWith("." . $file_extension)) {
                            $this->server->addwork(array("/code/$prefix$f"));
                        }
                    }
                } else {
                    $this->server->addwork(array("/code/$prefix$f"));
                }
            }
        }

        $this->server->process_work(false);
    }

    public function run($files)
    {
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
    }
}
