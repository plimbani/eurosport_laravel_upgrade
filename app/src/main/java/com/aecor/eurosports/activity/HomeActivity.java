package com.aecor.eurosports.activity;

import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.customtabs.CustomTabsIntent;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;

import butterknife.ButterKnife;
import butterknife.OnClick;

import static com.aecor.eurosports.util.AppConstants.FACEBOOK_PAGE_ID;
import static com.aecor.eurosports.util.AppConstants.FACEBOOK_URL;
import static com.aecor.eurosports.util.AppConstants.INSTAGRAM_APP_URL;
import static com.aecor.eurosports.util.AppConstants.INSTAGRAM_URL;
import static com.aecor.eurosports.util.AppConstants.TWITTER_APP_URL;
import static com.aecor.eurosports.util.AppConstants.TWITTER_URL;

public class HomeActivity extends BaseAppCompactActivity{

    private Context mContext;

    @Override
    public void initView() {

    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_home);
        super.onCreate(savedInstanceState);
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_TOURNAMENT;

        mContext = this;
    }

    @OnClick(R.id.facebook)
    protected void open_facebook() {
        PackageManager packageManager = mContext.getPackageManager();
        String facebookUrl;
        try {
            int versionCode = packageManager.getPackageInfo("com.facebook.katana", 0).versionCode;
            if (versionCode >= 3002850) {
                //newer versions of fb app
                facebookUrl = "fb://facewebmodal/f?href=" + FACEBOOK_URL;
            } else {
                //older versions of fb app
                facebookUrl = "fb://page/" + FACEBOOK_PAGE_ID;
            }
            Intent facebookIntent = new Intent(Intent.ACTION_VIEW);
            facebookIntent.setData(Uri.parse(facebookUrl));
            startActivity(facebookIntent);
        }
        catch (PackageManager.NameNotFoundException e) {
            //normal web url
            facebookUrl = FACEBOOK_URL;
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(facebookUrl));
        }

    }

    @OnClick(R.id.instagram)
    protected void open_instagram() {
        Uri uri = Uri.parse(INSTAGRAM_APP_URL);
        Intent likeIng = new Intent(Intent.ACTION_VIEW, uri);

        likeIng.setPackage("com.instagram.android");

        try {
            startActivity(likeIng);
        } catch (ActivityNotFoundException e) {
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(INSTAGRAM_URL));
        }
    }

    @OnClick(R.id.twitter)
    protected void open_twitter() {
        Intent twitter = null;
        try {
            // get the Twitter app if possible
            mContext.getPackageManager().getPackageInfo("com.twitter.android", 0);
            twitter = new Intent(Intent.ACTION_VIEW, Uri.parse(TWITTER_APP_URL));
            twitter.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            mContext.startActivity(twitter);
        } catch (Exception e) {
            // no Twitter app, revert to browser
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(TWITTER_URL));
        }

    }
}

