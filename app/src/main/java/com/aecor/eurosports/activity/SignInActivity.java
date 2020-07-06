package com.aecor.eurosports.activity;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.LinearLayout;

import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ProfileModel;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.ViewDialog;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.InstanceIdResult;
import com.testfairy.TestFairy;

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
    @BindView(R.id.cb_remember_me)
    protected CheckBox cb_remember_me;

    @Override
    public void initView() {
        String access = getIntent().getStringExtra("accessCode");
        boolean ss = getIntent().getBooleanExtra("isFromUrl", false);
        enabledDisableLoginButton(false);
        Utility.setupUI(mContext, ll_main_layout);
        mAppSharedPref = AppPreference.getInstance(mContext);
        if (!Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.KEY_REMEMBER_EMAIL))
                && !Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.KEY_REMEMBER_PASSWORD))) {
            email_address.setText(mAppSharedPref.getString(AppConstants.KEY_REMEMBER_EMAIL));
            sign_in_password.setText(mAppSharedPref.getString(AppConstants.KEY_REMEMBER_PASSWORD));
            cb_remember_me.setChecked(true);
            checkValidation();
        } else {
            cb_remember_me.setChecked(false);
        }
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
        intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(intent);
        finish();
    }

    @OnClick(R.id.tv_forgot_password)
    protected void onForgotPasswordClicked() {
        Intent mForgotPasswordIntent = new Intent(mContext, ForgotPasswordActivity.class);
        mForgotPasswordIntent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        mForgotPasswordIntent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
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

    @SuppressLint("NewApi")
    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            log_in.setEnabled(true);
            log_in.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
            log_in.setBackground(getResources().getDrawable(R.drawable.btn_yellow));
        } else {
            log_in.setEnabled(false);
            log_in.setTextColor(Color.BLACK);
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
                    .POST, url,
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
//                            if (!BuildConfig.isEasyMatchManager)
                            mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                            mAppSharedPref.setString(AppConstants.PREF_IMAGE_URL, jsonObject.getString("profile_image_url"));

                            if (jsonObject.has("role")) {
                                mAppSharedPref.setString(AppConstants.PREF_ROLE, jsonObject.getString("role"));
                            }
                            if (jsonObject.has("country_id")) {
                                mAppSharedPref.setString(AppConstants.PREF_COUNTRY_ID, jsonObject.getString("country_id"));
                            }
                            if (response != null && response.has("enable_logs_android")) {
                                String enable_logs_android = response.getString("enable_logs_android");
                                if (!Utility.isNullOrEmpty(enable_logs_android) && enable_logs_android.equalsIgnoreCase("true")) {
                                    TestFairy.begin(mContext, "SDK-7273syUD");
                                    mAppSharedPref.setString(AppConstants.KEY_ENABLE_LOGS_ANDROID, "true");
                                    TestFairy.setUserId(jsonObject.getString("user_id"));
                                }
                            }

                            if (jsonObject.has("locale") && !Utility.isNullOrEmpty(jsonObject.getString("locale"))) {
                                mAppSharedPref.setString(AppConstants.PREF_USER_LOCALE, jsonObject.getString("locale"));
                                mAppSharedPref.setString(AppConstants.LANGUAGE_SELECTION, jsonObject.getString("locale"));
                                Utility.setLocale(mContext, jsonObject.getString("locale"));
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
                            if (jsonObject.has("tournament_id") && !Utility.isNullOrEmpty(jsonObject.getString("tournament_id"))) {
                                mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
//                                mAppSharedPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                            }
                            checkIfNewTokenIsAvailable();
                        } else {
//                            {"authenticated":false,"message":"Account de-activated please contact your administrator."}
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
//                                Utility.showToast(mContext, response.getString("message"));
                                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.email_verification), response.getString("message"), mContext.getString(R.string.resend_email), new ViewDialog.CustomDialogInterface() {
                                    @Override
                                    public void onPositiveButtonClicked() {
                                        resendEmail();
                                    }

                                });
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
        } else {
            checkConnection();
        }
    }

    private void postUserDeviceDetails() {

        if (Utility.isInternetAvailable(mContext)) {
            PackageManager manager = getPackageManager();
            PackageInfo info;
            String installedAppVersion = "";
            try {
                info = manager.getPackageInfo(getPackageName(), 0);
                installedAppVersion = info.versionName;
            } catch (PackageManager.NameNotFoundException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }
            Utility.startProgress(mContext);
            String url = ApiConstants.POST_USER_DETAILS;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("device", "Android");
                requestJson.put("app_version", installedAppVersion);
                requestJson.put("os_version", Utility.getOsVersion(mContext));
                requestJson.put("user_id", mAppSharedPref.getString(AppConstants.PREF_USER_ID));
            } catch (JSONException e) {
                e.printStackTrace();
            }
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Post User details response *****" + response.toString());

                        launchHome();
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
        } else {
            checkConnection();
        }
    }

    private void resendEmail() {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.RESEND_EMAIL;
            final JSONObject requestJson1 = new JSONObject();
            try {
                requestJson1.put("email", email_address.getText().toString().trim());
            } catch (JSONException e) {
                e.printStackTrace();
            }
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson1, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Resend email response *****" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                String messgae = response.getString("message");
                                Utility.showToast(mContext, messgae);
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
        } else {
            checkConnection();
        }
    }

    private void checkIfNewTokenIsAvailable() {
        FirebaseInstanceId.getInstance().getInstanceId()
                .addOnCompleteListener(new OnCompleteListener<InstanceIdResult>() {
                    @Override
                    public void onComplete(@NonNull Task<InstanceIdResult> task) {
                        if (!task.isSuccessful()) {
                            AppLogger.LogE(TAG, "getInstanceId failed" + task.getException());
                            postUserDeviceDetails();
                            return;
                        }

                        // Get new Instance ID token
                        String token = task.getResult().getToken();
                        postTokenOnServer(token);
                    }
                });
    }

    private void launchHome() {
        if (BuildConfig.isEasyMatchManager) {
            if (getIntent().getBooleanExtra("isFromUrl", false) && getIntent().getStringExtra("accessCode") != null && getIntent().getStringExtra("accessCode").trim().length() > 0) {
                //call access api
                callAccessCodeApi(getIntent().getStringExtra("accessCode"));
            } else {
                if (Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_TOURNAMENT_ID))) {
                    //get started screen
                    startActivity(new Intent(mContext, GetStartedActivity.class));
                } else {
                    if (Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_COUNTRY_ID))) {
                        startActivity(new Intent(mContext, ProfileActivity.class));
                    } else {
                        startActivity(new Intent(mContext, HomeActivity.class));
                    }
                }
                finish();
            }
        } else {
            if (Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_COUNTRY_ID))) {
                startActivity(new Intent(mContext, ProfileActivity.class));
            } else {
                startActivity(new Intent(mContext, HomeActivity.class));
            }
            finish();
        }
    }


    private void callAccessCodeApi(String accessCode) {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressDialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.ACCESS_CODE;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("accessCode", accessCode);
            } catch (JSONException e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressDialog);
                    try {

                        AppLogger.LogE(TAG, "access code response" + response.toString());
                        if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                            TournamentModel mTempFavTournament = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel.class);
                            if (mTempFavTournament.getId() != null) {
                                mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, mTempFavTournament.getId());
                                startActivity(new Intent(mContext, HomeActivity.class));
                                finish();
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
                        Utility.StopProgress(mProgressDialog);
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
                            postUserDeviceDetails();
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
        if (cb_remember_me.isChecked()) {
            mAppSharedPref.setString(AppConstants.KEY_REMEMBER_EMAIL, email_address.getText().toString().trim());
            mAppSharedPref.setString(AppConstants.KEY_REMEMBER_PASSWORD, sign_in_password.getText().toString().trim());
        } else {
            mAppSharedPref.setString(AppConstants.KEY_REMEMBER_EMAIL, "");
            mAppSharedPref.setString(AppConstants.KEY_REMEMBER_PASSWORD, "");
        }

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
        mLandingActivityIntent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        mLandingActivityIntent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(mLandingActivityIntent);
        finish();
    }
}
