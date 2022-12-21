package com.aecor.eurosports.activity;

import android.content.Context;
import android.os.Bundle;
import android.widget.CompoundButton;

import androidx.annotation.Nullable;
import androidx.appcompat.widget.SwitchCompat;

import com.aecor.eurosports.R;
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

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 26-04-2017.
 */

public class NotificationAndSoundActivity extends BaseAppCompactActivity {

    private static final String TAG = "NotificationAndSoundsActivity";
    @BindView(R.id.sc_sound)
    protected SwitchCompat sc_sound;

    @BindView(R.id.sc_vibration)
    protected SwitchCompat sc_vibration;

    @BindView(R.id.sc_notification)
    protected SwitchCompat sc_notification;
    private Context mContext;
    private AppPreference mAppSharedPref;


    CompoundButton.OnCheckedChangeListener ccl = new CompoundButton.OnCheckedChangeListener() {
        @Override
        public void onCheckedChanged(CompoundButton compoundButton,
                                     boolean isChecked) {

            switch (compoundButton.getId()) {
                case R.id.sc_sound:
                case R.id.sc_notification:
                case R.id.sc_vibration:
                    try {
                        postSettingsParam();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    break;
            }

        }
    };

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.settings_notification_sound);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        initView();
    }

    protected void initView() {
        mContext = this;
        mAppSharedPref = AppPreference.getInstance(mContext);
        getSettingsParam();
        setListener();
        showBackButton(getString(R.string.notification_and_sounds));
    }

    protected void setListener() {
        sc_sound.setOnCheckedChangeListener(ccl);
        sc_vibration.setOnCheckedChangeListener(ccl);
        sc_notification.setOnCheckedChangeListener(ccl);
    }

    private void getSettingsParam() {


        if (Utility.isInternetAvailable(mContext)) {

            Utility.startProgress(mContext);
            String url = ApiConstants.GET_SETTINGS_ATTRIBUTE;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
            } catch (JSONException e) {
                e.printStackTrace();
            }
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Get Settings Param response  *****" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && response.get("data") != null) {
                                JSONArray mDataJsonArray = response.getJSONArray("data");
                                if (mDataJsonArray != null && mDataJsonArray.length() > 0) {
                                    JSONObject mProfileJson = mDataJsonArray.getJSONObject(0);
                                    if (mProfileJson.has("value") && !Utility.isNullOrEmpty(mProfileJson.getString("value"))) {
                                        JSONObject mValue = new JSONObject(mProfileJson.getString("value"));
                                        sc_sound.setOnCheckedChangeListener(null);
                                        sc_vibration.setOnCheckedChangeListener(null);
                                        sc_notification.setOnCheckedChangeListener(null);

                                        if (mValue.has("is_sound") && !Utility.isNullOrEmpty(mValue.getString("is_sound")) && mValue.getString("is_sound").equalsIgnoreCase("true")) {
                                            sc_sound.setChecked(true);
                                            mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, true);
                                        } else {
                                            sc_sound.setChecked(false);
                                            mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, false);
                                        }

                                        if (mValue.has("is_vibration") && !Utility.isNullOrEmpty(mValue.getString("is_vibration")) && mValue.getString("is_vibration").equalsIgnoreCase("true")) {
                                            sc_vibration.setChecked(true);
                                            mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, true);
                                        } else {
                                            sc_vibration.setChecked(false);
                                            mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, false);
                                        }

                                        if (mValue.has("is_notification") && !Utility.isNullOrEmpty(mValue.getString("is_notification")) && mValue.getString("is_notification").equalsIgnoreCase("true")) {
                                            sc_notification.setChecked(true);
                                            mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, true);
                                        } else {
                                            sc_notification.setChecked(false);
                                            mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, false);
                                        }

                                        setListener();

                                    }
                                }
                            }
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        } else {
            checkConnection();
        }

    }

    private void postSettingsParam() {

        if (Utility.isInternetAvailable(mContext)) {

            Utility.startProgress(mContext);
            String url = ApiConstants.UPDATE_USER_SETTINGS;
            final JSONObject requestJson = new JSONObject();
            try {
//            requestJson.put("user_id", Utility.getUserId(mContext));
                JSONObject mUserData = new JSONObject();
                mUserData.put("userId", Utility.getUserId(mContext));

                JSONObject mUserSettings = new JSONObject();
                if (sc_sound.isChecked()) {
                    mUserSettings.put("is_sound", "true");
                    mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, true);
                } else {
                    mUserSettings.put("is_sound", "false");
                    mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, false);
                }
                if (sc_vibration.isChecked()) {
                    mUserSettings.put("is_vibration", "true");
                    mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, true);
                } else {
                    mUserSettings.put("is_vibration", "false");
                    mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, false);
                }
                if (sc_notification.isChecked()) {
                    mUserSettings.put("is_notification", "true");
                    mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, true);
                } else {
                    mUserSettings.put("is_notification", "false");
                    mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, false);
                }
                mUserData.put("userSettings", mUserSettings);
                requestJson.put("userData", mUserData);
            } catch (JSONException e) {
                e.printStackTrace();
            }
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Post setting param response *****" + response.toString());
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        } else {
            checkConnection();
        }

    }

}
