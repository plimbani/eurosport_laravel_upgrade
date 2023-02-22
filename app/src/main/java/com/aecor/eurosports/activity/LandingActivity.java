package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.os.Bundle;
import android.widget.TextView;

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
import com.bumptech.glide.util.Util;
import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.login.LoginManager;
import com.facebook.login.LoginResult;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.messaging.FirebaseMessaging;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Arrays;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class LandingActivity extends BaseActivity {

    private final String TAG = LandingActivity.class.getSimpleName();
    private Context mContext;
    private AppPreference mAppPref;

    @BindView(R.id.tvAppVersion)
    TextView tvAppVersion;

    //Facebook integration
    private CallbackManager mFacebookCallbackManager;

    @Override
    public void initView() {

    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_landing);

        ButterKnife.bind(this);
        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);
        mAppPref.clear();
        this.mFacebookCallbackManager = CallbackManager.Factory.create();

        // Adds application version at the bottom of the screen
        try {
            PackageInfo pInfo = getPackageManager().getPackageInfo(getPackageName(), 0);
            String version = pInfo.versionName;
            tvAppVersion.setText(String.format(getString(R.string.app_version), version));

            if (BuildConfig.isEasyMatchManager) {
                tvAppVersion.setTextColor(ContextCompat.getColor(this, R.color.appColorPrimary));
            } else {
                tvAppVersion.setTextColor(Color.WHITE);
            }
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }
    }

    @OnClick(R.id.signin)
    protected void signin() {
        boolean ss = getIntent().getBooleanExtra("isFromUrl", false);
        Intent intent = new Intent(mContext, SignInActivity.class);
        intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(intent);
        finish();
    }

    @OnClick(R.id.register)
    protected void register() {
        Intent intent = new Intent(mContext, RegisterActivity.class);
        intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(intent);
        finish();
    }

    @OnClick(R.id.facebook)
    protected void facebookSignIn() {
        try {
            LoginManager.getInstance().logInWithReadPermissions(
                    this,
                    Arrays.asList("email")
            );
            LoginManager.getInstance().registerCallback(this.mFacebookCallbackManager, new mFacebookCallBack());

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        this.mFacebookCallbackManager.onActivityResult(requestCode, resultCode, data);

    }

    private class mFacebookCallBack implements FacebookCallback<LoginResult> {
        public void onSuccess(LoginResult loginResult) {
            Utility.startProgress(mContext);

            if (!Utility.isNullOrEmpty(loginResult.getAccessToken().getToken())) {
                AppLogger.LogE("TAG", "loginResult" + loginResult);
                AppLogger.LogE("TAG", "loginResult" + loginResult.getAccessToken().getToken());
                AppLogger.LogE("TAG", "loginResult" + loginResult.getAccessToken().getUserId());

                socialLogin(loginResult.getAccessToken().getToken(), AppConstants.PROVIDER_SOCIAL_FACEBOOK);
            } else {
                Utility.StopProgress();
            }

        }

        public void onCancel() {
            AppLogger.LogE("TAG", "onCancel");
        }

        public void onError(FacebookException exception) {
            AppLogger.LogE("TAG", "exception" + exception);

        }
    }

    private void socialLogin(String token, String providerSocialFacebook) {
        if (Utility.isInternetAvailable(mContext)) {
            String url = ApiConstants.FACEBOOK_LOGIN;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("token", token);
                requestJson.put("provider", providerSocialFacebook);
            } catch (JSONException e) {
                e.printStackTrace();
            }

            AppLogger.LogE(TAG, "*****FB Register request *****" + requestJson.toString());
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
                        mAppPref.setString(AppConstants.PREF_TOKEN, token);
                        proceedFBLogin();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    LoginManager.getInstance().logOut();
                    try {
                        Utility.StopProgress();
                        AppLogger.LogE(TAG, "***** Register Error *****" + error.toString());
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

    private void proceedFBLogin() {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.CHECK_USER;
            final JSONObject requestJson = new JSONObject();
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** FB Register response *****" + response.toString());
                        if (response.getString("authenticated").equalsIgnoreCase("true")) {
                            ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(response.get("userData").toString(), ProfileModel.class);
                            String profile = GsonConverter.getInstance().encodeToJsonString(profileModel);
                            JSONObject jsonObject = new JSONObject(response.get("userData").toString());
                            mAppPref.setString(AppConstants.PREF_EMAIL, jsonObject.getString("email"));
                            mAppPref.setString(AppConstants.PREF_PROFILE, profile);
                            mAppPref.setString(AppConstants.PREF_USER_ID, jsonObject.getString("user_id"));
//                            if (!BuildConfig.isEasyMatchManager)
                            if (jsonObject.has("profile_image_url")) {
                                mAppPref.setString(AppConstants.PREF_IMAGE_URL, jsonObject.getString("profile_image_url"));
                            }
                            if (jsonObject.has("role")) {
                                mAppPref.setString(AppConstants.PREF_ROLE, jsonObject.getString("role"));
                            }
                            if (jsonObject.has("country_id")) {
                                mAppPref.setString(AppConstants.PREF_COUNTRY_ID, jsonObject.getString("country_id"));
                            }


                            if (jsonObject.has("locale") && !Utility.isNullOrEmpty(jsonObject.getString("locale"))) {
                                mAppPref.setString(AppConstants.PREF_USER_LOCALE, jsonObject.getString("locale"));
                                mAppPref.setString(AppConstants.LANGUAGE_SELECTION, jsonObject.getString("locale"));
                                Utility.setLocale(mContext, jsonObject.getString("locale"));
                            }
                            if (jsonObject.has("settings")) {
                                JSONObject mSettingsJson = jsonObject.getJSONObject("settings");
                                if (mSettingsJson.has("value") && !Utility.isNullOrEmpty(mSettingsJson.getString("value"))) {
                                    JSONObject mValue = new JSONObject(mSettingsJson.getString("value"));
                                    if (mValue.has("is_sound") && !Utility.isNullOrEmpty(mValue.getString("is_sound")) && mValue.getString("is_sound").equalsIgnoreCase("true")) {
                                        mAppPref.setBoolean(AppConstants.KEY_IS_SOUND, true);
                                    } else {
                                        mAppPref.setBoolean(AppConstants.KEY_IS_SOUND, false);
                                    }

                                    if (mValue.has("is_vibration") && !Utility.isNullOrEmpty(mValue.getString("is_vibration")) && mValue.getString("is_vibration").equalsIgnoreCase("true")) {
                                        mAppPref.setBoolean(AppConstants.KEY_IS_VIBRATION, true);
                                    } else {
                                        mAppPref.setBoolean(AppConstants.KEY_IS_VIBRATION, false);
                                    }
                                    if (mValue.has("is_notification") && !Utility.isNullOrEmpty(mValue.getString("is_notification")) && mValue.getString("is_notification").equalsIgnoreCase("true")) {
                                        mAppPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, true);
                                    } else {
                                        mAppPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, false);
                                    }
                                }
                            }
                            if (jsonObject.has("tournament_id") && !Utility.isNullOrEmpty(jsonObject.getString("tournament_id"))) {
                                mAppPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
//                                mAppPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                            }
                            Utility.setTFFlags(mContext);
                            checkIfNewTokenIsAvailable(true);
                        } else {
//                            {"authenticated":false,"message":"Account de-activated please contact your administrator."}
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
//                                Utility.showToast(mContext, response.getString("message"));
                                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.email_verification), response.getString("message"), mContext.getString(R.string.resend_email), new ViewDialog.CustomDialogInterface() {
                                    @Override
                                    public void onPositiveButtonClicked() {
                                        if (mAppPref.getString(AppConstants.PREF_EMAIL) != null) {
                                            resendEmail();
                                        }
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
                        AppLogger.LogE(TAG, "***** Register Error *****" + error.toString());
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

    private void resendEmail() {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.RESEND_EMAIL;
            final JSONObject requestJson1 = new JSONObject();
            try {
                requestJson1.put("email", mAppPref.getString(AppConstants.PREF_EMAIL));
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

    private void checkIfNewTokenIsAvailable(final boolean isFromFB) {


        FirebaseMessaging.getInstance().getToken()
                .addOnCompleteListener(new OnCompleteListener<String>() {
                    @Override
                    public void onComplete(@NonNull Task<String> task) {
                        if (!task.isSuccessful()) {
                            AppLogger.LogE(TAG, "getInstanceId failed" + task.getException());
                            launchHome(isFromFB);
                            return;
                        }

                        // Get new FCM registration token
                        String token = task.getResult();

                        if (!Utility.isNullOrEmpty(token)) {
                            try {
                                postTokenOnServer(token, isFromFB);
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                        } else {
                            launchHome(isFromFB);
                        }

                    }
                });
    }

    private void postTokenOnServer(String mFcmToken, final boolean isFromFB) {
        String email = mAppPref.getString(AppConstants.PREF_EMAIL);
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
                            mAppPref.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER, "true");
                            launchHome(isFromFB);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        try {
                            AppLogger.LogE(TAG, "error" + error);
                            launchHome(isFromFB);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                });
                mQueue.add(jsonRequest);
            }
        } else {
            mAppPref.setString(AppConstants.PREF_TOKEN_POSTED_ONSERVER, "false");
            launchHome(isFromFB);
        }
    }

    private void launchHome(final boolean isFromFB) {

        if (Utility.isInternetAvailable(mContext)) {
            PackageManager manager = mContext.getPackageManager();
            PackageInfo info;
            String installedAppVersion = "";
            try {
                info = manager.getPackageInfo(mContext.getPackageName(), 0);
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
                requestJson.put("user_id", mAppPref.getString(AppConstants.PREF_USER_ID));
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
                        AppLogger.LogE("TAG", "***** Post User details response *****" + response.toString());
                        mAppPref.setBoolean(AppConstants.IS_LOGIN_USING_FB, isFromFB);
                        if (BuildConfig.isEasyMatchManager) {
                            if (getIntent().getBooleanExtra("isFromUrl", false) && getIntent().getStringExtra("accessCode") != null && getIntent().getStringExtra("accessCode").trim().length() > 0) {
                                //call access api
                                callAccessCodeApi(getIntent().getStringExtra("accessCode"));
                            } else {
                                if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID))) {
                                    //get started screen
                                    startActivity(new Intent(mContext, GetStartedActivity.class));
                                } else {
                                    if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_COUNTRY_ID)) || Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL))) {
                                        startActivity(new Intent(mContext, ProfileActivity.class));
                                    } else {
                                        startActivity(new Intent(mContext, HomeActivity.class));
                                    }
                                }
                                finish();
                            }
                        } else {
                            if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_COUNTRY_ID)) || Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID)) || Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL))) {
                                startActivity(new Intent(mContext, ProfileActivity.class));
                            } else {
                                startActivity(new Intent(mContext, HomeActivity.class));
                            }
                            finish();
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
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest1);
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
                                mAppPref.setString(AppConstants.PREF_TOURNAMENT_ID, mTempFavTournament.getId());
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
}
