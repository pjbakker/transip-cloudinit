<?php
use Transip\Api\Library\TransipAPI;

require_once(__DIR__ . '/vendor/autoload.php');
$config = include(__DIR__ . '/config.php');
$targets = $config['targets'];

if ($argc != 2)
{
    echo <<<HTML
To re-install VPS, use {$argv[0]} <host_id>

HTML;
    exit();
}

$host_id = $argv[1];
if (!isset($targets[$host_id]))
{
    echo <<<HTML
Unknown host id: {$host_id}

HTML;
    exit();
}

// Target specific template variables
//
$ip = $targets[$host_id]['ip'];
$hostname = $targets[$host_id]['hostname'];
$resize_rootfs = (isset($targets[$host_id]['resize_rootfs'])) ? 'resize_rootfs: '.$targets[$host_id]['resize_rootfs'] : '';

// Generic template variables
//
$account_name = $config['account_name'];
$domain = $config['domain'];
$ssh_public_key = $config['ssh_public_key'];
$salt_master_ip = $config['salt_master_ip'];

// Create templated cloud-config
//
ob_start();
include(__DIR__.'/cloud_config.txt');
$cloud_config = ob_get_clean();

$encoded_cloud_config = base64_encode($cloud_config);

// Connect with API and execute install
//
$api = new TransipAPI(
    $config['login'],
    '',
    true,
    $config['api_token']
);

$response = $api->test()->test();
if ($response === true)
    echo 'API connection successful!'.PHP_EOL;

echo "Forcing re-install of ".$host_id.PHP_EOL;

$response = $api->vpsOperatingSystems()->install(
    $host_id,
    'ubuntu-20.04',
    $hostname,
    $encoded_cloud_config,
    'cloudinit'
);

if ($response == null)
    echo "Re-installing".PHP_EOL;
else
    var_dump($response);
?>
