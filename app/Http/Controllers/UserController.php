<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Http\CurlClient;
use Twilio\Rest\Client;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['notifications' => function ($query) {
            $query->whereNull('read_at');
        }])->get();

        return view('users.index', compact('users'));
    }

    public function impersonateUser($id){
        $notifications = Notification::where('user_id', $id)
        ->whereNull('read_at')
        ->where('expiration', '>', Carbon::now()) 
        ->get();        
        return view('users.impersonate',compact('notifications'));
    }

    public function markAsRead( $id){
        try {
            Notification::where('id',$id)->update(['read_at'=>Carbon::now()]);
        } catch (\Exception $e) {
            Log::error("Exception in markAsRead".$e->getMessage());
        }
    }

    public function editUser($id){
        $user = User::where('id',$id)->first();
        return view('users.edit',compact('user'));
    }

    public function updateUser(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $user->notification_switch = ($request->has('notification_switch') && $request->input('notification_switch') == "on" ? true : false) ;
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone');
        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }



    private function sendVerificationCode($phoneNumber, $verificationCode)
    {
            $sid = env('TWILIO_ACCOUNT_SID'); 
            $token = env('TWILIO_ACCOUNT_TOKEN'); 
            $from = '+14242366227'; 
            try {

                $client = new Client($sid, $token);

                //need to do this for local wamp server ssl certificate issue
                //rmeobve on production
                $curlOptions = [
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false
                ];
                $client->setHttpClient(new CurlClient($curlOptions));


                $message = $client->messages->create('+917676797901', [
                    'from' => $from,
                    'body' => "Your verification code is: $verificationCode"
                ]);

                if ($message->sid) {
                    // Success
                    $statusCode = 200; 
                    $statusMessage = 'SMS Sent Successfully. Message SID: ' . $message->sid;
                } else {
                    // Failure
                    $statusCode = 400;
                    $statusMessage = 'Failed to send SMS. Message SID not available.';
                }

                return response()->json(['message' => $statusMessage], $statusCode);
            } catch (Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
    }



    public function verifyPhone(Request $request)
    {
        $phoneNumber = $request->input('phone');
        $verificationCode = 133221; // Can be generated randomly
        $response = $this->sendVerificationCode($phoneNumber, $verificationCode);
        return response()->json($response);
    }


    public function verifyPhoneCode(Request $request){
        $verificationCode = $request->input('verificationCode');
        if($verificationCode == 133221 ){
            return 200;
        }else{
            return 401; 
        }
    }
     
}


