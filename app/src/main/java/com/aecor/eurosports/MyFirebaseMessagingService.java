package com.aecor.eurosports;

import android.content.Context;

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
    }

    @Override
    public void onMessageSent(String s) {
        super.onMessageSent(s);
        AppLogger.LogE(TAG, "Send Message Response " + s);
    }

    @Override
    public void onSendError(String s, Exception e) {
        super.onSendError(s, e);
        AppLogger.LogE(TAG, "Send Message Error " + s);
    }

}
