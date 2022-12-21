package com.aecor.eurosports.util;

/**
 * Created by system-local on 24-08-2017.
 */

import android.annotation.SuppressLint;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import com.aecor.eurosports.application.ApplicationClass;

public class ConnectivityChangeReceiver
        extends BroadcastReceiver {

    public static ConnectivityReceiverListener connectivityReceiverListener;

    public ConnectivityChangeReceiver() {
        super();
    }

    @Override
    public void onReceive(Context context, Intent arg1) {


        if (connectivityReceiverListener != null) {
            connectivityReceiverListener.onNetworkConnectionChanged();
        }
    }

    public static boolean isConnected() {
        ConnectivityManager
                cm = (ConnectivityManager) ApplicationClass.getInstance().getApplicationContext()
                .getSystemService(Context.CONNECTIVITY_SERVICE);
        @SuppressLint("MissingPermission") NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
        return activeNetwork != null
                && activeNetwork.isConnectedOrConnecting();
    }


    public interface ConnectivityReceiverListener {
        void onNetworkConnectionChanged();
    }
}