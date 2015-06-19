<?php

/* Hooking into Composer's autoloader */
require_once __DIR__.'/vendor/autoload.php';


/* Starting Symfony's Process to access CLI */
use Symfony\Component\Process\Process;

/*Pulling in the environmental variables we need */
$cc_config = json_decode(getenv('ENGINE_CONFIG'), true);

$extra_config_options = array('--report=json');

if (isset($cc_config['config']['file_extensions'])) {
    $extra_config_options[] = '--extensions=' . $cc_config['config']['file_extensions'];
}
if (isset($cc_config['config']['custom_exclude_paths']) || isset($cc_config['exclude_paths'])) {
    if (is_array($cc_config['exclude_paths'])) {
        if(isset($cc_config['config']['custom_exclude_paths'])) {
            $cc_config['exclude_paths'][] = $cc_config['config']['custom_exclude_paths'];
        }
        $cc_exclude_paths = implode(',',$cc_config['exclude_paths']);
    }
    else {
        $cc_exclude_paths = $cc_config['config']['custom_exclude_paths'];
    }
    $extra_config_options[] = "--ignore=$cc_exclude_paths";
}
if (isset($cc_config['config']['standard'])) {
    $extra_config_options[] = '--standard=' . $cc_config['config']['standard'];
}
else {
    $extra_config_options[] = '--standard=PSR1,PSR2';
}

$phpcs_config_options = implode(' ',$extra_config_options);

$process = new Process("./vendor/bin/phpcs $phpcs_config_options /code");
$process->run();

$phpcs_output = json_decode($process->getOutput(), true);

if (is_array($phpcs_output['files'])) {
    foreach($phpcs_output['files'] as $phpcs_file => $phpcs_issues) {
        $numIssues = count($phpcs_issues['messages']);
        $iterations = 0;
        foreach($phpcs_issues['messages'] as $phpcs_issue_data) {
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
                'remediation_points' => (($phpcs_issue_data['severity'] * $phpcs_issue_data['severity']) . '00')
            );
            $iterations++;

            file_put_contents('php://stdout', json_encode($cleaned_single_issue, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE));
            if($iterations < $numIssues) {
                file_put_contents('php://stdout', chr(0));
            }

        }
    }
}

exit(0);
