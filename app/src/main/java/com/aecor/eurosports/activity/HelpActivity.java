package com.aecor.eurosports.activity;

import android.os.Bundle;

import androidx.annotation.Nullable;

import com.aecor.eurosports.R;

/**
 * Created by system-local on 26-04-2017.
 */

public class HelpActivity extends BaseAppCompactActivity {

    @Override
    protected void initView() {
        showBackButton(getString(R.string.help));
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.help);
        super.onCreate(savedInstanceState);
        initView();
    }

}
