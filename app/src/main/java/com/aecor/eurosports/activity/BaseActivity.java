package com.aecor.eurosports.activity;

import android.app.Activity;
import android.app.ActivityManager;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;


/**
 * Created by system-local on 19-06-2017.
 */

public abstract class BaseActivity extends Activity {


    protected abstract void initView();

    protected abstract void setListener();

}
