package com.aecor.eurosports.activity;

import android.content.Intent;
import android.support.v4.app.FragmentActivity;
import android.os.Bundle;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppLogger;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

public class VenueMapActivity extends FragmentActivity implements OnMapReadyCallback {

    private GoogleMap mMap;
    private double latitude, longitude;
    private String label;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_venue_map);
        initView();
        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);
    }

    private void initView() {
        Intent intent = getIntent();
        label = intent.getStringExtra("label");
        String[] mLocation = intent.getStringExtra("latlong").split(",");
        latitude = Double.parseDouble(mLocation[0]);
        longitude = Double.parseDouble(mLocation[1]);
        AppLogger.LogE("Google maps","**** Data **** -> "+latitude+longitude+label);
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
}
