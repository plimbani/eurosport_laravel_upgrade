package com.aecor.eurosports;

import android.content.Context;
import android.util.Log;

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
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by karan on 7/3/2017.
 */

public class MyFirebaseInstanceIdService extends FirebaseInstanceIdService {

    private final String TAG = "FirebaseIIDService";
    private AppPreference appPreference;
    private Context mContext;
    private String refreshedToken;

    @Override
    public void onTokenRefresh() {
        mContext = this;
        AppPreference.initializeInstance(mContext);
        appPreference = AppPreference.getInstance(mContext);

        refreshedToken = FirebaseInstanceId.getInstance().getToken();
        Log.d(TAG, "*** Refreshed token *** -> " + refreshedToken);
        if (Utility.isInternetAvailable(this)) {
            postTokenOnServer(refreshedToken);
        }
    }

    private void postTokenOnServer(String mFcmToken) {
        appPreference.setString(AppConstants.FIREBASE_TOKEN, refreshedToken);
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
                            appPreference.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER ,"true");
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
        }else{
            appPreference.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER ,"false");
        }
    }
}
