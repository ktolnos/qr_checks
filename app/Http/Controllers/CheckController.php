<?php

namespace App\Http\Controllers;

use App\Check;
use App\Product;
use Illuminate\Http\Request;

class CheckController extends Controller
{


    public function show(){
        $checks = Check::orderBy('id', 'asc')->get();
        return view('checks/view', [
            'checks' => $checks
        ]);
    }

    public function add(){
        return view('checks/add');
    }

    public function confirm(Request $request){
        $username='+79787466941';
        $password='891075';
        $URL="https://proverkacheka.nalog.ru:9999/v1/inns/*/kkts/*/fss/".
            $request->fn.
            "/tickets/".
            $request->i.
            "?fiscalSign=".
            $request->fp.
            "&sendToEmail=no";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HEADER, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Device-Id: 12321312",
            "Device-OS: sded"
        ));

        $result=curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (strpos($result, "{\"document\":{\"") < 0){
            return redirect('/')
                ->withInput()
                ->withErrors('Invalid paycheck returned');
        }
        $result = substr($result, strpos($result, "{\"document\":{\"")) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_close ($ch);

        $receipt = json_decode($result, false)->document->receipt;
        session(['receipt' => $receipt]);
        return view('checks/confirm', [
            'receipt' => $receipt
        ]);
    }

    public function store(){
        if(!session()->has('receipt'))
            return redirect('/')
                ->withInput()
                ->withErrors('No paycheck');

        $receipt = session()->get('receipt');
        $check = new Check();
        $check->fiscalSign = $receipt->fiscalSign;
        $check->fiscalDocumentNumber = $receipt->fiscalDocumentNumber;
        $check->fiscalDriveNumber = $receipt->fiscalDriveNumber;
        $check->storeName = $receipt->userInn;
        $check->initialTotalSum = bcdiv($receipt->totalSum, 100, 2);
        $check->initialDate = $receipt->dateTime;
        $check->save();
        foreach($receipt->items as $item){
            $product = new Product();
            $product->checkId = $check->id;
            $product->name = $item->name;
            $product->quantity = $item->quantity;
            $product->price = bcdiv($item->price, 100, 2);
            $product->sum = bcdiv($item->sum, 100, 2);
            $product->save();
        }
        $messages = array('Check is saved successfully');
        session('messages', $messages);
        return redirect('checks/');

    }
}
