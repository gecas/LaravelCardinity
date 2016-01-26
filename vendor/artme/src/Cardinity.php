<?php

namespace Artme\Cardinity;

use Cardinity\Exception\Declined;
use Illuminate\Support\Facades\Config;
use Cardinity\Client;
use Cardinity\Method\Payment\Create;

class Cardinity {

    public static function getRequiredFields(){
        return ['pan', 'exp_year', 'exp_month', 'cvc', 'holder', 'amount', 'order_id'];
    }

    public static function makePayment($data){
        $client = Client::create([
            'consumerKey' => Config::get('cardinity.customer_key'),
            'consumerSecret' => Config::get('cardinity.customer_secret')
        ]);

        $method = new Create([
            'amount' => floatval(isset($data['amount'])?$data['amount']:0),
            'currency' => Config::get('cardinity.currency'),
            'settle' => Config::get('cardinity.settle'),
            'description' => isset($data['description'])?$data['description']:'',
            'order_id' => isset($data['order_id'])?(string)$data['order_id']:'',
            'country' => Config::get('cardinity.country'),
            'payment_method' => Create::CARD,
            'payment_instrument' => [
                'pan' => isset($data['pan'])?$data['pan']:'', //'4111111111111111',
                'exp_year' => isset($data['exp_year'])?(int)$data['exp_year']:0, //2016,
                'exp_month' => isset($data['exp_month'])?(int)$data['exp_month']:0, //12,
                'cvc' => isset($data['cvc'])?$data['cvc']:'', //456,
                'holder' => isset($data['holder'])?$data['holder']:'', //'Mike Dough'
            ],
        ]);

        try {
            $payment = $client->call($method);
        } catch (Declined $exception) {
            $payment = $exception->getResult();
        }

        return ([
            'payment_id' => $payment->getId(),
            'order_id' => isset($data['order_id'])?(string)$data['order_id']:'',
            'status' => Config::get('cardinity.statuses.'.$payment->getStatus()),
            'error' => $payment->getErrors()
        ]);

    }
}