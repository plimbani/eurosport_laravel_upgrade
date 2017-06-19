package com.aecor.eurosports.fragment;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.aecor.eurosports.R;

/**
 * Created by karan on 6/19/2017.
 */

public class ClubPlaceHolderFragment extends Fragment {

    private static final String ARG_SECTION_NAME = "tab_name";

    public ClubPlaceHolderFragment() {
    }

    /**
     * Returns a new instance of this fragment for the given section
     * number.
     */
    public static ClubPlaceHolderFragment newInstance(int sectionName) {
        ClubPlaceHolderFragment fragment = new ClubPlaceHolderFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_SECTION_NAME, sectionName);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_clubs, container, false);
        getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.list_view_frame, new ClubsListFragment()).commit();
        return rootView;
    }

}
