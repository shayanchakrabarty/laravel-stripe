<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;

class PaymentController extends Controller
{
    public function index() {
        return view('stripe');
    }

    public function payment(Request $request) {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Stripe\Customer::create(array(
            'name' => 'test',
            'description' => 'test description',
            'email' => 'codescapers@gmail.com',
            'source'  =>  $request->stripeToken,
            'address' => [
                'line1' => '510 Townsend St',
                'postal_code' => '98140',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'US',
            ],
        ));
        $orderID = strtoupper(str_replace('.','',uniqid('', true)));

        Stripe\Charge::create(array(
            'customer' => $customer->id,
            //'source' => 'rtest',
            'amount'   => 200 * 100,
            'currency' => "usd",
            'description' => "test1",
            'metadata' => array(
                'order_id' => $orderID
            )
        ));

        Session::flash('success', 'Payment successful!');

        return back();

    }
}
