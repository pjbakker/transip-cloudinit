# TransIP Cloudinit

Straightforward PHP-based script to interact with the [TransIP API](https://api.transip.nl/rest/docs.html) and install a VPS via CloudInit.

This particular script was built for use in the article [Reproducibly provisioning Salt Minions on TransIP](https://brain-dump.space/articles/reproducibly-provisioning-salt-minions-transip/), but the specified cloud config file can be easily changed to suit any purpose.

## Installation

```sh
$ git clone https://github.com/pjbakker/transip-cloudinit
$ cd transip-cloudinit
$ composer install
```

## Configuration

To function you will need a configuration file called *config.php* with the following content structure:

```php
<?php
return array(
    'login' => 'example',
    'domain' => 'example.com',
    'account_name' => 'boss',
    'api_token' => 'TRANSIP_API_TOKEN',
    'ssh_public_key' => 'ssh-ed25519 AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
    'targets' => array(
        'example-vps3' => array(
            'ip' => '192.168.0.10',
            'hostname' => 'consul-server-01',
            ),
        'example-vps4' => array(
            'ip' => '192.168.0.20',
            'hostname' => 'nomad-server-01',
            ),
        'example-vps5' => array(
            'ip' => '192.168.0.30',
            'hostname' => 'docker-01',
            ),
        'example-vps6' => array(
            'ip' => '192.168.0.100',
            'hostname' => 'gluster-01',
            'resize_rootfs' => 'false',
        ),
    ),
);
?>
```

For each TransIP VPS you want to install, an entry in the `targets` array is needed.

## Execution

To install a VPS via Cloudinit, make sure it's internal TransIP name is added to your *config.php* and then run:

```sh
$ php install_vps.php example-vps3
API connection successful!
Forcing re-install of example-vps3
Re-installing
```
