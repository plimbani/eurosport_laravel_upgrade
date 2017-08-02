package com.aecor.eurosports;

import android.content.Context;
import android.util.Log;

import com.aecor.eurosports.util.AppLogger;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import org.json.JSONObject;

import java.util.Map;

public class MyFirebaseMessagingService extends FirebaseMessagingService {

    private final String TAG = MyFirebaseMessagingService.class.getSimpleName();
    private Context mContext;

    public MyFirebaseMessagingService() {
    }

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);
        mContext = this;
        AppLogger.LogD(TAG, "From: " + remoteMessage.getFrom());
        Log.e("dataChat", remoteMessage.getData().toString());
        try {
            Map<String, String> params = remoteMessage.getData();
            JSONObject object = new JSONObject(params);
            Log.e("JSON_OBJECT", object.toString());
//            Intent mNewMessagePopupIntent = new Intent(mContext, NewMessagePopupActivity.class);
//            mNewMessagePopupIntent.putExtra(AppConstants.ARG_NEW_MESSAGE, params.toString());
//            mNewMessagePopupIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
//            startActivity(mNewMessagePopupIntent);
        } catch (Exception e) {
            e.printStackTrace();
        }

    }


    @Override
    public void onSendError(String s, Exception e) {
        super.onSendError(s, e);
        AppLogger.LogE(TAG, "Send Message Error " + s);
    }

}
