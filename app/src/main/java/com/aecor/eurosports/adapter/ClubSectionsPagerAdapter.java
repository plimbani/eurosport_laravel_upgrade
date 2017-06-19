package com.aecor.eurosports.adapter;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;

import com.aecor.eurosports.fragment.ClubPlaceHolderFragment;

/**
 * Created by karan on 6/19/2017.
 */

public class ClubSectionsPagerAdapter extends FragmentPagerAdapter {

    public ClubSectionsPagerAdapter(FragmentManager fm) {
        super(fm);
    }

    @Override
    public Fragment getItem(int position) {
        return ClubPlaceHolderFragment.newInstance(position+1);
    }

    @Override
    public int getCount() {
        return 3;
    }

    @Override
    public CharSequence getPageTitle(int position) {
        switch (position) {
            case 0:
                return "CLUB";
            case 1:
                return "AGE";
            case 2:
                return "GROUPS";
        }
        return null;
    }

}
