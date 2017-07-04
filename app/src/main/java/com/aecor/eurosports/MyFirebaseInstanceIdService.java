package com.aecor.eurosports;

import android.content.Context;
import android.util.Log;

import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;

/**
 * Created by karan on 7/3/2017.
 */

public class MyFirebaseInstanceIdService extends FirebaseInstanceIdService {

    private final String TAG = "FirebaseIIDService";
    private AppPreference appPreference;
    private Context mContext;
    private String refreshedToken;

    @Override
    public void onTokenRefresh() {
        mContext = this;
        AppPreference.initializeInstance(mContext);
        appPreference = AppPreference.getInstance(mContext);

        refreshedToken = FirebaseInstanceId.getInstance().getToken();
        Log.d(TAG, "*** Refreshed token *** -> " + refreshedToken);
        if (Utility.isInternetAvailable(this)) {
            appPreference.setString(AppConstants.FIREBASE_TOKEN, refreshedToken);
        }
    }

}
