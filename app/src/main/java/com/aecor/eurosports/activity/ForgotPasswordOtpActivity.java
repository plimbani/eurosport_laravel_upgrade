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

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.Utility;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 22-06-2017.
 */

public class ForgotPasswordOtpActivity extends BaseActivity {
    @BindView(R.id.et_email)
    protected EditText et_email;
    @BindView(R.id.et_otp)
    protected EditText et_otp;
    @BindView(R.id.et_password)
    protected EditText et_password;
    @BindView(R.id.btn_change_password)
    protected Button btn_change_password;
    private Context mContext;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forgot_password_otp);
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
        Intent mIntent = new Intent(mContext, SignInActivity.class);
        mIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK |
                IntentCompat.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(mIntent);
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

        String emailOrPhone = et_email.getText().toString();
        String otp = et_otp.getText().toString();
        String updatedPassword = et_password.getText().toString();

        if (emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }
        if (otp.isEmpty()) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }
        if (updatedPassword.isEmpty()) {
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
