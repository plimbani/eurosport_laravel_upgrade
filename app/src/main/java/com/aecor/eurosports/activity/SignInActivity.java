package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;

import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ProfileModel;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
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
import butterknife.OnClick;

public class SignInActivity extends BaseActivity {
    private final String TAG = SignInActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_email)
    protected EditText email_address;
    @BindView(R.id.et_password)
    protected EditText sign_in_password;
    @BindView(R.id.signin)
    protected Button log_in;
    private AppPreference mAppSharedPref;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;

    @Override
    public void initView() {
        enabledDisableLoginButton(false);
        Utility.setupUI(mContext, ll_main_layout);
        mAppSharedPref = AppPreference.getInstance(mContext);
        setListener();
    }

    @Override
    public void setListener() {
        GenericTextMatcher mTextChangeLister = new GenericTextMatcher();
        email_address.addTextChangedListener(mTextChangeLister);
        sign_in_password.addTextChangedListener(mTextChangeLister);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_in);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    @OnClick(R.id.iv_header_logo)
    protected void onHeaderLogoClicked() {
        Intent intent = new Intent(mContext, LandingActivity.class);
        startActivity(intent);
        finish();
    }

    @OnClick(R.id.tv_forgot_password)
    protected void onForgotPasswordClicked() {
        Intent mForgotPasswordIntent = new Intent(mContext, ForgotPasswordActivity.class);
        startActivity(mForgotPasswordIntent);
        finish();
    }

    @OnClick(R.id.signin)
    protected void signin() {
        checkuser();
    }

    private void checkValidation() {
        if (!validate()) {
            enabledDisableLoginButton(false);
            return;
        } else {
            enabledDisableLoginButton(true);
        }
    }

    public boolean validate() {

        String emailOrPhone = email_address.getText().toString();
        String password = sign_in_password.getText().toString();

        if (emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone)) {
            return false;
        }

        return !(password.isEmpty() || password.length() < 5);

    }

    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            log_in.setEnabled(true);
            log_in.setBackground(getResources().getDrawable(R.drawable.btn_yellow));
        } else {
            log_in.setEnabled(false);
            log_in.setBackground(getResources().getDrawable(R.drawable.btn_disable));
        }
    }

    @OnClick(R.id.iv_back)
    protected void onBackButtonPressed() {
        loadBackActivity();
    }

    private void validate_user() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.CHECK_USER;
            final JSONObject requestJson1 = new JSONObject();
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .GET, url,
                    requestJson1, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Sign in response *****" + response.toString());

                        if (response.getString("authenticated").equalsIgnoreCase("true")) {
                            ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(response.get("userData").toString(), ProfileModel.class);
                            String profile = GsonConverter.getInstance().encodeToJsonString(profileModel);
                            JSONObject jsonObject = new JSONObject(response.get("userData").toString());
                            mAppSharedPref.setString(AppConstants.PREF_EMAIL, email_address.getText().toString());
                            mAppSharedPref.setString(AppConstants.PREF_PASSWORD, sign_in_password.getText().toString());
                            mAppSharedPref.setString(AppConstants.PREF_PROFILE, profile);
                            mAppSharedPref.setString(AppConstants.PREF_USER_ID, jsonObject.getString("user_id"));
                            mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                            mAppSharedPref.setString(AppConstants.PREF_IMAGE_URL, jsonObject.getString("profile_image_url"));
                            if (jsonObject.has("locale") && !Utility.isNullOrEmpty(jsonObject.getString("locale"))) {
                                mAppSharedPref.setString(AppConstants.PREF_USER_LOCALE, jsonObject.getString("locale"));
                            }
                            if (jsonObject.has("settings")) {
                                JSONObject mSettingsJson = jsonObject.getJSONObject("settings");
                                if (mSettingsJson.has("value") && !Utility.isNullOrEmpty(mSettingsJson.getString("value"))) {
                                    JSONObject mValue = new JSONObject(mSettingsJson.getString("value"));
                                    if (mValue.has("is_sound") && !Utility.isNullOrEmpty(mValue.getString("is_sound")) && mValue.getString("is_sound").equalsIgnoreCase("true")) {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, true);
                                    } else {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, false);
                                    }

                                    if (mValue.has("is_vibration") && !Utility.isNullOrEmpty(mValue.getString("is_vibration")) && mValue.getString("is_vibration").equalsIgnoreCase("true")) {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, true);
                                    } else {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, false);
                                    }
                                    if (mValue.has("is_notification") && !Utility.isNullOrEmpty(mValue.getString("is_notification")) && mValue.getString("is_notification").equalsIgnoreCase("true")) {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, true);
                                    } else {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, false);
                                    }
                                }
                            }
                            checkIfNewTokenIsAvailable();
                        } else {
//                            {"authenticated":false,"message":"Account de-activated please contact your administrator."}
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                Utility.showToast(mContext, response.getString("message"));
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
            mQueue.add(jsonRequest1);
        }else{
            checkConnection();
        }
    }

    private void checkIfNewTokenIsAvailable() {
        if (!Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_TOKEN_POSTED_ONSERVER)) && mAppSharedPref.getString(AppConstants.PREF_TOKEN_POSTED_ONSERVER).equalsIgnoreCase("true")) {
            launchHome();
        } else {
            if (!Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.FIREBASE_TOKEN))) {
                postTokenOnServer(mAppSharedPref.getString(AppConstants.FIREBASE_TOKEN));
            } else {
                launchHome();
            }

        }
    }

    private void launchHome() {
        startActivity(new Intent(mContext, HomeActivity.class));
        finish();
    }

    private void postTokenOnServer(String mFcmToken) {
        String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
        if (!Utility.isNullOrEmpty(email)) {


            if (Utility.isInternetAvailable(mContext)) {
                String url = ApiConstants.POST_FCM_TOKEN;
                final JSONObject requestJson = new JSONObject();
                try {
                    requestJson.put("email", email);
                    requestJson.put("fcm_id", mFcmToken);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                AppLogger.LogE(TAG, "***** Post FCM Token request *****" + requestJson.toString());
                final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
                final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                        .POST, url,
                        requestJson, new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            AppLogger.LogE(TAG, "***** Post FCM Token response *****" + response.toString());
                            mAppSharedPref.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER, "true");
                            launchHome();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        try {
                            AppLogger.LogE(TAG, "error" + error);
                            launchHome();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                });
                mQueue.add(jsonRequest);
            }
        } else {
            mAppSharedPref.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER, "false");
            launchHome();
        }
    }

    private void checkuser() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.SIGN_IN;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("email", email_address.getText().toString().trim());
                requestJson.put("password", sign_in_password.getText().toString().trim());
            } catch (JSONException e) {
                e.printStackTrace();
            }
            AppLogger.LogE(TAG, "***** Sign in request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Sign in response *****" + response.toString());
                        String token = response.get(AppConstants.PREF_TOKEN).toString();
                        mAppSharedPref.setString(AppConstants.PREF_TOKEN, token);
                        validate_user();
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

    private class GenericTextMatcher implements TextWatcher {

        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            checkValidation();
        }

        @Override
        public void afterTextChanged(Editable s) {

        }
    }

    @Override
    public void onBackPressed() {
//        super.onBackPressed();
        loadBackActivity();
    }

    protected void loadBackActivity() {
        Intent mLandingActivityIntent = new Intent(mContext, LandingActivity.class);
        startActivity(mLandingActivityIntent);
        finish();
    }
}
