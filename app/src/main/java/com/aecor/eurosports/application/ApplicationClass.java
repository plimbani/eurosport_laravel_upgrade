package com.aecor.eurosports.application;

import android.app.Application;
import android.content.SharedPreferences;

/**
 * Created by asoni on 02-06-2016.
 */
public class ApplicationClass extends Application {
    private static ApplicationClass sInstance;

    SharedPreferences mPref;

    public static ApplicationClass getInstance() {
        return sInstance;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        sInstance = this;
    }


}

