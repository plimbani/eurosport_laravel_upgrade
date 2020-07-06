package com.aecor.eurosports.activity;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import com.aecor.eurosports.R;
import com.aecor.eurosports.application.ApplicationClass;
import com.aecor.eurosports.util.ConnectivityChangeReceiver;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import butterknife.BindView;
import butterknife.ButterKnife;

public class VenueMapActivity extends AppCompatActivity implements OnMapReadyCallback, ConnectivityChangeReceiver.ConnectivityReceiverListener {

    private GoogleMap mMap;
    private double latitude, longitude;
    private String label;
    @BindView(R.id.tv_no_internet)
    protected TextView tv_no_internet;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_venue_map);
        ButterKnife.bind(this);
        initView();
        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);
    }

    protected void showBackButton(String title) {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        toolbar.setTitleTextColor(Color.WHITE);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setTitle(title.toUpperCase());
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setHomeAsUpIndicator(R.drawable.left_arrow_white);
        }
    }

    protected void initView() {
        showBackButton(getString(R.string.title_activity_venue_map));
        Intent intent = getIntent();
        label = intent.getStringExtra("label");
        String[] mLocation = intent.getStringExtra("latlong").split(",");
        latitude = Double.parseDouble(mLocation[0]);
        longitude = Double.parseDouble(mLocation[1]);
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

    /**
     * Manipulates the map once available.
     * This callback is triggered when the map is ready to be used.
     * This is where we can add markers or lines, add listeners or move the camera. In this case,
     * we just add a marker near Sydney, Australia.
     * If Google Play services is not installed on the device, the user will be prompted to install
     * it inside the SupportMapFragment. This method will only be triggered once the user has
     * installed Google Play services and returned to the app.
     */
    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;
        // Add a marker in Sydney and move the camera
        LatLng venue = new LatLng(latitude, longitude);
        mMap.addMarker(new MarkerOptions().position(venue).title(label).alpha(0.8f).flat(true));
        mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(venue, 19));
        mMap.animateCamera(CameraUpdateFactory.zoomTo(19), 1000, null);
    }

    @Override
    protected void onResume() {
        super.onResume();
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
