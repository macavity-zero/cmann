<?php

namespace App\Controllers;

use \Exception;
use \DB\MyRedis;

class Starter extends BaseController
{
    private $assert_file_exists = [
        '/usr/local/nagios/etc/' => [
            'nagios.cfg',
            'resource.cfg'
        ],
        '/usr/local/nagios/etc/objects/' => [
            'commands.cfg',
            'contacts.cfg',
            'localhost.cfg',
            'printer.cfg',
            'switch.cfg',
            'templates.cfg',
            'timeperiods.cfg',
            'windows.cfg'
        ]
    ];
    public function index()
    {
        $this->chk_nagios_installation(); // check nagios core config files health
        try {
            //
        }catch(Exception $imp){
            return view('errors/html/installation', [
                'message' => $imp->getMessage()
            ]);
        }
    }

    private function chk_nagios_installation()
    {
        try {
            if (!is_file('/usr/local/nagios/bin/nagios')) {
                throw new Exception('nagios core installation not found in /usr/local/nagios');
            }
            if (version_compare(PHP_VERSION, '7.0.0') < 0) {
                throw new Exception('Old PHP version!, you are currently using PHP' . PHP_VERSION . ' upgrade your php version!');
            }
            foreach ($this->assert_file_exists as $path => $files) {
                foreach ($files as $file) {
                    if (!is_file($path . $file)) {
                        throw new Exception("Broken config file {$path}{$file}.");
                    }
                }
            }
        } catch (Exception $imp) {
            return view('errors/html/installation', [
                'message' => $imp->getMessage()
            ]);
        }
    }
}
