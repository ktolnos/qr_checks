<?php

namespace App\Http\Controllers;

use App\Check;
use App\CheckView;
use App\Payment;
use App\PaymentView;
use App\User;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $payments = PaymentView::all();
        return view('payments/index', [
            'payments' => $payments,
        ]);
    }

    public function add($id, Request $request){
        $validator = null;
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'payer' => 'required',
            ]);

            if (!$validator->fails()) {
                $payment = new Payment;
                $payment->amount = $request->amount;
                $payment->payer_id = $request->payer;
                if($request->payee>=0)
                    $payment->payee_id = $request->payee;
                if($request->check>=0)
                    $payment->check_id = $request->check;
                $payment->save();

                return redirect('/payments');
            } else {
            }
        }
        $users = User::all();
        $checks = CheckView::all();
        $amount = 0;

        $check = CheckView::where('id', $id)->first();
        if($check)
            $amount = $check->actualTotalSum;

        return view('payments/add', [
            'users' => $users,
            'checks' => $checks,
            'check_id' => $id,
            'amount' => $amount
        ]) -> withErrors($validator);
    }

    public function addPayment(Request $request){
        return $this->add(-1, $request);
    }

    public function update(Request $request)
    {
        foreach($request->items as $item){
            echo $item['amount'].'-->'.is_numeric($item['amount']).'|'.$item['id'].'++';
            if(!is_numeric($item['amount']))
                continue;
            $payment = Payment::find($item['id']);
            $payment->amount = $item['amount'];
            $payment->save();
        }
        $keys = [];
        foreach($request->deletedItems as $item){
            array_push($keys, $item['id']);
        }
        Payment::destroy($keys);
    }
}
