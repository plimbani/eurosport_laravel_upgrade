package com.aecor.eurosports;

import android.content.Context;
import android.util.Log;

import androidx.annotation.NonNull;

import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Map;

public class MyFirebaseMessagingService extends FirebaseMessagingService {

    private final String TAG = MyFirebaseMessagingService.class.getSimpleName();
    private Context mContext;
    private AppPreference appPreference;

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

    @Override
    public void onNewToken(@NonNull String s) {
        AppLogger.LogE(TAG, "New Device fcm Token " + s);
        if (!Utility.isNullOrEmpty(s)) {
            AppPreference.initializeInstance(mContext);
            appPreference = AppPreference.getInstance(mContext);

            if (Utility.isInternetAvailable(this)) {
                postTokenOnServer(s);
            }
        }
    }

    private void postTokenOnServer(String mFcmToken) {
        appPreference.setString(AppConstants.FIREBASE_TOKEN, mFcmToken);
        String email = appPreference.getString(AppConstants.PREF_EMAIL);
        if (!Utility.isNullOrEmpty(email)) {
            String url = ApiConstants.POST_FCM_TOKEN;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("email", email);
                requestJson.put("fcm_id", mFcmToken);
            } catch (JSONException e) {
                e.printStackTrace();
            }

            if (Utility.isInternetAvailable(mContext)) {
                AppLogger.LogE(TAG, "***** Post FCM Token request *****" + requestJson.toString());
                final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
                final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                        .POST, url,
                        requestJson, new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            AppLogger.LogE(TAG, "***** Post FCM Token response *****" + response.toString());
                            appPreference.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER, "true");
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        try {
                            AppLogger.LogE(TAG, "error" + error);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                });
                mQueue.add(jsonRequest);
            }
        } else {
            appPreference.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER, "false");
        }
    }
}
