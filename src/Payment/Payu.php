<?php

namespace Wontonee\Payu\Payment;

use Webkul\Payment\Payment\Payment;

class Payu extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'payu';

    public function getRedirectUrl()
    {
        return route('payu.process');
        
    }
}