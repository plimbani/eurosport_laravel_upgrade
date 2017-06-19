package com.aecor.eurosports.activity;

import android.app.Activity;

/**
 * Created by system-local on 19-06-2017.
 */

public abstract class BaseActivity extends Activity {
    protected abstract void initView();

    protected abstract void setListener();

}
