package com.aecor.eurosports;

import com.google.firebase.messaging.FirebaseMessagingService;

/**
 * Created by asoni on 01-06-2016.
 */

public class MyFirebaseMessagingService extends FirebaseMessagingService {

    /*
public static final String ACTION_NEW_MESSAGE_RECEIVE = "com.lanesgroup.pnsmessenger.ACTION_NEW_MESSAGE_RECEIVE";
    private static final String TAG = "MyFirebaseMsgService";
    private final static AtomicInteger c = new AtomicInteger(0);
    private Context mContext;
    private AppPreference mAppSharedPref;

    public static int getID() {
//        return c.incrementAndGet();
        return 777;
    }

    // This function will create an intent. This intent must take as parameter the "unique_name" that you registered your activity with
    private void updateHomeActivity() {

        Intent intent = new Intent(ACTION_NEW_MESSAGE_RECEIVE);

        //put whatever data you want to send, if any
        intent.putExtra(AppConstants.KEY_INTENT_BROADCAST_NEW_MESSAGE
                , "new_message_received");

        //send broadcast
        mContext.sendBroadcast(intent);
    }

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);
        mContext = this;
        //Displaying data in log
        //It is optional
        AppPreference.initializeInstance(mContext);
        mAppSharedPref = AppPreference.getInstance();

        Log.d(TAG, "From: " + remoteMessage.getFrom());
        mContext = this;
        Log.d(TAG, "Notification Message Body: " + remoteMessage.getData().get("data"));
        String messageId = "";
        try {
            JSONObject mNotificationJson = new JSONObject(remoteMessage.getData().get("data"));
            if (mNotificationJson.has("message_id")) {
                if (mNotificationJson.getString("message_id") != null && !mNotificationJson.getString("message_id").equals("")) {
                    messageId = mNotificationJson.getString("message_id");
                }
            }
            if (!Utility.isNullOrEmpty(messageId) *//*
*/
/*&& !MessagesTable.checkIfMessageAlreadyExistInDb(this, messageId)*//*
*/
/* ) {
                sendAcknowledgeToServer(messageId);
                insertMessageIntoDb(messageId);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    //This method is only generating push notification
    //It is same as we did in earlier posts
    private void sendNotification(MessageModel mMessageData) {
        Utility.updateLauncherCout(MessagesTable.getUnreadMessageCount(this), this);
        updateHomeActivity();
        Intent singleMessageintent;
        if (mMessageData.getType().equalsIgnoreCase(AppConstants.QUIZ_TYPE_STANDARD)) {
            singleMessageintent = new Intent(this, MessageDetailsActivity.class);
        } else {
            singleMessageintent = new Intent(this, QuizActivity.class);
        }
        singleMessageintent.putExtra(AppConstants.KEY_FROM_NOTIFICATION, true);
        singleMessageintent.putExtra(AppConstants.KEY_SELECTED_MESSAGE_DETAIL, mMessageData.getId());

        singleMessageintent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        PendingIntent pendingIntentForSingleMessage = PendingIntent.getActivity(this, 0, singleMessageintent,
                PendingIntent.FLAG_ONE_SHOT);

        Intent multiMessageintent = new Intent(this, HomeActivity.class);
        multiMessageintent.putExtra(AppConstants.KEY_FROM_NOTIFICATION, true);
        multiMessageintent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

        PendingIntent pendingIntentForMultipleMessage = PendingIntent.getActivity(this, 0, multiMessageintent,
                PendingIntent.FLAG_ONE_SHOT);
        Bitmap bitmap = Utility.drawTextToBitmap(MyFirebaseMessagingService.this, R.mipmap.ic_launcher, "");

        String departmentName = this.getResources().getString(R.string.department_not_available);
        if (!Utility.isNullOrEmpty(mMessageData.getDepartment())) {
            departmentName = mMessageData.getDepartment();
        }

        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this)
                .setSmallIcon(R.mipmap.ic_launcher)
                .setAutoCancel(true);

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            notificationBuilder.setColor(mContext.getResources().getColor(R.color.notification_color));
        }

        AppConstants.NOTIFICATION_COUNT = AppConstants.NOTIFICATION_COUNT + 1;
        if (AppConstants.NOTIFICATION_COUNT > 1) {
            notificationBuilder.setContentIntent(pendingIntentForMultipleMessage);
            notificationBuilder.setContentTitle(mContext.getResources().getString(R.string.lanes_grp_messenger));
            String contentText = AppConstants.NOTIFICATION_COUNT + " new messages";
            notificationBuilder.setContentText(contentText);
            notificationBuilder.setLargeIcon(bitmap);
        } else {
            notificationBuilder.setContentIntent(pendingIntentForSingleMessage);
            notificationBuilder.setContentTitle(departmentName);
            notificationBuilder.setContentText(mMessageData.getContent());
            bitmap = Utility.getDepartmentBitmap(mContext, departmentName);
            notificationBuilder.setLargeIcon(bitmap);
        }
        try {
            notificationBuilder.setVisibility(Notification.VISIBILITY_PUBLIC);
        } catch (Exception e) {
            e.printStackTrace();
        }

        notificationBuilder.setDeleteIntent(getDeleteIntent());
        NotificationManager notificationManager =
                (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        String sound = "true";
        if (!Utility.isNullOrEmpty(mAppSharedPref.getString(AppPreference.KEY_SETTING_IS_SOUND_ENABLE))) {
            sound = mAppSharedPref.getString(AppPreference.KEY_SETTING_IS_SOUND_ENABLE);
        }
        String vibrate = "true";
        if (!Utility.isNullOrEmpty(mAppSharedPref.getString(AppPreference.KEY_SETTING_IS_VIBRATION_ENABLE))) {
            vibrate = mAppSharedPref.getString(AppPreference.KEY_SETTING_IS_VIBRATION_ENABLE);
        }

        if (sound.equalsIgnoreCase("true")) {
//            notif.defaults |= Notification.DEFAULT_SOUND;
            notificationBuilder.setSound(defaultSoundUri);
        }

        if (vibrate.equalsIgnoreCase("true")) {
//            notif.defaults |= Notification.DEFAULT_VIBRATE;
            notificationBuilder.setVibrate(new long[]{1000, 1000});
        }
        Notification notif = notificationBuilder.build();

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            int smallIconViewId = getResources().getIdentifier("right_icon", "id", android.R.class.getPackage().getName());

            if (smallIconViewId != 0) {
                if (notif.contentIntent != null)
                    notif.contentView.setViewVisibility(smallIconViewId, View.INVISIBLE);

                if (notif.headsUpContentView != null)
                    notif.headsUpContentView.setViewVisibility(smallIconViewId, View.INVISIBLE);

                if (notif.bigContentView != null)
                    notif.bigContentView.setViewVisibility(smallIconViewId, View.INVISIBLE);
            }
        }

        notificationManager.notify(getID(), notif);
    }

    private void insertMessageIntoDb(String messageId) {
        String url = ApiConstants.URL_GET_MESSAGE_DETAILS;
        JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("id", messageId);
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue
                    = VolleyRequestQueue.getInstance(this.getApplicationContext())
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    try {
                        AppLogger.LogD(TAG, "Get Message Details Response" + response.toString());
                        if (response != null && !response.equals("")) {
                            MessageModel mMessage = GsonConverter
                                    .getInstance().decodeFromJsonString(response.toString(),
                                            MessageModel.class);
                            mMessage.setStatus(AppConstants.MESSAGE_STATUS_DELIVERED);
                            new MessagesTable().insert(MyFirebaseMessagingService.this, mMessage);

                            NotificationTable.insert(mContext, mMessage.getId() + "");

                            Intent newPushDialogActivity = new Intent(mContext,
                                    NewPushMessageDialog.class);
                            newPushDialogActivity
                                    .setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                            newPushDialogActivity.putExtra("msg_id", mMessage.getId() + "");
                            startActivity(newPushDialogActivity);

                            sendNotification(mMessage);
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    AppLogger.LogE(TAG, "Error " + error.getMessage());
                }
            });
            jsonRequest.setShouldCache(false);
            mQueue.add(jsonRequest);
        } else {
            BackLogRequestModel backLogRequestModel = new BackLogRequestModel();
            backLogRequestModel.setRequestApi(url);
            backLogRequestModel.setRequestJson(requestJson.toString());
            backLogRequestModel.setEntity("message");
            backLogRequestModel.setAction("text");
            BacklogTable.insert(mContext, backLogRequestModel);
        }
    }

    private void sendAcknowledgeToServer(String messageId) {
        String url = ApiConstants.URL_ACKNOWLEDGE;
        JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("email", Utility.getMailId(this));
            requestJson.put("message_id", messageId);
            requestJson.put("status", AppConstants.MESSAGE_STATUS_DELIVERED);
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue
                    = VolleyRequestQueue.getInstance(this.getApplicationContext())
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    try {
                        AppLogger.LogD(TAG, "response" + response.toString());
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {

                }
            });
            jsonRequest.setTag("ACKNOWLEDGE_REQUEST_TAG");
            jsonRequest.setShouldCache(false);
            mQueue.add(jsonRequest);
        } else {
            BackLogRequestModel backLogRequestModel = new BackLogRequestModel();
            backLogRequestModel.setRequestApi(url);
            backLogRequestModel.setRequestJson(requestJson.toString());
            backLogRequestModel.setEntity("push_message");
            backLogRequestModel.setAction("acknowledge");
            BacklogTable.insert(mContext, backLogRequestModel);
        }
    }

    @Override
    public void onMessageSent(String s) {
        super.onMessageSent(s);
        AppLogger.LogE(TAG, "Send Message Response " + s);
    }

    protected PendingIntent getDeleteIntent() {
        Intent intent = new Intent(mContext, NotificationDeleteBroadcastReceiver.class);
        intent.setAction("notification_cancelled");
        return PendingIntent.getBroadcast(mContext, 0, intent, PendingIntent.FLAG_CANCEL_CURRENT);
    }


    @Override
    public void onSendError(String s, Exception e) {
        super.onSendError(s, e);
        AppLogger.LogE(TAG, "Send Message Error " + s);
    }

*/

}

