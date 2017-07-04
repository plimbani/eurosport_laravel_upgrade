package com.aecor.eurosports;

import android.content.Context;
import android.util.Log;

import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleyRequestQueue;
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
    private RequestQueue mQueue;
    private String userid;
    private Context mContext;
    private String refreshedToken;

    @Override
    public void onTokenRefresh() {
        mContext = this;
        AppPreference.initializeInstance(mContext);
        appPreference = AppPreference.getInstance(mContext);
        Log.d(TAG, "onTokenRefresh" + "onTokenRefreshonTokenRefreshonTokenRefreshonTokenRefresh");

        //Getting registration token
        refreshedToken = FirebaseInstanceId.getInstance().getToken();
        Log.d(TAG, "*** Refreshed token *** -> " + refreshedToken);
        if (Utility.isInternetAvailable(this)) {
            appPreference.setString(AppConstants.FIREBASE_TOKEN, refreshedToken);
        }
    }

    /*private void sendTokenToServer(String registerationId) throws JSONException{
        String url = "";
        JSONObject requestJson = new JSONObject();
        requestJson.put("id",appPreference.getString(AppConstants.KEY_LOGGED_IN_USER_ID));
        requestJson.put("registration_id",registerationId);
        AppLogger.LogE(TAG, "FirebaseIIDS requestJson" + requestJson.toString());
        if (Utility.isInternetAvailable(MyFirebaseInstanceIdService.this)) {
            mQueue = VolleyRequestQueue.getInstance(this.getApplicationContext())
                    .getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    AppLogger.LogE("TAG", "FirebaseIIDS response " + response);
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {

                }
            });
            jsonRequest.setTag("REGISTER_REQUEST_TAG");
            jsonRequest.setShouldCache(false);

            mQueue.add(jsonRequest);
        }
    }

    private void makeStartupCall() throws JSONException {
        String url= ApiConstants.SIGN_IN;
        JSONObject requestJson = new JSONObject();
        requestJson.put("email","kdeopura@aecordigital.com");
        requestJson.put("password","password");

        if (Utility.isInternetAvailable(MyFirebaseInstanceIdService.this)) {
            mQueue = VolleyRequestQueue.getInstance(this.getApplicationContext())
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    handleStartupResponse(response.toString());
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {

                }
            });
            mQueue.add(jsonRequest);
        }
    }

    private void handleStartupResponse(String serverResponse) {
        try {
            JSONObject mServerResponse = new JSONObject(serverResponse.toString());
            if(mServerResponse != null && !mServerResponse.equals("")) {
                if(mServerResponse.has("userData")) {
                    JSONObject jsonUser = mServerResponse.getJSONObject("userData");
                    if(jsonUser != null && !jsonUser.equals("")) {
                        if(jsonUser.has("user_id")) {
                            AppLogger.LogE(TAG, "user id -> " + jsonUser.getString("user_id"));
                            userid = jsonUser.getString("user_id");
                            appPreference.setString(AppConstants.KEY_LOGGED_IN_USER_ID, jsonUser.getString("userid"));
                            if (Utility.isInternetAvailable(MyFirebaseInstanceIdService.this)) {
                                if (!Utility.isNullOrEmpty(refreshedToken)) {
                                    sendTokenToServer(refreshedToken);
                                    AppLogger.LogE(TAG,refreshedToken+"");
                                }
                            }
                            *//*try {

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }*//*
                        }

                        if (jsonUser.has("first_name") && !Utility.isNullOrEmpty(jsonUser.getString("first_name"))) {
                            appPreference.setString(AppConstants.KEY_LOGGED_IN_USER_FIRST_NAME, jsonUser.getString("first_name"));
                        }
                        if (jsonUser.has("sur_name") && !Utility.isNullOrEmpty(jsonUser.getString("sur_name"))) {
                            appPreference.setString(AppConstants.KEY_LOGGED_IN_USER_SUR_NAME, jsonUser.getString("first_name"));
                        }
                    }
                }
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

    }*/
}
