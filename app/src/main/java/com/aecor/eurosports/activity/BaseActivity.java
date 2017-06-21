package com.aecor.eurosports.activity;

import android.app.Activity;
import android.app.ActivityManager;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;


/**
 * Created by system-local on 19-06-2017.
 */

public abstract class BaseActivity extends Activity {

    public static String selectedTabName = "";

    private Context mContext;
    private TextView tv_favourites;
    private ImageView iv_favourites;
    private LinearLayout lv_favourites;
    private TextView tv_tournament;
    private ImageView iv_tournament;
    private LinearLayout lv_tournament;
    private TextView tv_clubs;
    private ImageView iv_clubs;
    private LinearLayout lv_clubs;
    private TextView tv_age_categories;
    private ImageView iv_age_categories;
    private LinearLayout lv_age_categories;
    private TextView tv_user_settings;
    private ImageView iv_user_settings;
    private LinearLayout lv_user_settings;
    private int resourceIdFavourites;
    private int resourceIdUserSettings;
    private int resourceIdAgeCategories;
    private int resourceIdTournament;
    private int resourceIdClubs;
    private int resourceIdFavouritesSelected;
    private int resourceIdUserSettingsSelected;
    private int resourceIdAgeCategoriesSelected;
    private int resourceIdTournamentSelected;
    private int resourceIdClubsSelected;

    protected abstract void initView();

    protected abstract void setListener();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mContext = this;
        initFooterContent();
    }

    private void initFooterContent() {

        ActivityManager.TaskDescription taskDescription = null;
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(), R.mipmap.ic_launcher);
            taskDescription = new ActivityManager.TaskDescription(mContext.getResources().getString(R.string.app_name), icon, mContext.getResources().getColor(R.color.colorPrimary));
            this.setTaskDescription(taskDescription);
        }

        tv_favourites = (TextView) findViewById(R.id.tv_favourites);
        tv_tournament = (TextView) findViewById(R.id.tv_tournament);
        tv_clubs = (TextView) findViewById(R.id.tv_clubs);
        tv_age_categories = (TextView) findViewById(R.id.tv_age_categories);
        tv_user_settings = (TextView) findViewById(R.id.tv_user_settings);
        iv_favourites = (ImageView) findViewById(R.id.iv_favourites);
        iv_age_categories = (ImageView) findViewById(R.id.iv_age_categories);
        iv_tournament = (ImageView) findViewById(R.id.iv_tournament);
        iv_user_settings = (ImageView) findViewById(R.id.iv_user_settings);
        iv_clubs = (ImageView) findViewById(R.id.iv_clubs);
        lv_age_categories = (LinearLayout) findViewById(R.id.lv_age_categories);
        lv_tournament = (LinearLayout) findViewById(R.id.lv_tournament);
        lv_clubs = (LinearLayout) findViewById(R.id.lv_clubs);
        lv_user_settings = (LinearLayout) findViewById(R.id.lv_user_settings);
        lv_favourites = (LinearLayout) findViewById(R.id.lv_favourites);

        resourceIdAgeCategories = R.drawable.age_categories;
        resourceIdAgeCategoriesSelected = R.drawable.age_categories_red;
        resourceIdClubs = R.drawable.clubs;
        resourceIdClubsSelected = R.drawable.clubs_red;
        resourceIdTournament = R.drawable.tournament;
        resourceIdTournamentSelected = R.drawable.tournament_red;
        resourceIdFavourites = R.drawable.favorites;
        resourceIdFavouritesSelected = R.drawable.favorites_red;
        resourceIdUserSettings = R.drawable.user_settings;
        resourceIdUserSettingsSelected = R.drawable.user_settings_red;

        changeBottomTabAccordingToFlag();
        setFooterClickListener();
    }

    private void setFooterClickListener() {
        FooterClickListener footerClickListener = new FooterClickListener();
//        lv_age_categories.setOnClickListener(footerClickListener);
//        lv_user_settings.setOnClickListener(footerClickListener);
//        lv_favourites.setOnClickListener(footerClickListener);
//        lv_clubs.setOnClickListener(footerClickListener);
//        lv_tournament.setOnClickListener(footerClickListener);
    }

    private void selectDiselectBottomTab(ImageView iv, TextView tv, LinearLayout lv, boolean isSelected, int imageDrawable) {
        iv.setImageResource(imageDrawable);
        if (isSelected) {
            tv.setTextColor(mContext.getResources().getColor(R.color.red));
        } else {
            tv.setTextColor(mContext.getResources().getColor(R.color.home_textview));
        }
    }

    private void changeBottomTabAccordingToFlag() {
        if (selectedTabName.equalsIgnoreCase(AppConstants.SCREEN_CONSTANT_AGE_CATEGORIES)) {
            selectDiselectBottomTab(iv_favourites, tv_favourites, lv_favourites, true, resourceIdFavouritesSelected);
            selectDiselectBottomTab(iv_tournament, tv_tournament, lv_tournament, false, resourceIdTournament);
            selectDiselectBottomTab(iv_clubs, tv_clubs, lv_clubs, false, resourceIdClubs);
            selectDiselectBottomTab(iv_age_categories, tv_age_categories, lv_age_categories, false, resourceIdAgeCategories);
            selectDiselectBottomTab(iv_user_settings, tv_user_settings, lv_user_settings, false, resourceIdUserSettings);
        }

        if (selectedTabName.equalsIgnoreCase(AppConstants.SCREEN_CONSTANT_TOURNAMENT)) {
            selectDiselectBottomTab(iv_favourites, tv_favourites, lv_favourites, false, resourceIdFavourites);
            selectDiselectBottomTab(iv_tournament, tv_tournament, lv_tournament, true, resourceIdTournamentSelected);
            selectDiselectBottomTab(iv_clubs, tv_clubs, lv_clubs, false, resourceIdClubs);
            selectDiselectBottomTab(iv_age_categories, tv_age_categories, lv_age_categories, false, resourceIdAgeCategories);
            selectDiselectBottomTab(iv_user_settings, tv_user_settings, lv_user_settings, false, resourceIdUserSettings);
        }

        if (selectedTabName.equalsIgnoreCase(AppConstants.SCREEN_CONSTANT_CLUBS)) {
            selectDiselectBottomTab(iv_favourites, tv_favourites, lv_favourites, false, resourceIdFavourites);
            selectDiselectBottomTab(iv_tournament, tv_tournament, lv_tournament, false, resourceIdTournament);
            selectDiselectBottomTab(iv_clubs, tv_clubs, lv_clubs, true, resourceIdClubsSelected);
            selectDiselectBottomTab(iv_age_categories, tv_age_categories, lv_age_categories, false, resourceIdAgeCategories);
            selectDiselectBottomTab(iv_user_settings, tv_user_settings, lv_user_settings, false, resourceIdUserSettings);
        }
        if (selectedTabName.equalsIgnoreCase(AppConstants.SCREEN_CONSTANT_AGE_CATEGORIES)) {
            selectDiselectBottomTab(iv_favourites, tv_favourites, lv_favourites, false, resourceIdFavourites);
            selectDiselectBottomTab(iv_tournament, tv_tournament, lv_tournament, false, resourceIdTournament);
            selectDiselectBottomTab(iv_clubs, tv_clubs, lv_clubs, false, resourceIdClubs);
            selectDiselectBottomTab(iv_age_categories, tv_age_categories, lv_age_categories, true, resourceIdAgeCategoriesSelected);
            selectDiselectBottomTab(iv_user_settings, tv_user_settings, lv_user_settings, false, resourceIdUserSettings);
        }
        if (selectedTabName.equalsIgnoreCase(AppConstants.SCREEN_CONSTANT_USER_SETTINGS)) {
            selectDiselectBottomTab(iv_favourites, tv_favourites, lv_favourites, false, resourceIdFavourites);
            selectDiselectBottomTab(iv_tournament, tv_tournament, lv_tournament, false, resourceIdTournament);
            selectDiselectBottomTab(iv_clubs, tv_clubs, lv_clubs, false, resourceIdClubs);
            selectDiselectBottomTab(iv_age_categories, tv_age_categories, lv_age_categories, false, resourceIdAgeCategories);
            selectDiselectBottomTab(iv_user_settings, tv_user_settings, lv_user_settings, true, resourceIdUserSettingsSelected);
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        changeBottomTabAccordingToFlag();
    }

    private class FooterClickListener implements View.OnClickListener {

        @Override
        public void onClick(View v) {
            switch(v.getId()) {
                case R.id.lv_favourites:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_FAVOURITES;
                    /*Intent mFavourites = new Intent(mContext, FavouritesActivity.class);
                    mFavourites.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mFavourites);*/
                    changeBottomTabAccordingToFlag();
                    overridePendingTransition(0, 0);
                    break;

                case R.id.lv_clubs:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;
                    Intent mClubs = new Intent(mContext, ClubsActivity.class);
                    mClubs.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mClubs);
                    changeBottomTabAccordingToFlag();
                    overridePendingTransition(0, 0);
                    break;

                case R.id.lv_age_categories:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_AGE_CATEGORIES;
                   /* Intent mAgeCategories = new Intent(mContext, AgeCategoriesActivity.class);
                    mAgeCategories.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mAgeCategories);*/
                    changeBottomTabAccordingToFlag();
                    overridePendingTransition(0, 0);
                    break;

                case R.id.lv_tournament:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_TOURNAMENT;
                    /*Intent mTournament = new Intent(mContext, TournamentActivity.class);
                    mTournament.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mTournament);*/
                    changeBottomTabAccordingToFlag();
                    overridePendingTransition(0, 0);
                    break;

                case R.id.lv_user_settings:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_USER_SETTINGS;
                    /*Intent mUserSettings = new Intent(mContext, UserSettingsActivity.class);
                    mUserSettings.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mUserSettings);*/
                    changeBottomTabAccordingToFlag();
                    overridePendingTransition(0, 0);
                    break;
            }
        }
    }
}
