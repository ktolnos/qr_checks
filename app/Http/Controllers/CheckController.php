<?php

namespace App\Http\Controllers;

use App\Check;
use App\CheckView;
use App\Product;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $checks = CheckView::all();
        return view('checks/view', [
            'checks' => $checks
        ]);
    }

    public function show(Request $request){
        return redirect()->action('ProductController@index', ['checkids' => $request->id]);
    }

    public function add(){
        return view('checks/add');
    }

    public function confirm(Request $request){
        $username=$request->phone;
        $password=$request->password;
        $fn = $request->fn;
        $i = $request->i;
        $fp = $request->fp;
        if($request->qr){
            $r = [];
            parse_str($request->qr, $r);
            $fn = $r['fn'];
            $fp = $r['fp'];
            $i = $r['i'];
        }
        $URL="https://proverkacheka.nalog.ru:9999/v1/inns/*/kkts/*/fss/".
            $fn.
            "/tickets/".
            $i.
            "?fiscalSign=".
            $fp.
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

        curl_exec ($ch);
        $result=curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo '<pre style="display: none">';
        print_r($result);
        echo '</pre>';
        if (strpos($result, "{\"document\":{\"") < 0){
            return redirect('/checks/add')
                ->withInput()
                ->withErrors('Invalid paycheck returned');
        }
        $result = substr($result, strpos($result, "{\"document\":{\"")) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_close ($ch);
        $receipt = json_decode($result, false);
        if(!is_object($receipt) || !property_exists($receipt, 'document') ||
            !is_object($receipt->document) ||
            !property_exists($receipt->document, 'receipt')){
            echo '<pre>';
            print_r($receipt);
            die();
        }
        $receipt = $receipt->document->receipt;
        $check = Check::where('fiscalSign', $receipt->fiscalSign)
            ->where('fiscalDocumentNumber', $receipt->fiscalDocumentNumber)
            ->where('fiscalDriveNumber', $receipt->fiscalDriveNumber)
            ->first();
        session(['receipt' => $receipt]);
        return view('checks/confirm', [
            'receipt' => $receipt,
            'check' => $check
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
        $check->storeInn = $receipt->userInn;
        $check->initialTotalSum = $receipt->totalSum/100;
        $check->initialDate = $receipt->dateTime;
        $check->save();
        foreach($receipt->items as $item){
            $product = new Product();
            $product->check_id = $check->id;
            $product->name = $item->name;
            $product->quantity = $item->quantity;
            $product->price = $item->price/100;
            $product->sum = $item->sum/100;
            $product->save();
        }
        $messages = array('Check is saved successfully');
        session('messages', $messages);
        return redirect('checks/');
    }

    public function update(Request $request){
        $keys = [];
        foreach($request->deletedItems as $item){
            array_push($keys, $item['id']);
        }
        Check::destroy($keys);
    }
}
