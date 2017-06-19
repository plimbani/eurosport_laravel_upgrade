package com.aecor.eurosports.activity;

import android.content.Context;
import android.net.Uri;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.Toolbar;

import android.support.v4.view.ViewPager;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.ClubSectionsPagerAdapter;
import com.aecor.eurosports.fragment.ClubsListFragment;

import butterknife.BindView;
import butterknife.ButterKnife;


public class ClubsActivity extends BaseAppCompactActivity implements ClubsListFragment.OnFragmentInteractionListener {


    private ClubSectionsPagerAdapter mSectionsPagerAdapter;
    private Context mContext;
    @BindView(R.id.container)
    protected ViewPager mViewPager;
    @BindView(R.id.tabs)
    protected TabLayout tabLayout;

    @Override
    public void initView() {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        mSectionsPagerAdapter = new ClubSectionsPagerAdapter(getSupportFragmentManager());
        mViewPager.setAdapter(mSectionsPagerAdapter);
        tabLayout.setupWithViewPager(mViewPager);
        setListener();
    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_clubs);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onFragmentInteraction(Uri uri) {

    }
}
