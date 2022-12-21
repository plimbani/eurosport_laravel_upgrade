package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by karan on 6/22/2017.
 */

public class EasyMatchManagerFavAdapter extends BaseAdapter {
    private final String TAG = EasyMatchManagerFavAdapter.class.getSimpleName();
    private OnFavRowClick onFavRowClick;
    private LayoutInflater inflater;
    private Context mContext;
    private List<TournamentModel> mFavTournamentList;
    private AppPreference mPreference;

    public EasyMatchManagerFavAdapter(Activity context, List<TournamentModel> favlist, OnFavRowClick onFavRowClick) {
        mContext = context;
        this.onFavRowClick = onFavRowClick;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        mFavTournamentList = favlist;
        mPreference = AppPreference.getInstance(mContext);
    }

    @Override
    public int getCount() {
        return mFavTournamentList.size();
    }

    @Override
    public TournamentModel getItem(int i) {
        return mFavTournamentList.get(i);
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
        final BaseViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            if (BuildConfig.isEasyMatchManager) {
                rowview = inflater.inflate(R.layout.layout_favourrite_textview_commer, null);
                holder = new ViewHolderCommerci(rowview);
                rowview.setTag(holder);
            } else {
                rowview = inflater.inflate(R.layout.layout_favourite_textview, null);
                holder = new ViewHolder(rowview);
                rowview.setTag(holder);
            }
        } else {
            if (BuildConfig.isEasyMatchManager) {
                holder = (ViewHolderCommerci) rowview.getTag();
            } else {
                holder = (ViewHolder) rowview.getTag();
            }
        }
        final TournamentModel rowItem = getItem(position);
        if (BuildConfig.isEasyMatchManager) {

            if (!Utility.isNullOrEmpty(rowItem.getName())) {
                ((ViewHolderCommerci) holder).tv_tournamentName.setText(rowItem.getName());
            }
            if (!Utility.isNullOrEmpty(rowItem.getName()) && checkDefault(rowItem)) {
                ((ViewHolderCommerci) holder).iv_is_default.setImageResource(R.drawable.fav_add);
            } else {
                ((ViewHolderCommerci) holder).iv_is_default.setImageResource(R.drawable.fav_default);
            }


            if (!Utility.isNullOrEmpty(rowItem.getTournamentLogo())) {
                Glide.with(mContext)
                        .load(rowItem.getTournamentLogo())
                        .diskCacheStrategy(DiskCacheStrategy.NONE)
                        .skipMemoryCache(true)
                        .dontAnimate()
                        .error(R.drawable.globe)
                        .override(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT)
                        .into(((ViewHolderCommerci) holder).iv_tournament_logo);
            } else {
                ((ViewHolderCommerci) holder).iv_tournament_logo.setImageResource(R.drawable.globe);
            }
            rowview.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (mPreference.getString(AppConstants.PREF_TOURNAMENT_ID) == null || !mPreference.getString(AppConstants.PREF_TOURNAMENT_ID).equalsIgnoreCase(rowItem.getTournament_id()))
                        onFavRowClick.onFavRowClick(rowItem.getAccess_code());
                }
            });
        } else {

            if (!Utility.isNullOrEmpty(rowItem.getName())) {
                ((ViewHolder) holder).favourite_tournament.setText(rowItem.getName());
            }

            if (!Utility.isNullOrEmpty(rowItem.getStart_date()) && !Utility.isNullOrEmpty(rowItem.getEnd_date())) {
                ((ViewHolder) holder).favourite_date.setText(rowItem.getStart_date() + " - " + rowItem.getEnd_date());
            }

            if (checkFav(rowItem.getId())) {
                ((ViewHolder) holder).favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.fav_add));
            } else {
                ((ViewHolder) holder).favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.fav_default));
            }

            if (!Utility.isNullOrEmpty(rowItem.getTournamentLogo())) {
                Glide.with(mContext)
                        .load(rowItem.getTournamentLogo())
                        .diskCacheStrategy(DiskCacheStrategy.NONE)
                        .skipMemoryCache(true)
                        .dontAnimate()
                        .error(R.drawable.globe)
                        .override(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT)
                        .into(((ViewHolder) holder).favourite_logo);
            } else {
                ((ViewHolder) holder).favourite_logo.setImageResource(R.drawable.globe);
            }

            if (!Utility.isNullOrEmpty(rowItem.getName()) && checkDefault(rowItem)) {
                ((ViewHolder) holder).default_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.selected_default_tournament));
                ((ViewHolder) holder).favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.fav_add));
                ((ViewHolder) holder).default_imageview.setEnabled(false);
            } else {
                ((ViewHolder) holder).default_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.default_tournament));
                ((ViewHolder) holder).default_imageview.setEnabled(true);
                ((ViewHolder) holder).favourite_imageview.setEnabled(true);
            }

            ((ViewHolder) holder).favourite_imageview.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (checkDefault(rowItem)) {
                        Utility.showToast(mContext, mContext.getString(R.string.deafult_tournament_cannot_be_removed_from_favourite));
                    } else {
                        if (!checkFav(rowItem.getId())) {
                            ((ViewHolder) holder).favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.fav_add));
                            makeTournamenetFavourite(mFavTournamentList.get(position));
                        } else {
                            ((ViewHolder) holder).favourite_imageview.setImageDrawable(mContext.getResources().getDrawable(R.drawable.fav_default));
                            removeTournamenetFromFavourite(mFavTournamentList.get(position));
                        }
                    }
                }
            });

            ((ViewHolder) holder).default_imageview.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (!checkDefault(rowItem)) {
                        setDefaultTournament(rowItem.getId());
                    }
                }
            });
        }
        return rowview;
    }

    private boolean checkFav(String tournamentId) {
        for (int i = 0; i < mFavTournamentList.size(); i++) {
            if (mFavTournamentList.get(i).getTournament_id().equalsIgnoreCase(tournamentId)) {
                return true;
            }
        }
        return false;
    }

    private boolean checkDefault(TournamentModel tournamentModal) {
        return tournamentModal.getTournament_id().equalsIgnoreCase(mPreference.getString(AppConstants.PREF_TOURNAMENT_ID));
    }

    public void updateList(List<TournamentModel> favList) {
        mFavTournamentList = new ArrayList<>();
        mFavTournamentList = favList;
        notifyDataSetChanged();
    }

    protected class BaseViewHolder {

    }

    protected class ViewHolder extends BaseViewHolder {
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

    protected class ViewHolderCommerci extends BaseViewHolder {
        @BindView(R.id.tv_tournamentName)
        protected TextView tv_tournamentName;
        @BindView(R.id.iv_is_default)
        protected ImageView iv_is_default;
        @BindView(R.id.iv_tournament_logo)
        protected ImageView iv_tournament_logo;

        public ViewHolderCommerci(View rowView) {
            ButterKnife.bind(this, rowView);
        }
    }


    private void removeTournamenetFromFavourite(TournamentModel tournamenetModel) {

        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.REMOVE_TOURNAMENT_FROM_FAVOURITE;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
                requestJson.put("tournament_id", tournamenetModel.getId());
            } catch (Exception e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            AppLogger.LogE(TAG, "Request as Remove Favourite" + requestJson.toString());
            AppLogger.LogE(TAG, "URL as RemoveFavourite" + url);
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Remove Tournamenet as Favourite" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            updateLoggedInUserFavouriteList();
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

    private void setDefaultTournament(final String tournamentId) {

        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.SET_DEFAULT_TOURNAMENET;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
                requestJson.put("tournament_id", tournamentId);
            } catch (Exception e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            AppLogger.LogE(TAG, "*** SET DEFAULT TOURNAMENT REQUEST ***" + requestJson.toString());
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
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


                            mPreference.setString(AppConstants.PREF_TOURNAMENT_ID, tournamentId);

                            notifyDataSetChanged();
                            updateLoggedInUserFavouriteList();
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

    private void makeTournamenetFavourite(TournamentModel tournamenetModal) {

        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.SET_TOURNAMENT_AS_FAVOURITE;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
                requestJson.put("tournament_id", tournamenetModal.getId());
            } catch (Exception e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            AppLogger.LogE(TAG, "Request as Make Favourite" + requestJson.toString());
            AppLogger.LogE(TAG, "URL as Make Favourite" + url);
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Set Tournament as Favourite" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            updateLoggedInUserFavouriteList();
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


    private void updateLoggedInUserFavouriteList() {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressDialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_USER_FAVOURITE_LIST;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
            } catch (Exception e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressDialog);
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            TournamentModel mTempFavTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                            if (mTempFavTournamentList != null && mTempFavTournamentList.length > 0) {
                                mFavTournamentList = new ArrayList<>();
                                mFavTournamentList.addAll(Arrays.asList(mTempFavTournamentList));
                                notifyDataSetChanged();
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
                        Utility.StopProgress(mProgressDialog);
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }

    @Override
    public int getItemViewType(int position) {
        return position;
    }

    public interface OnFavRowClick {
        void onFavRowClick(String access_code);
    }
}
