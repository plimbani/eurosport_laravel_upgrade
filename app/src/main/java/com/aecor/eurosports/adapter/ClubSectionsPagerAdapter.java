package com.aecor.eurosports.adapter;

import android.content.Context;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentStatePagerAdapter;

import com.aecor.eurosports.R;
import com.aecor.eurosports.fragment.ClubsAgeFragment;
import com.aecor.eurosports.fragment.ClubsClubFragment;
import com.aecor.eurosports.fragment.ClubsGroupFragment;

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
        Fragment mSelectedFragment = null;
        switch (position) {
            case 0:
                mSelectedFragment = new ClubsClubFragment();
                break;
            case 1:
                mSelectedFragment = new ClubsAgeFragment();
                break;
            case 2:
                mSelectedFragment = new ClubsGroupFragment();
                break;
        }
        return mSelectedFragment;
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
