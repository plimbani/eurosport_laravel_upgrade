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
import com.aecor.eurosports.util.Utility;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 22-06-2017.
 */

public class ForgotPasswordActivity extends BaseActivity {
    @BindView(R.id.register_email_address)
    protected EditText register_email_address;
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
        register_email_address.addTextChangedListener(mTextWatcher);
    }

    @OnClick(R.id.btn_get_otp)
    protected void onGetOtpClicked() {
        Intent mValidateOtpIntent = new Intent(mContext, ForgotPasswordOtpActivity.class);
        startActivity(mValidateOtpIntent);
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

        String emailOrPhone = register_email_address.getText().toString();

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
