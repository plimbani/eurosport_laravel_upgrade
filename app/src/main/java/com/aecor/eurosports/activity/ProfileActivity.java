package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;

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
import butterknife.OnClick;

/**
 * Created by system-local on 26-04-2017.
 */

public class ProfileActivity extends BaseActivity implements ImageOptionDialogActivity.onImageSelectedInterface {
    private static final String TAG = "ProfileActivity";

    @BindView(R.id.iv_profileImage)
    protected ImageView iv_profileImage;
    @BindView(R.id.input_first_name)
    protected EditText input_first_name;
    @BindView(R.id.input_last_name)
    protected EditText input_last_name;
    @BindView(R.id.input_email)
    protected EditText input_email;

    @BindView(R.id.btn_update)
    protected Button btn_update;

    private AppPreference mAppPref;
    private Context mContext;


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.profile);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        initView();
    }

    @OnClick(R.id.btn_update)
    protected void onUpdateButtonClicked() {
        Utility.startProgress(mContext);

    }


    protected void initView() {


        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);


        setListener();
    }

    protected void setListener() {
        GenericTextMatcher textWatcher = new GenericTextMatcher();
        input_last_name.addTextChangedListener(textWatcher);
        input_first_name.addTextChangedListener(textWatcher);
    }


    public boolean validate() {

        boolean isValid = false;
        if (!Utility.isNullOrEmpty(input_first_name.getText().toString().trim())) {
            isValid = true;
        } else {
            isValid = false;
            return isValid;
        }

        if (!Utility.isNullOrEmpty(input_last_name.getText().toString().trim())) {
            isValid = true;
        } else {
            isValid = false;
            return isValid;
        }

        return isValid;
    }

    private void checkValidation() {
        if (!validate()) {
            enabledDisableLoginButton(false);
        } else {
            enabledDisableLoginButton(true);
        }
    }

    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            btn_update.setEnabled(true);
            btn_update.setBackgroundResource(R.drawable.btn_yellow);
        } else {
            btn_update.setEnabled(false);
            btn_update.setBackgroundResource(R.drawable.btn_disable);
        }
    }


    @OnClick(R.id.iv_change_profile_pic)
    protected void changeProfileImage() {
        Intent mImageOptionDialogIntent = new Intent(mContext, ImageOptionDialogActivity.class);
        ImageOptionDialogActivity.mCallback = this;
        startActivity(mImageOptionDialogIntent);

    }

    @Override
    public void selectedImageBitmap(Bitmap btm) {
        if (btm != null) {
            iv_profileImage.setImageBitmap(btm);
        } else {
            iv_profileImage.setImageResource(0);
            iv_profileImage.setImageResource(R.drawable.profile_placeholder);
        }

        uploadProfileImage(btm);
    }

    private void uploadProfileImage(Bitmap selectedBitmap) {

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
