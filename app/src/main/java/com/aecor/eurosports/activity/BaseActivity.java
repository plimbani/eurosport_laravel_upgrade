package com.aecor.eurosports.activity;

import android.app.Activity;

/**
 * Created by karan on 6/22/2017.
 */

public abstract class BaseActivity extends Activity{

    protected abstract void initView();

    protected abstract void setListener();

}
