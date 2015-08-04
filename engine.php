<?php

/* Hooking into Composer's autoloader */
require_once __DIR__.'/vendor/autoload.php';

/* Suppress warnings */
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
date_default_timezone_set('UTC');
ini_set('memory_limit', -1);

/* Starting Symfony's Process to access CLI */
use Symfony\Component\Process\Process;

/* Read the configuration from the filesystem */
$config_json = file_get_contents('/config.json');
$cc_config = json_decode($config_json, true);

$report_file = tempnam(sys_get_temp_dir(), 'phpcs');
$extra_config_options = array('--report=json', '--report-file=' . $report_file);

if (isset($cc_config['config']['file_extensions'])) {
    $extra_config_options[] = '--extensions=' . $cc_config['config']['file_extensions'];
}

if (isset($cc_config['config']['standard'])) {
    $extra_config_options[] = '--standard=' . $cc_config['config']['standard'];
} else {
    $extra_config_options[] = '--standard=PSR1,PSR2';
}

if ($cc_config["exclude_paths"]) {
    foreach ($cc_config["exclude_paths"] as $exclude_path) {
        $extra_config_options[] = "--ignore=/code/" . $exclude_path;
    }
}

$extra_config_options[] = "/code";

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

$phpcs_output = json_decode(file_get_contents($report_file), true);

if (is_array($phpcs_output['files'])) {
    foreach ($phpcs_output['files'] as $phpcs_file => $phpcs_issues) {
        foreach ($phpcs_issues['messages'] as $phpcs_issue_data) {
            $cleaned_single_issue = array(
                'type' => 'issue',
                'check_name' => str_replace('.', ' ', $phpcs_issue_data['source']),
                'description' => $phpcs_issue_data['message'],
                'categories' => array('Style'),
                'location' => array(
                    'path' => str_replace('/code/', '', $phpcs_file),
                    'lines' => array(
                        'begin' => $phpcs_issue_data['line'],
                        'end' => $phpcs_issue_data['line']
                    )
                ),
                'remediation_points' => $phpcs_issue_data['severity'] * 75000
            );
            file_put_contents(
                'php://stdout',
                json_encode($cleaned_single_issue, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE)
            );
            file_put_contents('php://stdout', chr(0));
        }
    }
}
