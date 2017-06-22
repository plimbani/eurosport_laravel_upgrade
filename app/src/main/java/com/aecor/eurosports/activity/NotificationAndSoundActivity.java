package com.aecor.eurosports.activity;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v7.widget.SwitchCompat;
import android.widget.CompoundButton;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;

import org.json.JSONException;
import org.json.JSONObject;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 26-04-2017.
 */

public class NotificationAndSoundActivity extends BaseActivity {

    private static final String TAG = "NotificationAndSoundsActivity";
    @BindView(R.id.sc_sound)
    protected SwitchCompat sc_sound;

    @BindView(R.id.sc_vibration)
    protected SwitchCompat sc_vibration;

    @BindView(R.id.sc_notification)
    protected SwitchCompat sc_notification;
    private AppPreference mAppSharedPref;
    private Context mContext;
    private RequestQueue mQueue;
    CompoundButton.OnCheckedChangeListener ccl = new CompoundButton.OnCheckedChangeListener() {
        @Override
        public void onCheckedChanged(CompoundButton compoundButton,
                                     boolean isChecked) {

            switch (compoundButton.getId()) {
                case R.id.sc_sound:

                    try {
                        postSettingsParam();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                    break;
                case R.id.sc_vibration:
                    try {
                        postSettingsParam();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    break;
                case R.id.sc_notification:
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
        try {
            getSettingsParam();
        } catch (JSONException e) {
            e.printStackTrace();
        }
        setListener();

    }

    protected void setListener() {
        sc_sound.setOnCheckedChangeListener(ccl);
        sc_vibration.setOnCheckedChangeListener(ccl);
        sc_notification.setOnCheckedChangeListener(ccl);
    }

    private void getSettingsParam() throws JSONException {


    }

    private void postSettingsParam() throws JSONException {


    }

}
