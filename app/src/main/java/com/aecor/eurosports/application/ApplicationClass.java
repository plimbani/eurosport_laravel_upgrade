package com.aecor.eurosports.application;

import android.app.Activity;
import android.app.Application;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.util.Log;

import com.aecor.eurosports.R;
import com.aecor.eurosports.ui.ViewDialog;
import com.aecor.eurosports.util.AppVisibilityDetector;
import com.aecor.eurosports.util.AutoLoginUtils;
import com.aecor.eurosports.util.ConnectivityChangeReceiver;

import java.lang.ref.WeakReference;

/**
 * Created by asoni on 02-06-2016.
 */
public class ApplicationClass extends Application {
    private static ApplicationClass sInstance;
    private final String TAG = "ApplicationClass";
    private WeakReference<Activity> mActivity = null;

    public static ApplicationClass getInstance() {
        return sInstance;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        sInstance = this;
        AppVisibilityDetector.init(this, new AppVisibilityDetector.AppVisibilityCallback() {
            @Override
            public void onAppGotoForeground() {
                Log.e(TAG, "onAppGotoForeground() called <----------");
                Thread thread = new Thread(new Runnable() {
                    @Override
                    public void run() {

                        //AutoLoginUtils.checkAppVersion(ApplicationClass.this);
                    }
                });
                thread.start();

            }

            @Override
            public void onAppGotoBackground() {
                Log.d(TAG, "onAppGotoBackground() called ---------->");
            }
        });

    }

    public WeakReference<Activity> getmActivity() {
        return mActivity;
    }

    public void setmActivity(WeakReference<Activity> mActivity) {
        this.mActivity = mActivity;
    }

    public void setConnectivityListener(ConnectivityChangeReceiver.ConnectivityReceiverListener listener) {
        ConnectivityChangeReceiver.connectivityReceiverListener = listener;
    }

}

