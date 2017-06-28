package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;

import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyErrorHelper;
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

    @Override
    public void initView() {
        enabledDisableLoginButton(false);
        email_address.setText("kdeopura@aecordigital.com");
        sign_in_password.setText("password");
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

    @OnClick(R.id.tv_forgot_password)
    protected void onForgotPasswordClicked() {
        Intent mForgotPasswordIntent = new Intent(mContext, ForgotPasswordActivity.class);
        startActivity(mForgotPasswordIntent);
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
        boolean valid = false;

        String emailOrPhone = email_address.getText().toString();
        String password = sign_in_password.getText().toString();

        if (emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (password.isEmpty() || password.length() < 5) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        return valid;
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

    private void validate_user() {
        Utility.startProgress(mContext);
        String url = ApiConstants.CHECK_USER;
        final JSONObject requestJson1 = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(Request.Method
                    .GET, url,
                    requestJson1, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Sign in response *****" + response.toString());
                        ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(response.get("userData").toString(), ProfileModel.class);
                        String profile = GsonConverter.getInstance().encodeToJsonString(profileModel);

                        if(response.getString("authenticated").equalsIgnoreCase("true")) {
                            mAppSharedPref.setString(AppConstants.PREF_EMAIL, email_address.getText().toString());
                            mAppSharedPref.setString(AppConstants.PREF_PASSWORD, sign_in_password.getText().toString());
                            mAppSharedPref.setString(AppConstants.PREF_PROFILE, profile);
                            startActivity(new Intent(mContext, HomeActivity.class));
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
//                        VolleyErrorHelper.getMessage(error, mContext);
                        Utility.showToast(mContext, VolleyErrorHelper.getMessage(error, mContext));
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, mAppSharedPref.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest1);
        }
    }

    private void checkuser() {
        Utility.startProgress(mContext);
        String url = ApiConstants.SIGN_IN;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("email", email_address.getText().toString().trim());
            requestJson.put("password", sign_in_password.getText().toString().trim());
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if (Utility.isInternetAvailable(mContext)) {
            AppLogger.LogE(TAG, "***** Sign in request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Sign in response *****" + response.toString());
                        String token = response.get(AppConstants.PREF_TOKEN).toString();
                        mAppSharedPref.setString(AppConstants.PREF_TOKEN,token);
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
}
