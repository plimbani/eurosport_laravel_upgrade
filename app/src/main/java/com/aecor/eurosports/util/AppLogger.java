package com.aecor.eurosports.util;

import android.util.Log;

/**
 * Created by asoni on 02-06-2016.
 */
public class AppLogger {
    public static boolean isDebugable = true;

    public static void LogD(String TAG, String message) {
        if (isDebugable) {
            Log.d(TAG, message);
        }
    }


    public static void LogI(String TAG, String message) {
        if (isDebugable) {
            Log.i(TAG, message);
        }
    }


    public static void LogE(String TAG, String message) {
        if (isDebugable) {
            Log.e(TAG, message);
        }
    }


    public static void LogV(String TAG, String message) {
        if (isDebugable) {
            Log.v(TAG, message);
        }
    }
}
