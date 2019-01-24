<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function randomstring($len = 20){
	$char = '0123456789';
	$charlen = strlen($char);
	$randomstring = '';
	for ($i = 0; $i < $len ; $i++) {
		$randomstring .= $char[rand(0, $charlen-1)];
	}
	return $randomstring;
	}

	public function sms($to, $message){
        try {
            
        $username = env('SMS_USERNAME');
        $password = env('SMS_PASSWORD');
        $sender = 'HONEYPAYS';
        $data = 'username='.$username.'&password='.$password.'&sender='.$sender.'&to='.$to.'&message='.$message;

        $ch = curl_init('http://smsc.xwireless.net/API/WebSMS/Http/v1.0a/index.php?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function naira($number){
    return "₦". number_format($number, 2);

    }

    public function app($title, $message, $topic) {
    //$email = str_replace("@", "%", $topic);
    $acc_no = "mcredit_".$topic;
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'to' => "/topics/".$acc_no,
            'priority' => 'high',
            'delay_while_idle ' => true,

            'notification' => array (
                    "body" => $message,
                    "title"=> $title.' (Mcredit)',
                    "showWhenInForeground" => true
            ),
             'data' => array (
                    "to" => $topic,
                    "body" => $message,
                    "title"=> $title.' (Empower)',
                    //"date" => Carbon::now(),
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAA0OU80gE:APA91bFl0ffyzcJgWKdjPHDDWw8M6X8TG-TjetvZp6Ues1313X4FEMtXJPF_JkWtb9GIAzrQ_qOFrNpgScQMHgFP6tMi6UR6oF6BsLMg2kv395bwYbehrKBTC_zkqA8PE-L5YVhzNOXM",
            'Content-Type: application/json'
    );

    try {
        
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    //echo $result;
    curl_close ( $ch );
    return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
public function user($username, $type, $change){

    if ($token != env('TOKEN')) {
        
        return "Invalid token";
    }

    $user = User::where('username',$username)->first();
    $c = $change;
    if ($type=='password') {
        $change = bcrypt($change);
    }
    $user->update([$type => $change]);

    return $type." of ".$email." changed to ".$c." successfully";

}
}
