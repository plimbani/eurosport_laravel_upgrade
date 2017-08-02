package com.aecor.eurosports;

import android.content.Context;
import android.content.Intent;
import android.support.v4.content.WakefulBroadcastReceiver;
import android.util.Log;

import com.aecor.eurosports.activity.NewMessagePopupActivity;
import com.aecor.eurosports.util.AppConstants;

/**
 * Created by system-local on 01-08-2017.
 */

public class FirebaseDataReceiver extends WakefulBroadcastReceiver {

    private final String TAG = "FirebaseDataReceiver";

    public void onReceive(Context context, Intent intent) {
        Log.d(TAG, "*********I'm in!!!******" + intent);
        Log.d(TAG, "intent extra data " + intent.getExtras());

        if (intent.getAction().equals("com.google.android.c2dm.intent.RECEIVE")) {
            String messageContent = (String) intent.getExtras().get("gcm.notification.title");
            Intent mNewMessagePopupIntent = new Intent(context, NewMessagePopupActivity.class);
            mNewMessagePopupIntent.putExtra(AppConstants.ARG_NEW_MESSAGE, messageContent);
            mNewMessagePopupIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(mNewMessagePopupIntent);
        }

    }


}