package com.aecor.eurosports.adapter;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;

import com.aecor.eurosports.R;
import com.aecor.eurosports.fragment.ClubsListFragment;
import com.aecor.eurosports.util.AppConstants;

/**
 * Created by karan on 6/19/2017.
 */

public class ClubSectionsPagerAdapter extends FragmentStatePagerAdapter {
    private String[] pageTitles;

    public ClubSectionsPagerAdapter(Context mContext, FragmentManager fm) {
        super(fm);
        pageTitles = mContext.getResources().getStringArray(R.array.clubs_tabs);

    }

    @Override
    public Fragment getItem(int position) {
        ClubsListFragment fragment = new ClubsListFragment();
        Bundle args = new Bundle();
        args.putInt(AppConstants.ARG_SECTION_NAME, position);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public int getCount() {
        return 3;
    }

    @Override
    public CharSequence getPageTitle(int position) {
        return pageTitles[position];
    }

}
