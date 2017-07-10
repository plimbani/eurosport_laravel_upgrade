package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;

import java.util.Locale;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by system-local on 03-07-2017.
 */

public class VenueDetailActivity extends BaseAppCompactActivity {
    private Context mContext;
    private TeamFixturesModel mTeamFixturesModel;
    @BindView(R.id.tv_pitch_name)
    protected TextView tv_pitch_name;
    @BindView(R.id.tv_address)
    protected TextView tv_address;
    @BindView(R.id.tv_playing_surface)
    protected TextView tv_playing_surface;

    @OnClick(R.id.ll_view_on_map)
    protected void onViewOnMapClicked() {
        String uri = String.format(Locale.ENGLISH, "geo:%f,%f", mTeamFixturesModel.getVenueCoordinates());
        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(uri));
        startActivity(intent);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.venue_detail);
        super.onCreate(savedInstanceState);
        mTeamFixturesModel = getIntent().getParcelableExtra(AppConstants.ARG_MATCH_INFO);
        mContext = this;
        initView();
    }


    @Override
    protected void initView() {
        showBackButton(getString(R.string.venue));
        tv_pitch_name.setText(mTeamFixturesModel.getVenue_name());
        tv_playing_surface.setText(mTeamFixturesModel.getPitchType());
        String address = "";
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueaddress())) {
            address = mTeamFixturesModel.getVenueaddress();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueCity())) {
            address = address + ", " + mTeamFixturesModel.getVenueCity();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueCounty())) {
            address = address + ", " + mTeamFixturesModel.getVenueCounty();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueCountry())) {
            address = address + ", " + mTeamFixturesModel.getVenueCountry();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenuePostcode())) {
            address = address + ", " + mTeamFixturesModel.getVenuePostcode();
        }
        tv_address.setText(address);
    }


    @Override
    protected void setListener() {

    }
}
