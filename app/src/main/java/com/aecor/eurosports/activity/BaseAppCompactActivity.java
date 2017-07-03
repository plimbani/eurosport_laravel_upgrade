package com.aecor.eurosports.activity;

import android.app.ActivityManager;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by karan on 6/19/2017.
 */

public abstract class BaseAppCompactActivity extends AppCompatActivity {

    public static String selectedTabName = "";
    @BindView(R.id.tv_tournament)
    protected TextView tv_tournament;
    @BindView(R.id.tv_clubs)
    protected TextView tv_clubs;
    @BindView(R.id.tv_age_categories)
    protected TextView tv_age_categories;
    @BindView(R.id.tv_user_settings)
    protected TextView tv_user_settings;
    @BindView(R.id.iv_favourites)
    protected ImageView iv_favourites;
    @BindView(R.id.iv_age_categories)
    protected ImageView iv_age_categories;
    @BindView(R.id.iv_tournament)
    protected ImageView iv_tournament;
    @BindView(R.id.iv_user_settings)
    protected ImageView iv_user_settings;
    @BindView(R.id.iv_clubs)
    protected ImageView iv_clubs;
    @BindView(R.id.lv_age_categories)
    protected LinearLayout lv_age_categories;
    @BindView(R.id.lv_tournament)
    protected LinearLayout lv_tournament;
    @BindView(R.id.lv_clubs)
    protected LinearLayout lv_clubs;
    @BindView(R.id.lv_user_settings)
    protected LinearLayout lv_user_settings;
    @BindView(R.id.lv_favourites)
    protected LinearLayout lv_favourites;


    private Context mContext;
    @BindView(R.id.tv_favourites)
    protected TextView tv_favourites;
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
        ButterKnife.bind(this);
        initFooterContent();
    }

    private void initFooterContent() {

        ActivityManager.TaskDescription taskDescription = null;
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(), R.mipmap.ic_launcher);
            taskDescription = new ActivityManager.TaskDescription(mContext.getResources().getString(R.string.app_name), icon, mContext.getResources().getColor(R.color.colorPrimary));
            this.setTaskDescription(taskDescription);
        }

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
        lv_age_categories.setOnClickListener(footerClickListener);
        lv_user_settings.setOnClickListener(footerClickListener);
        lv_favourites.setOnClickListener(footerClickListener);
        lv_clubs.setOnClickListener(footerClickListener);
        lv_tournament.setOnClickListener(footerClickListener);
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
        if (selectedTabName.equalsIgnoreCase(AppConstants.SCREEN_CONSTANT_FAVOURITES)) {
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
            switch (v.getId()) {
                case R.id.lv_favourites:

                    selectedTabName = AppConstants.SCREEN_CONSTANT_FAVOURITES;
                    Intent mFavourites = new Intent(mContext, FavouritesActivity.class);
                    mFavourites.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mFavourites);
                    changeBottomTabAccordingToFlag();
                    break;

                case R.id.lv_clubs:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;
                    Intent mClubs = new Intent(mContext, ClubsActivity.class);
                    mClubs.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mClubs);
                    changeBottomTabAccordingToFlag();
                    break;

                case R.id.lv_age_categories:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_AGE_CATEGORIES;
                    Intent mAgeCategories = new Intent(mContext, AgeCategoriesActivity.class);
                    mAgeCategories.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mAgeCategories);
                    changeBottomTabAccordingToFlag();
                    break;

                case R.id.lv_tournament:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_TOURNAMENT;
                    Intent mTournament = new Intent(mContext, HomeActivity.class);
                    mTournament.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(mTournament);
                    changeBottomTabAccordingToFlag();
                    break;

                case R.id.lv_user_settings:
                    selectedTabName = AppConstants.SCREEN_CONSTANT_USER_SETTINGS;
                    Intent mSettingsIntent = new Intent(mContext, SettingsActivity.class);
                    startActivity(mSettingsIntent);
                    changeBottomTabAccordingToFlag();
                    break;
            }
        }
    }

    protected void showBackButton(String title) {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(title.toUpperCase());
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeAsUpIndicator(R.drawable.left_arrow_white);
        toolbar.setTitleTextColor(Color.WHITE);

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // app icon in action bar clicked; go home
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }
}
