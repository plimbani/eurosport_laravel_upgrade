package com.aecor.eurosports.application;

import android.app.Application;
import android.util.Log;

import com.aecor.eurosports.util.AppVisibilityDetector;
import com.aecor.eurosports.util.AutoLoginUtils;
import com.aecor.eurosports.util.ConnectivityChangeReceiver;

/**
 * Created by asoni on 02-06-2016.
 */
public class ApplicationClass extends Application {
    private static ApplicationClass sInstance;
    private final String TAG = "ApplicationClass";

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
                        AutoLoginUtils.checkuser(ApplicationClass.this);
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

    public void setConnectivityListener(ConnectivityChangeReceiver.ConnectivityReceiverListener listener) {
        ConnectivityChangeReceiver.connectivityReceiverListener = listener;
    }

}

