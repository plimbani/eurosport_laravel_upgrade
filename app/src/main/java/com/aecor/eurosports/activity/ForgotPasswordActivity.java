package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;
import com.android.volley.DefaultRetryPolicy;
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

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forgot_password);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            btn_get_otp.setEnabled(true);
            btn_get_otp.setBackground(getResources().getDrawable(R.drawable.btn_yellow));
        } else {
            btn_get_otp.setEnabled(false);
            btn_get_otp.setBackground(getResources().getDrawable(R.drawable.btn_disable));
        }
    }

    @Override
    protected void initView() {
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
        checkUser();
    }

    private void checkUser() {
        Utility.startProgress(mContext);
        String url = ApiConstants.FORGOT_PASSWORD;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("email", forgot_email_address.getText().toString().trim());
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if (Utility.isInternetAvailable(mContext)) {
            AppLogger.LogE(TAG, "***** Forgot password request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        if(response != null && response.length() > 0) {
                            AppLogger.LogE(TAG, "***** Forgot password response *****" + response.toString());
                            if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                Utility.showToast(mContext, response.toString());
                                startActivity(new Intent(mContext, ForgotPasswordOtpActivity.class));
                            }
                        }
                        else
                            Utility.showToast(mContext, "Response is null");
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
            jsonRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                    DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                    DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
            mQueue.add(jsonRequest);
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
        boolean valid = false;

        String emailOrPhone = forgot_email_address.getText().toString();
        if (emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }
        return valid;
    }

    private class GenericTextMatcher implements TextWatcher {

        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {  }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            checkValidation();
        }

        @Override
        public void afterTextChanged(Editable s) {  }
    }
}
