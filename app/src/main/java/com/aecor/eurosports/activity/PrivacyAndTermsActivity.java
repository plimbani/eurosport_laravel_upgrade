package com.aecor.eurosports.activity;

import android.os.Bundle;
import android.support.annotation.Nullable;

import com.aecor.eurosports.R;

/**
 * Created by system-local on 26-04-2017.
 */

public class PrivacyAndTermsActivity extends BaseAppCompactActivity {

    @Override
    protected void initView() {
        showBackButton(getString(R.string.privacy_terms));
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.privacy_terms);
        super.onCreate(savedInstanceState);
        initView();
    }


}
