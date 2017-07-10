package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.content.IntentCompat;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
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

/**
 * Created by system-local on 22-06-2017.
 */

public class ForgotPasswordOtpActivity extends BaseActivity {
    private final String TAG = ForgotPasswordOtpActivity.class.getSimpleName();
    @BindView(R.id.et_email)
    protected EditText et_email;
    @BindView(R.id.et_otp)
    protected EditText et_otp;
    @BindView(R.id.et_password)
    protected EditText et_password;
    @BindView(R.id.btn_change_password)
    protected Button btn_change_password;
    private Context mContext;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    private String otp;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forgot_password_otp);
        Bundle extras = getIntent().getExtras();
        if (extras == null) {
            otp = "";
        } else {
            otp = extras.getString(AppConstants.ARG_FORGOT_PASSWORD_OTP);
        }
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            btn_change_password.setEnabled(true);
            btn_change_password.setBackground(getResources().getDrawable(R.drawable.btn_yellow));
        } else {
            btn_change_password.setEnabled(false);
            btn_change_password.setBackground(getResources().getDrawable(R.drawable.btn_disable));
        }
    }

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        checkValidation();
        setListener();
    }

    @Override
    protected void setListener() {
        GenericTextMatcher mTextWatcher = new GenericTextMatcher();
        et_email.addTextChangedListener(mTextWatcher);
        et_otp.addTextChangedListener(mTextWatcher);
        et_password.addTextChangedListener(mTextWatcher);
    }

    @OnClick(R.id.btn_change_password)
    protected void onChangePasswordClicked() {
        if (et_otp.getText().toString().equalsIgnoreCase(otp)) {
            resetUserPassword();
        } else {
            Utility.showToast(mContext, getString(R.string.please_enter_valid_otp));
        }
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

        String emailOrPhone = et_email.getText().toString();
        String otpText = et_otp.getText().toString();
        String updatedPassword = et_password.getText().toString();

        if (Utility.isNullOrEmpty(emailOrPhone) || !Utility.isValidEmail(emailOrPhone)) {
            return false;
        }
        if (Utility.isNullOrEmpty(otpText)) {
            return false;
        }
        if (Utility.isNullOrEmpty(updatedPassword)) {
            return false;
        }

        return true;
    }

    private void resetUserPassword() {

        Utility.startProgress(mContext);
        String url = ApiConstants.RESET_PASSWORD;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("email", et_email.getText().toString().trim());
            requestJson.put("password", et_password.getText().toString().trim());
            requestJson.put("otp", et_otp.getText().toString().trim());
        } catch (JSONException e) {
            e.printStackTrace();
        }
        if (Utility.isInternetAvailable(mContext)) {
            AppLogger.LogE(TAG, "***** Forgot password request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        if (response != null) {
                            AppLogger.LogE(TAG, "***** Reset password response *****" + response.toString());
                            if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                Utility.showToast(mContext, getString(R.string.password_updated_successfully));
                                Intent mIntent = new Intent(mContext, SignInActivity.class);
                                mIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK |
                                        IntentCompat.FLAG_ACTIVITY_CLEAR_TASK);
                                startActivity(mIntent);
                                finish();
                            }
                        } else
                            Utility.showToast(mContext, getString(R.string.error));
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
