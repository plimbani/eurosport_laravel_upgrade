package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.aecor.eurosports.R;
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
import com.squareup.picasso.Picasso;

import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by karan on 6/22/2017.
 */

public class FavouriteListAdapter extends BaseAdapter {
    private final String TAG = FavouriteListAdapter.class.getSimpleName();
    private LayoutInflater inflater;
    private ArrayList<Integer> mFavList;
    private Context mContext;
    private List<TournamentModel> mTournamentList;
    private AppPreference mPreference;

    public FavouriteListAdapter(Activity context, List<TournamentModel> list) {
        mContext = context;
        this.mTournamentList = list;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        mFavList = new ArrayList<>();
        mPreference = AppPreference.getInstance(mContext);
    }

    @Override
    public int getCount() {
        return mTournamentList.size();
    }

    @Override
    public TournamentModel getItem(int i) {
        return mTournamentList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return rowView(convertView, position);
    }

    private View rowView(View convertView, final int position) {
        AppLogger.LogE(TAG, "pos" + position);
        final ViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            rowview = inflater.inflate(R.layout.layout_favourite_textview, null);
            holder = new ViewHolder(rowview);
            rowview.setTag(holder);
        } else {
            holder = (ViewHolder) rowview.getTag();
        }
        TournamentModel rowItem = getItem(position);
        if (!Utility.isNullOrEmpty(rowItem.getName())) {
            holder.favourite_tournament.setText(rowItem.getName());
        }

        if (!Utility.isNullOrEmpty(rowItem.getStart_date()) && !Utility.isNullOrEmpty(rowItem.getEnd_date())) {
            holder.favourite_date.setText(rowItem.getStart_date() + " - " + rowItem.getEnd_date());
        }

        if (mFavList.contains(position)) {
            holder.favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.heart_red));
        } else {
            holder.favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.heart_gray));
        }
        if (!Utility.isNullOrEmpty(rowItem.getLogo())) {
            Picasso.with(mContext).load(rowItem.getLogo()).fit().centerCrop()
                    .into(holder.favourite_logo);
        }

        holder.favourite_imageview.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (!mFavList.contains(position)) {
                    holder.favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.heart_red));
                    mFavList.add(position);
                    makeTournamenetFavourite(mTournamentList.get(position).getId());
                } else {
                    holder.favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.heart_gray));
                    mFavList.remove(position);
                    removeTournamenetFromFavourite(mTournamentList.get(position).getId());
                }
            }
        });

        holder.default_imageview.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (holder.default_imageview.getDrawable().getConstantState().equals
                        (mContext.getResources().getDrawable(R.drawable.default_tournament).getConstantState())) {
                    holder.default_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.selected_default_tournament));
                    mPreference.setInt(AppConstants.PREF_TOURNAMENT_ID, position);
                    setDefaultTournament(mTournamentList.get(position).getId());
                }
                else
                    holder.default_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.default_tournament));
            }
        });

        return rowview;
    }

    private void setDefaultTournament(String tournamentId) {
        Utility.startProgress(mContext);
        String url = ApiConstants.GET_USER_DEFAULT_FAVOURITE_LIST;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("user_id", Utility.getUserId(mContext));
            requestJson.put("tournament_id", tournamentId);
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            AppLogger.LogE(TAG, "*** SET DEFAULT TOURNAMENT REQUEST ***" + requestJson.toString());
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "*** SET DEFAULT TOURNAMENT RESPONSE ***" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                String messgae = response.getString("message");
                                Utility.showToast(mContext, messgae);
                            } else {
                                Utility.showToast(mContext, mContext.getResources().getString(R.string.default_tournament));
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
            }, mPreference.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest);
        }
    }

    private void makeTournamenetFavourite(String tournamenetId) {
        Utility.startProgress(mContext);
        String url = ApiConstants.SET_TOURNAMENT_AS_FAVOURITE;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("user_id", Utility.getUserId(mContext));
            requestJson.put("tournament_id", tournamenetId);
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
                        AppLogger.LogE(TAG, "Set Tournament as Favourite" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {

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
            }, mPreference.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest);
        }
    }

    private void removeTournamenetFromFavourite(String tournamenetId) {
        Utility.startProgress(mContext);
        String url = ApiConstants.REMOVE_TOURNAMENT_FROM_FAVOURITE;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("user_id", Utility.getUserId(mContext));
            requestJson.put("tournament_id", tournamenetId);
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
                        AppLogger.LogE(TAG, "Remove Tournamenet as Favourite" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {

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
            }, mPreference.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest);
        }
    }

    protected class ViewHolder {
        @BindView(R.id.favourite_logo)
        protected ImageView favourite_logo;
        @BindView(R.id.favourite_imageview)
        protected ImageView favourite_imageview;
        @BindView(R.id.favourite_tournament)
        protected TextView favourite_tournament;
        @BindView(R.id.favourite_date)
        protected TextView favourite_date;
        @BindView(R.id.default_imageview)
        protected ImageView default_imageview;

        public ViewHolder(View rowView) {
            ButterKnife.bind(this, rowView);
        }
    }
}