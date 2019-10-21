package com.aecor.eurosports.http;

import android.content.Context;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.Volley;

/**
 * Created by system-local on 07-06-2016.
 */
public class VolleySingeltone {
    private static VolleySingeltone mAppSingletonInstance;
    private static Context mContext;
    private RequestQueue mRequestQueue;

    private VolleySingeltone(Context context) {
        mContext = context;
        mRequestQueue = getRequestQueue();

    }

    public static synchronized VolleySingeltone getInstance(Context context) {
        if (mAppSingletonInstance == null) {
            mAppSingletonInstance = new VolleySingeltone(context);
        }
        return mAppSingletonInstance;
    }


    public RequestQueue getRequestQueue() {
        if (mRequestQueue == null) {
            // getApplicationContext() is key, it keeps you from leaking the
            // Activity or BroadcastReceiver if someone passes one in.
            mRequestQueue = Volley.newRequestQueue(mContext.getApplicationContext());
        }
        return mRequestQueue;
    }
}
