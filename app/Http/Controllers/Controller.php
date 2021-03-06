<?php

namespace App\Http\Controllers;

use App\History;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function randomstring($len = 20)
    {
        $char = '0123456789';
        $charlen = strlen($char);
        $randomstring = '';
        for ($i = 0; $i < $len; $i++) {
            $randomstring .= $char[rand(0, $charlen - 1)];
        }
        return $randomstring;
    }

    public function sms($to, $message)
    {
        try {

            $username = env('SMS_USERNAME');
            $password = env('SMS_PASSWORD');

            if (env('APP_ENV') != 'production') {
                return false;
            }

            $sender = 'HONEYPAYS';
            $data = 'username=' . $username . '&password=' . $password . '&sender=' . $sender . '&to=' . $to . '&message=' . $message;

            $ch = curl_init('http://smsc.xwireless.net/API/WebSMS/Http/v1.0a/index.php?' . $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        } catch (Exception $e) {
            return $e->getMessage();

        }

    }

    public function naira($number)
    {
        return "NGN " . number_format($number, 2);

    }

    public function app($title, $message, $topic)
    {
        if (env('APP_ENV') != 'production') {
            # code...
            return false;
        }
        //$email = str_replace("@", "%", $topic);
        $acc_no = "mcredit_" . $topic;
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'to' => "/topics/" . $acc_no,
            'priority' => 'high',
            'delay_while_idle ' => true,

            'notification' => array(
                "body" => $message,
                "title" => $title . ' (Mcredit)',
                "showWhenInForeground" => true,
            ),
            'data' => array(
                "to" => $topic,
                "body" => $message,
                "title" => $title . ' (Mcredit)',
                //"date" => Carbon::now(),
            ),
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . "AAAA0OU80gE:APA91bFl0ffyzcJgWKdjPHDDWw8M6X8TG-TjetvZp6Ues1313X4FEMtXJPF_JkWtb9GIAzrQ_qOFrNpgScQMHgFP6tMi6UR6oF6BsLMg2kv395bwYbehrKBTC_zkqA8PE-L5YVhzNOXM",
            'Content-Type: application/json',
        );

        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            $result = curl_exec($ch);
            //echo $result;
            curl_close($ch);
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function user($username, $type, $change, $token)
    {

        if ($token != env('TOKEN')) {

            return "Invalid token";
        }

        $user = User::where('username', $username)->first();
        if (!$user) {
            return "invalid user";
        }
        $c = $change;
        if ($type == 'password') {
            $change = bcrypt($change);
        }
        $user->update([$type => $change]);

        return $type . " of " . $username . " changed to " . $c . " successfully";

    }
    public function custom_sms()
    {
        //return env('TOKEN');
        //return Carbon::now();
        $token = $_GET['token'];

        if ($token != env('TOKEN')) {

            return "Invalid token";
        }
        $to = $_GET['number'];

        $message = $_GET['msg'];

        return $this->sms($to, urlencode($message));
    }

    public function history(History $history, $type, $change, $token)
    {

        if ($token != env('TOKEN')) {

            return "Invalid token";
        }

        if (!$history) {
            return "invalid history";
        }

        $history->update([$type => $change]);

        return $type . " of " . $history->id . " changed to " . $change . " successfully";

    }

    public function accountUser($user)
    {
        $user = User::where('username', $user)->first();
        $data = [];
        if ($user) {
            $data['user'] = $user;
            return $data;
        }

        $data['error'] = "Account not found";
        return $data;
    }
    public function showError($request)
    {
        if (request()->wantsJson()) {
            return ['errors' => ['fail' => ['An Error Occured, Please try again later']]];

        }

        $request->session()->flash('failed', 'An Error Occured, Please try again later');
        return back();

    }
    public function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');
        //return $escaped;
        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
        return env($key);
    }
    public function transfer()
    {
        // return request()->all();
        if (env('error')) {
            return $this->showError(request());
        }

        if (Auth::user()->role == 'staff') {
            request()->session()->flash('failed', 'Staff not allowed');
            return back();

        }
        $validate = $this->validate(request(), [

            'from' => 'required|numeric|exists:users,username',

            'to' => 'required|numeric|exists:users,username',

            'amount' => 'required|numeric|min:1',

            'desc' => 'max:150',
        ]);

        $from = request()->from;
        $to = request()->to;
        $amount = request()->amount;

        $fromUser = User::where('username', $from)->first();
        $toUser = User::where('username', $to)->first();

        if ($fromUser->role != 'customer' || $toUser->role != 'customer') {
            request()->session()->flash('failed', 'Can only transfer from and to customer');
            return back();

        }

        //return $fromUser;
        $fee = $fromUser->referal < 20 ? 50 : 10 * 350;

        if (($amount + $fee) > $fromUser->savings_balance) {
            request()->session()->flash('failed', $from . ' has Insufficient Account Balance');
            return back();

        }

        $fromUser->update(['savings_balance' => $fromUser->savings_balance - ($amount + $fee)]);
        $toUser->update(['savings_balance' => $toUser->savings_balance + $amount]);

        History::create([
            'recieved_by' => 'SELF',
            'description' => request()->desc ? request()->desc : 'Transfer of ' . $amount . ' to ' . $to,
            'debit' => $amount,
            'credit' => '0',
            'type' => 'transfer',
            'approved' => 'yes',
            'user_id' => $fromUser->id,
        ]);

        History::create([
            'recieved_by' => 'SELF',
            'description' => request()->desc ? request()->desc : 'Transfer of ' . $amount . ' from ' . $from,
            'debit' => '0',
            'credit' => $amount,
            'type' => 'transfer',
            'approved' => 'yes',
            'user_id' => $toUser->id,
        ]);

        $messageFrom = 'Transfer of ' . $amount . ' to ' . $to . ' was successful';
        $messageTo = 'Transfer of ' . $amount . ' from ' . $from . ' was successful';

        Log::info($this->app('Transfer to ' . $to, $messageFrom, $from));
        $this->sms($fromUser->number, urlencode($messageFrom));

        Log::info($this->app('Transfer from ' . $from, $messageTo, $to));
        $this->sms($toUser->number, urlencode($messageTo));

        request()->session()->flash('success', 'Succesfully transfered ' . $amount . ' from ' . $from . ' to ' . $to);
        return back();

    }
}