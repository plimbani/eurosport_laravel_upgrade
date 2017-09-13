package com.aecor.eurosports.application;

import android.app.Application;

import com.aecor.eurosports.util.ConnectivityChangeReceiver;

/**
 * Created by asoni on 02-06-2016.
 */
public class ApplicationClass extends Application {
    private static ApplicationClass sInstance;


    public static ApplicationClass getInstance() {
        return sInstance;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        sInstance = this;
    }

    public void setConnectivityListener(ConnectivityChangeReceiver.ConnectivityReceiverListener listener) {
        ConnectivityChangeReceiver.connectivityReceiverListener = listener;
    }

}

