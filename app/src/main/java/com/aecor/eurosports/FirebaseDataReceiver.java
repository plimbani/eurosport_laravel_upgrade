package com.aecor.eurosports;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import androidx.legacy.content.WakefulBroadcastReceiver;

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
            try {
                Bundle bundle = intent.getExtras();
                if (bundle != null) {
                    for (String key : bundle.keySet()) {
                        Object value = bundle.get(key);
                        Log.d(TAG, String.format("%s %s (%s)", key,
                                value.toString(), value.getClass().getName()));
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            String messageContent = (String) intent.getExtras().get("gcm.notification.body");
            String messageTitle = (String) intent.getExtras().get("gcm.notification.title");
            Intent mNewMessagePopupIntent = new Intent(context, NewMessagePopupActivity.class);
            mNewMessagePopupIntent.putExtra(AppConstants.ARG_NEW_MESSAGE, messageContent);
            mNewMessagePopupIntent.putExtra(AppConstants.ARG_NEW_MESSAGE_TITLE, messageTitle);
            mNewMessagePopupIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(mNewMessagePopupIntent);
        }

    }


}