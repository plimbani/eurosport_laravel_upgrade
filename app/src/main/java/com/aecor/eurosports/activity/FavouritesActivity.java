package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.BaseExpandableListAdapter;
import android.widget.ImageView;
import android.widget.ListView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.FavouriteListAdapter;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class FavouritesActivity extends BaseAppCompactActivity {
    private final String TAG = FavouritesActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.favourite_list)
    protected ListView favouriteList;
    private AppPreference mPreference;

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        getTournamentList();
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_FAVOURITES;
        setContentView(R.layout.activity_favourites);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    private void getTournamentList() {
//        Utility.startProgress(mContext);
        String url = ApiConstants.GET_TOURNAMENTS;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .GET, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
//                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Tournament List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TournamentModel mTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mTournamentList != null && mTournamentList.length > 0) {
//                                    getLoggedInUserFavouriteTournamentList(mTournamentList);
                                    checkFavourite(mTournamentList,null);
                                }
                            }
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }

    private void getLoggedInUserFavouriteTournamentList(final TournamentModel mTournamentList[]) {
        Utility.startProgress(mContext);
        String url = ApiConstants.GET_USER_FAVOURITE_LIST;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("user_id", Utility.getUserId(mContext));
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            /*JSONArray jsonArray = response.getJSONArray("data");
                            ArrayList<Integer> tournament_id_list = new ArrayList<>();
                            for(int i=0; i<jsonArray.length(); i++) {
                                JSONObject jsonObject = jsonArray.getJSONObject(i);
                                tournament_id_list.add(jsonObject.getInt("tournament_id"));
                            }*/
                            TournamentModel mFavTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                            checkFavourite(mTournamentList, mFavTournamentList);
//                            setTournamnetAdapter(mTournamentList, tournament_id_list);
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }

    private void checkFavourite(TournamentModel tournamentModels[], TournamentModel favTournamentModel[] ){
        /*ArrayList<String> tournament_id_list = new ArrayList<>();
        for(int j=0; j<favTournamentModel.length; j++) {
            for (int i = 0; i < tournamentModels.length; i++) {
                if (tournamentModels[i].getId() == favTournamentModel[j].getId()) {
                    tournament_id_list.add(tournamentModels[i].getId());
                }
            }
        }*/
        setTournamnetAdapter(tournamentModels, favTournamentModel);
    }

    private void setTournamnetAdapter(TournamentModel mTournamentList[], TournamentModel mFavTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        List<TournamentModel> favList = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        if(mFavTournamentList != null) {
            favList.addAll(Arrays.asList(mFavTournamentList));
        }
        FavouriteListAdapter adapter = new FavouriteListAdapter((Activity) mContext, list, favList);
        favouriteList.setAdapter(adapter);
    }
}
