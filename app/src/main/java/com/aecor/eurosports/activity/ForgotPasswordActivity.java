package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;

import androidx.annotation.Nullable;
import androidx.core.content.ContextCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.util.ApiConstants;
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

public class ForgotPasswordActivity extends BaseActivity {
    private final String TAG = ForgotPasswordActivity.class.getSimpleName();
    @BindView(R.id.forgot_email_address)
    protected EditText forgot_email_address;
    @BindView(R.id.btn_get_otp)
    protected Button btn_get_otp;
    private Context mContext;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forgot_password);
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

    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            btn_get_otp.setEnabled(true);
            btn_get_otp.setBackgroundResource(R.drawable.btn_yellow);
            btn_get_otp.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
        } else {
            btn_get_otp.setEnabled(false);
            btn_get_otp.setBackgroundResource(R.drawable.btn_disable);
            btn_get_otp.setTextColor(Color.BLACK);
        }
    }

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        setListener();
        checkValidation();
    }

    @Override
    protected void setListener() {
        GenericTextMatcher mTextWatcher = new GenericTextMatcher();
        forgot_email_address.addTextChangedListener(mTextWatcher);
    }

    @OnClick(R.id.btn_get_otp)
    protected void onGetOtpClicked() {
        forgotPassword();
    }

    private void forgotPassword() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.FORGOT_PASSWORD;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("email", forgot_email_address.getText().toString().trim());
            } catch (JSONException e) {
                e.printStackTrace();
            }
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
                            AppLogger.LogE(TAG, "***** Forgot password response *****" + response.toString());
                            if (response.has("status") && !Utility.isNullOrEmpty(response.getString("status")) && response.getString("status").equalsIgnoreCase("200")) {
//                                Intent mForgotPasswordOtpIntent = new Intent(mContext, ForgotPasswordOtpActivity.class);
//                                mForgotPasswordOtpIntent.putExtra(AppConstants.ARG_FORGOT_PASSWORD_OTP, response.getString("otp"));
//                                startActivity(mForgotPasswordOtpIntent);
                                if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message")) && response.getString("message").equalsIgnoreCase("success")) {
                                    Utility.showToast(mContext, getString(R.string.we_have_sent_password_reset_link));
                                    Intent intent = new Intent(mContext,SignInActivity.class);
                                    intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
                                    intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
                                    startActivity(intent);
                                    finish();
                                } else {
                                    Utility.showToast(mContext, response.getString("message"));
                                }

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
        } else {
            checkConnection();
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

        String emailOrPhone = forgot_email_address.getText().toString();
        return !(emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone));
    }

    @OnClick(R.id.iv_back)
    protected void onBackButtonPressed() {
        loadBackActivity();
    }

    private void loadBackActivity() {
        Intent mSignInIntent = new Intent(mContext, SignInActivity.class);
        mSignInIntent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        mSignInIntent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(mSignInIntent);
        finish();
    }

    @Override
    public void onBackPressed() {
//        super.onBackPressed();
        loadBackActivity();
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
