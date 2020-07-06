package com.aecor.eurosports.activity;

import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.aecor.eurosports.R;
import com.aecor.eurosports.application.ApplicationClass;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.ConnectivityChangeReceiver;
import com.aecor.eurosports.util.Utility;

import butterknife.BindView;

/**
 * Created by karan on 6/22/2017.
 */

public abstract class BaseActivity extends AppCompatActivity implements ConnectivityChangeReceiver.ConnectivityReceiverListener {

    private Context mContext;
    private AppPreference mPref;
    @BindView(R.id.tv_no_internet)
    protected TextView tv_no_internet;

    protected abstract void initView();

    protected abstract void setListener();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mContext = this;
        mPref = AppPreference.getInstance(mContext);
        Utility.updateRecentTaskHeaderColor(mContext);
    }

    @Override
    protected void onResume() {
        super.onResume();
        String language = mPref.getString(AppConstants.LANGUAGE_SELECTION);
        if (Utility.isNullOrEmpty(language))
            Utility.setLocale(mContext, "en");
        else
            Utility.setLocale(mContext, language);
        ApplicationClass.getInstance().setConnectivityListener(this);

        checkConnection();
    }


    @Override
    public void onNetworkConnectionChanged() {
        checkConnection();
    }

    // Method to manually check connection status
    protected void checkConnection() {
        boolean isConnected = ConnectivityChangeReceiver.isConnected();
        if (isConnected) {
            tv_no_internet.setVisibility(View.GONE);
        } else {
            tv_no_internet.setVisibility(View.VISIBLE);
        }
    }

}
