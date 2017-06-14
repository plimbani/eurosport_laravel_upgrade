package com.aecor.eurosports;

import com.google.firebase.iid.FirebaseInstanceIdService;

/**
 * Created by asoni on 01-06-2016.
 */

public class MyFirebaseInstanceIDService extends FirebaseInstanceIdService {

/*private static final String TAG = "MyFirebaseIIDService";
    private AppPreference mAppPreference;
    private RequestQueue mQueue;
    private String userId;
    private String refreshedToken;

    @Override
    public void onTokenRefresh() {
        AppPreference.initializeInstance(this);
        mAppPreference = AppPreference.getInstance();
        Log.d(TAG, "onTokenRefresh" + "onTokenRefreshonTokenRefreshonTokenRefreshonTokenRefresh");

        //Getting registration token
        refreshedToken = FirebaseInstanceId.getInstance().getToken();

        //Displaying token on logcat
        Log.d(TAG, "Refreshed token: " + refreshedToken);
        try {
            if (Utility.isInternetAvailable(this)) {
                makeStartupCall();
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

    }


    private void sendTokenToServer(String registrationId) throws JSONException {
        String url = ApiConstants.URL_REGISTER;
        JSONObject requestJson = new JSONObject();
        requestJson.put("id", mAppPreference.getString(AppConstants.KEY_LOGGED_IN_USER_ID));
        requestJson.put("registration_id", registrationId);
        AppLogger.LogE(TAG, "requestJson" + requestJson.toString());
        if (Utility.isInternetAvailable(MyFirebaseInstanceIDService.this)) {
            mQueue = VolleyRequestQueue.getInstance(this.getApplicationContext())
                    .getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    AppLogger.LogE("TAG", "response " + response);
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {

                }
            });
            jsonRequest.setTag("REGISTER_REQUEST_TAG");
            jsonRequest.setShouldCache(false);

            mQueue.add(jsonRequest);
        } else {
            BackLogRequestModel backLogRequestModel = new BackLogRequestModel();
            backLogRequestModel.setRequestApi(url);
            backLogRequestModel.setRequestJson(requestJson.toString());
            backLogRequestModel.setEntity("notification");
            backLogRequestModel.setAction("register");
            BacklogTable.insert(MyFirebaseInstanceIDService.this, backLogRequestModel);
        }

    }

    private void makeStartupCall() throws JSONException {
        String url = ApiConstants.URL_STARTUP;
        JSONObject requestJson = new JSONObject();
        requestJson.put("email", Utility.getMailId(this)); // phone
//        requestJson.put("email", "chris.gartside@lanesgroup.com"); // phone

        if (Utility.isInternetAvailable(MyFirebaseInstanceIDService.this)) {
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
            jsonRequest.setTag("STARTUP_REQUEST_TAG");
            jsonRequest.setShouldCache(false);

            mQueue.add(jsonRequest);
        } else {
            BackLogRequestModel backLogRequestModel = new BackLogRequestModel();
            backLogRequestModel.setRequestApi(url);
            backLogRequestModel.setRequestJson(requestJson.toString());
            backLogRequestModel.setEntity("startup");
            backLogRequestModel.setAction("");
            BacklogTable.insert(MyFirebaseInstanceIDService.this, backLogRequestModel);
        }
    }

    private void handleStartupResponse(String serverResponse) {
        try {

            JSONObject mServerResponse = new JSONObject(serverResponse.toString());
            if (mServerResponse != null && !mServerResponse.equals("")) {
                if (mServerResponse.has("data")) {
                    JSONObject jsonData = mServerResponse.getJSONObject("data");
                    if (jsonData != null && !jsonData.equals("")) {
                        if (jsonData.has("user")) {
                            JSONObject jsonUser = jsonData.getJSONObject("user");
                            if (jsonUser != null && !jsonUser.equals("")) {
                                if (jsonUser.has("userid")) {
                                    AppLogger.LogE(TAG, "user id " + jsonUser.getString("userid"));
                                    userId = jsonUser.getString("userid");
                                    mAppPreference.setString(AppConstants.KEY_LOGGED_IN_USER_ID, jsonUser.getString("userid"));
                                    try {
                                        if (Utility.isInternetAvailable(MyFirebaseInstanceIDService.this)) {
                                            if (!Utility.isNullOrEmpty(refreshedToken)) {
                                                sendTokenToServer(refreshedToken);
                                            }
                                        }
                                    } catch (JSONException e) {
                                        e.printStackTrace();
                                    }
                                }

                                String name = "";
                                if (jsonUser.has("firstname") && !Utility.isNullOrEmpty(jsonUser.getString("firstname"))) {
                                    name = jsonUser.getString("firstname");
                                }
                                if (jsonUser.has("lastname") && !Utility.isNullOrEmpty(jsonUser.getString("lastname"))) {
                                    name = name + " " + jsonUser.getString("lastname");
                                }
                                mAppPreference.setString(AppConstants.KEY_LOGGED_IN_USER_NAME, name);

                                if (jsonUser.has("settings") && !Utility.isNullOrEmpty(jsonUser.getString("settings"))) {
                                    JSONObject settingsJson = jsonUser.getJSONObject("settings");
                                    if (settingsJson.has("is_sound_enabled")) {
                                        mAppPreference.setString(AppPreference.KEY_SETTING_IS_SOUND_ENABLE, settingsJson.getString("is_sound_enabled"));

                                    }
                                    if (settingsJson.has("is_vibration_enabled")) {
                                        mAppPreference.setString(AppPreference.KEY_SETTING_IS_VIBRATION_ENABLE, settingsJson.getString("is_vibration_enabled"));

                                    }
                                }


                            }
                        }
                    }
                }

            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }*/

}
