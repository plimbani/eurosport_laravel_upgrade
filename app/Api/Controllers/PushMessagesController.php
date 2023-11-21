<?php

namespace Laraspace\Api\Controllers;

use Carbon\Carbon;
/*use App\Models\User;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageReceiptRequest;*/
use Config;
use DB;
use FCM;
use Illuminate\Http\Request;
use Laraspace\Events\AppMessageSent;
use Laraspace\Http\Requests\PushMessage\GetMessagesRequest;
use Laraspace\Http\Requests\PushMessage\SendNotificationRequest;
use Laraspace\Models\Message;
use Laraspace\Models\Tournament;
use Laraspace\Models\User;
use Laraspace\Models\Website;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PushMessagesController extends BaseController
{
    /**
     * Acknowledge the receipt of the push message.
     *
     * This endpoint will be called whenever a push message is
     * delivered to the client. Update the status to 'Delivered'
     * and return the corresponding GCM message id back to the app.
     *
     * @param $id
     * @return mixed
     */
    public function postAcknowledgePushReceipt(MessageReceiptRequest $request)
    {
        \Log::info('received acknowledge request for message id ');
        \Log::info($request->all());
        $message_id = $request->message_id;
        $email = $request->email;
        $status = $request->status;
        $user = User::where(['email' => $email])->first();
        if ($user) {
            $message_rec = MessageRecipient::where(['user_id' => $user->id, 'message_id' => $message_id])->first();
            $message_rec->status = $status;
            if ($message_rec->save()) {
                \Log::info('Saved the data');

                return response()->json(['status' => 'success']);
            }
        }

        return $this->response->error('Error saving response.');

    }

    public function postIncomingMessage(Request $request)
    {
        \Log::info($request->all());

        return $this->response->array([]);
    }

    private function sendToFCM($title, $content, $token, $sound = 'default')
    {

        try {
            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);
            $optionBuiler->setPriority('high');
            $optionBuiler->setContentAvailable(true);

            // $isSound = $sound == 'true'? 'default' : '';
            // $isSound = $sound;

            $notificationBuilder = new PayloadNotificationBuilder($content);
            $notificationBuilder->setBody($content)
                ->setTitle($title)
                ->setSound($sound);

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['test_data' => $content]);

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            // $token = "a_registration_from_your_database";

            /* $token = "f7ExTxZrh4o:APA91bHxEdDIm-ezv3_wl4qE0TKygzUZBJeC1HZvia-vdZ4-THffbx6PGGANrE_XaRE9TCeuPRO61wRGkJcS2tFNVPe7xL7Yvtdw3IxStgm2Gx4Ehmuvu5sGrOfiyZzl_rTJkciCU4jM"; */
            // $token = "etZg2KA4O4M:APA91bHPFpP8wJirQrhl1aEktjwsgqBP9Fp_hkArKqZxsls-WwboMSAisEQ64NQDglig9oSMLYWF2M4GriJN6CGShmB96h5gf6MNL-Xqep-6QMlUZD8vO4gvwwUsZG_-DhdpaDCXVhik";
            \Log::info($data);
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

            // echo "<pre>";print_r($downstreamResponse);echo "</pre>";exit;

            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();

            //return Array - you must remove all this tokens in your database
            $downstreamResponse->tokensToDelete();

            //return Array (key : oldToken, value : new token - you must change the token in your database )
            $downstreamResponse->tokensToModify();

            //return Array - you should try to resend the message to the tokens in the array
            $downstreamResponse->tokensToRetry();

            return $downstreamResponse;
        } catch (\Exception $e) {
            \Log::info('exception=========');
            \Log::info($e->message());

            return false;
        }

        // return Array (key:token, value:errror) - in production you should remove from your database the tokens
    }

    // Insert Value in MessagesTable
    private function insertInMessageHistory($data)
    {
        $message = null;
        if (isset($data['message_id']) && ($data['message_id'] != '' || $data['message_id'] != null)) {

            // here we add one code for Delete
            if (isset($data['is_delete']) && ($data['is_delete'] != '' || $data['is_delete'] != null)) {
                // remove from child table
                DB::table('message_recipients')->where('message_id', $data['message_id'])->delete();
                // remove from parent Table
                Message::where('id', '=', $data['message_id'])->delete();

                return true;
            }
            $data['message_id'] = $data['message_id'];
            $message = Message::find($data['message_id']);
            $message->status = $data['status'];
            $message->content = $data['content'];
            $message->save();
            $messageId = $data['message_id'];
            // Delete all data for message receipts for that message id
            DB::table('message_recipients')->where('message_id', $data['message_id'])->delete();
        } else {
            $message = new Message();
            $message->sent_from = $data['sent_from'];
            $message->status = $data['status'];
            $message->content = $data['content'];
            $message->tournament_id = $data['tournament_id'];
            $message->save();

            $messageId = $message->id;
        }

        // Now store values for multiple receipient
        if ($data['user_id']) {
            $msg_receiptArray = [];
            foreach ($data['user_id'] as $key => $user) {
                $msg_receiptArray[$key] = ['message_id' => $messageId, 'user_id' => $user, 'error_json' => '{}'];
            }
            // Now Insert in DB
            DB::table('message_recipients')->insert($msg_receiptArray);
        }

        return $message;
    }

    public function sendNotification(SendNotificationRequest $request)
    {
        \Log::info($request->all());
        $data = $request->all();
        $data = $data['messageData'];

        // Here we put code for Delete
        if (isset($data['is_delete']) && ($data['is_delete'] != null || $data['is_delete'] != '')) {
            $this->insertInMessageHistory($data);

            return $this->response->array([
                'data' => 'Deleted',
                'message' => 'success',
                'status_code' => 200,
            ]);
        }
        $userId = $data['user_id'];
        $tournamentId = $data['tournament_id'];
        $tournament = Tournament::find($tournamentId);
        $content = $data['contents'];
        $type = $data['type'];

        $users = \DB::table('users_favourite')->where('tournament_id', '=', $tournamentId)->where('deleted_at', '=', null)->pluck('user_id')->toArray();

        if (is_array($users) && count($users) == 0) {
            return $this->response->array([
                'data' => 'No App users exist',
                'message' => 'failure',
                'status_code' => 200,
            ]);
        }
        if ($type == 'save') {
            $downstreamResponse1 = '';
            $downstreamResponse2 = '';
            // dd($users);
            $userDataSoundOff = User::join('settings', 'users.id', '=', 'settings.user_id')->whereIn('users.id', $users)->where('settings.value->is_sound', 'false')->pluck('users.fcm_id')->toArray();
            $tokenSoundOff = $userDataSoundOff;
            // dd($tokenSoundOff);
            if (! empty($tokenSoundOff)) {
                $downstreamResponse1 = $this->sendToFCM($tournament->name, $content, $tokenSoundOff, '');
            }
            $userDataSoundOn = User::join('settings', 'users.id', '=', 'settings.user_id')->whereIn('users.id', $users)->where('settings.value->is_sound', 'true')->pluck('users.fcm_id')->toArray();
            $tokenSoundOn = $userDataSoundOn;
            // dd($tokenSoundOn);
            if (! empty($tokenSoundOn)) {
                $downstreamResponse2 = $this->sendToFCM($tournament->name, $content, $tokenSoundOn, 'default');
            }

            $data['sent_from'] = $userId;
            $data['user_id'] = $users;
            $data['content'] = $content;
            $data['status'] = 'sent';
            $data['tournament_id'] = $tournamentId;
        } else {
            // Means Have to Draft
            $data['sent_from'] = $userId;
            $data['user_id'] = $users;
            $data['content'] = $content;
            $data['status'] = 'queued';
            $data['tournament_id'] = $tournamentId;
            $downstreamResponse1 = '';
            $downstreamResponse2 = '';
        }

        // Now insert in DB
        if ($downstreamResponse1 === false || $downstreamResponse2 === false) {
            return $this->response->array([
                'data' => 'Problem on send notification',
                'message' => 'failure',
                'status_code' => 200,
            ]);
        }
        $message = $this->insertInMessageHistory($data);

        // Broadcast message to make message appear for users on tournament websites
        // event(new AppMessageSent($message));

        return $this->response->array([
            'data' => $downstreamResponse1,
            'message' => 'success',
            'status_code' => 200,
        ]);
    }

    public function getMessages(GetMessagesRequest $request)
    {
        $messageData = $request->all();
        $tournamentId = $messageData['messageData']['tournament_id'];
        $messageData = Message::where('tournament_id', $tournamentId)->With(['sender', 'receiver', 'tournament'])->get()->toArray();

        return $this->response->array([
            'data' => $messageData,
            'message' => 'success',
            'status_code' => 200,
        ]);

    }

    /**
     * Get tournament messages for websites
     *
     * Get a JSON representation of tournament messages.
     *
     * @Get("/getWebsiteMessages")
     *
     * @Versions({"v1"})
     *
     * @Response(200, body={})
     */
    public function getWebsiteMessages(Request $request, $websiteId)
    {
        $website = Website::find($websiteId);
        $days = Config::get('wot.message_notification_days');
        $createdAfter = Carbon::today()->subDay($days);
        $messages = $website->messages()->whereDate('created_at', '>=', $createdAfter)->orderBy('created_at', 'desc')->get();

        return ['messages' => $messages->toArray()];
    }
}
