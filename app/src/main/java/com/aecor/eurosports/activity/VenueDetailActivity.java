package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.customtabs.CustomTabsIntent;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by system-local on 03-07-2017.
 */

public class VenueDetailActivity extends BaseAppCompactActivity {
    private static final String TAG = "VenueDetailActivity";
    private Context mContext;
    private TeamFixturesModel mTeamFixturesModel;
    @BindView(R.id.tv_location_name)
    protected TextView tv_location_name;
    @BindView(R.id.tv_pitch_name)
    protected TextView tv_pitch_name;
    @BindView(R.id.tv_address)
    protected TextView tv_address;
    @BindView(R.id.tv_playing_surface)
    protected TextView tv_playing_surface;
    @BindView(R.id.ll_view_on_map)
    protected LinearLayout ll_view_on_map;

    @OnClick(R.id.ll_view_on_map)
    protected void onViewOnMapClicked() {

        String[] mLocation = mTeamFixturesModel.getVenueCoordinates().split(",");


        double latitude = Double.parseDouble(mLocation[0]);
        double longitude = Double.parseDouble(mLocation[1]);
        String label = mTeamFixturesModel.getVenue_name();
        String uriBegin = "geo:" + latitude + "," + longitude;
        String query = latitude + "," + longitude + "(" + label + ")";
        String encodedQuery = Uri.encode(query);
        String uriString = uriBegin + "?q=" + encodedQuery + "&z=16";
        Uri uri = Uri.parse(uriString);
        if (Utility.isPackageInstalled(mContext, "com.google.android.gms.maps")) {
            Intent intent = new Intent(android.content.Intent.ACTION_VIEW, uri);
            startActivity(intent);
        } else {
            String mapUrl = "http://maps.google.com/maps?q=" + query;
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(mapUrl));
        }
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

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenue_name())) {
            tv_location_name.setText(mTeamFixturesModel.getVenue_name());
        } else {
            tv_location_name.setText(getString(R.string.na));
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getPitch_number())) {
            tv_pitch_name.setText(mTeamFixturesModel.getPitch_number());
        } else {
            tv_pitch_name.setText(getString(R.string.na));
        }

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getPitchType())) {
            String pitchType = mTeamFixturesModel.getPitchType();
            pitchType = pitchType.substring(0, 1).toUpperCase() + pitchType.substring(1);
            tv_playing_surface.setText(pitchType);
        } else {
            tv_playing_surface.setText(getString(R.string.na));
        }

        String address = "";
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueaddress())) {
            address = mTeamFixturesModel.getVenueaddress();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueCity())) {
            address = address + ", " + mTeamFixturesModel.getVenueCity();
        }

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueCountry())) {
            address = address + ", " + mTeamFixturesModel.getVenueCountry();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenuePostcode())) {
            address = address + ", " + mTeamFixturesModel.getVenuePostcode();
        }
        if (!Utility.isNullOrEmpty(address)) {
            tv_address.setText(address);
        } else {
            tv_address.setText(getString(R.string.na));
        }

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenueCoordinates())) {
            ll_view_on_map.setVisibility(View.VISIBLE);

        } else {
            ll_view_on_map.setVisibility(View.GONE);
        }
    }


    @Override
    protected void setListener() {

    }
}
