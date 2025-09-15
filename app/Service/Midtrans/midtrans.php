<?php

namespace App\Service\Midtrans;

use Midtrans\Config;


class Midtrans{
    protected $serverKey;
    protected $isProduction;
    protected $clientKey;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->clientKey = config('midtrans.client_key');
        $this->is3ds = config('midtrans.is_3ds');

        $this->_configureMindtrans();
    }

    public function _configureMindtrans()    {
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$clientKey = $this->clientKey;
        Config::$is3ds = $this->is3ds;
    }
}



