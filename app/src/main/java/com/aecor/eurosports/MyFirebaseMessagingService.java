package com.aecor.eurosports;

import android.content.Context;
import android.content.Intent;

import com.aecor.eurosports.activity.NewMessagePopupActivity;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

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
        Intent mNewMessagePopupIntent = new Intent(mContext, NewMessagePopupActivity.class);
        mNewMessagePopupIntent.putExtra(AppConstants.ARG_NEW_MESSAGE, remoteMessage.getFrom());
        mNewMessagePopupIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mNewMessagePopupIntent);
    }


    @Override
    public void onSendError(String s, Exception e) {
        super.onSendError(s, e);
        AppLogger.LogE(TAG, "Send Message Error " + s);
    }

}
