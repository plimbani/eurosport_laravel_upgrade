package com.aecor.eurosports.activity;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;

import com.aecor.eurosports.R;

/**
 * Created by system-local on 03-07-2017.
 */

public class VenueDetailActivity extends BaseAppCompactActivity {
    private Context mContext;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.venue_detail);
        super.onCreate(savedInstanceState);
        mContext = this;
    }

    @Override
    protected void initView() {

    }

    @Override
    protected void setListener() {

    }
}
