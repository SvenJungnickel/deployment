<?php
/**
 * GIT DEPLOYMENT SCRIPT
 *
 * Used for automatically deploying websites via bitbucket securely, more deets here:
 *
 *      https://gist.github.com/limzykenneth/baef1b190c68970d50e1
 */

// The header information which will be verified
$agent = $_SERVER['HTTP_USER_AGENT'];
$environment = getenv('ENVIRONMENT');
$body = @file_get_contents('php://input');

// The commands
$commands = array(
    'git pull origin ' . $environment,
    'git submodule sync',
    'git submodule update',
);

base64_encode($agent);

if (strpos($agent,'Bitbucket-Webhooks/2.0') !== false){
    // Run the commands
    $output = '';
    foreach($commands AS $command){
        // Run it
        $tmp = shell_exec("$command 2>&1");

        // Output
        $output .= $command . "\n";
        $output .= htmlentities(trim($tmp)) . "\n";
    }

    echo $output;
}else{
    header('HTTP/1.1 403 Forbidden');
    echo "Wrong User Agent";
}