package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;

import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;

/**
 * Created by karan on 6/22/2017.
 */

public abstract class BaseActivity extends Activity{

    private Context mContext;
    private AppPreference mPref;
    protected abstract void initView();

    protected abstract void setListener();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mContext = this;
        mPref = AppPreference.getInstance(mContext);

        String language = mPref.getString(AppConstants.LANGUAGE_SELECTION);
        if(Utility.isNullOrEmpty(language))
            Utility.setLocale(mContext, "en");
        else
            Utility.setLocale(mContext, language);
    }

}
