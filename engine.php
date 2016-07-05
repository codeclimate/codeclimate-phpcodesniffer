<?php

// Hooking into Composer's autoloader
require_once __DIR__.'/vendor/autoload.php';
require_once "Runner.php";
require_once "Sniffs.php";

use Sniffs\Sniffs;

// Suppress warnings
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('UTC');
ini_set('memory_limit', -1);

// Obtain the config
$config = json_decode(file_get_contents('/config.json'), true);

// Setup forking daemon
$server = new \fork_daemon();
$server->max_children_set(3);
$server->max_work_per_child_set(50);
$server->store_result_set(true);
$runner = new Runner($config, $server);
$server->register_child_run(array($runner, "run"));

$runner->queueDirectory("/code");

$server->process_work(true);

// Process results

$results = $server->get_all_results();

// If there is no output from the runner, an exception must have occurred
if (count($results) == 0) {
    exit(1);
}

foreach ($results as $result_file) {
    $phpcs_output = json_decode(file_get_contents($result_file), true);
    unlink($result_file);

    if (is_array($phpcs_output['files'])) {
        foreach ($phpcs_output['files'] as $phpcs_file => $phpcs_issues) {
            foreach ($phpcs_issues['messages'] as $phpcs_issue_data) {
                if (Sniffs::isValidIssue($phpcs_issue_data)) {
                    $cleaned_single_issue = array(
                        'type' => 'issue',
                        'check_name' => str_replace('.', ' ', $phpcs_issue_data["source"]),
                        'description' => $phpcs_issue_data['message'],
                        'categories' => array('Style'),
                        'location' => array(
                            'path' => preg_replace('/^\/code\//', '', $phpcs_file),
                            'lines' => array(
                                'begin' => $phpcs_issue_data['line'],
                                'end' => $phpcs_issue_data['line']
                            )
                        ),
                        'remediation_points' => Sniffs::pointsFor($phpcs_issue_data)
                    );
                    file_put_contents(
                        'php://stdout',
                        json_encode($cleaned_single_issue, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE)
                    );
                    file_put_contents('php://stdout', chr(0));
                }
            }
        }
    }
}
