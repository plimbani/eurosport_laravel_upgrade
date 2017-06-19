package com.aecor.eurosports.activity;

import android.app.Activity;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;

/**
 * Created by karan on 6/19/2017.
 */

public abstract class BaseAppCompactActivity extends AppCompatActivity {

    public abstract void initView();
    public abstract void setListener();


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }
}
