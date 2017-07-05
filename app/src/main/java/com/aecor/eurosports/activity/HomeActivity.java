package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.support.customtabs.CustomTabsIntent;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;

import com.aecor.eurosports.R;
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
import com.bumptech.glide.Glide;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.github.lzyzsd.circleprogress.DonutProgress;


import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import butterknife.BindView;
import butterknife.OnClick;

import static com.aecor.eurosports.util.AppConstants.FACEBOOK_PAGE_ID;
import static com.aecor.eurosports.util.AppConstants.FACEBOOK_URL;
import static com.aecor.eurosports.util.AppConstants.INSTAGRAM_URL;
import static com.aecor.eurosports.util.AppConstants.TWITTER_URL;

public class HomeActivity extends BaseAppCompactActivity {

    private final String TAG = "HomeActivity";
    private Context mContext;
    @BindView(R.id.sp_tournament)
    protected Spinner sp_tournament;
    private List<TournamentModel> mTournamentList;
    @BindView(R.id.tv_tournamentDate)
    protected TextView tv_tournamentDate;
    @BindView(R.id.tv_tournamentName)
    protected TextView tv_tournamentName;
    @BindView(R.id.iv_tournamentLogo)
    protected ImageView iv_tournamentLogo;
    @BindView(R.id.progress_days)
    protected DonutProgress mProgressDays;
    @BindView(R.id.progress_hours)
    protected DonutProgress mProgressHours;
    @BindView(R.id.progress_minutes)
    protected DonutProgress mProgressMinutes;
    @BindView(R.id.progress_seconds)
    protected DonutProgress mProgresSeconds;
    private AppPreference mPreference;
    private Timer timer = new Timer();
    private TimerTask timerTask;

    @Override
    public void initView() {
        mPreference = AppPreference.getInstance(mContext);
        initProgressView();
        getLoggedInUserFavouriteTournamentList();
        setListener();
    }

    @Override
    public void setListener() {
        sp_tournament.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (mTournamentList != null && mTournamentList.get(position) != null && !Utility.isNullOrEmpty(mTournamentList.get(position).getName())) {
                    mPreference.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, mTournamentList.get(position).getTournament_id());
                    tv_tournamentName.setText(mTournamentList.get(position).getName());
                }
                if (mTournamentList != null && mTournamentList.get(position) != null && !Utility.isNullOrEmpty(mTournamentList.get(position).getTournamentLogo())) {

                    Glide.with(mContext)
                            .load(mTournamentList.get(position).getTournamentLogo())
                            .asBitmap()
                            .into(new SimpleTarget<Bitmap>() {
                                @Override
                                public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                                    iv_tournamentLogo.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH_LARGE, AppConstants.MAX_IMAGE_HEIGHT_LARGE));
                                }
                            });


                } else {

                    iv_tournamentLogo.setImageResource(R.drawable.globe);
                }

                if (mTournamentList != null && mTournamentList.get(position) != null && !Utility.isNullOrEmpty(mTournamentList.get(position).getStart_date()) && !Utility.isNullOrEmpty(mTournamentList.get(position).getEnd_date())) {
                    tv_tournamentDate.setText(Utility.getFormattedTournamentDate(mTournamentList.get(position).getStart_date(), mTournamentList.get(position).getEnd_date()));
                    if (timer != null) {
                        timer.cancel();
                        timer = new Timer();
                    }
                    initProgressView();
                    startTimeUpdateHandler(mTournamentList.get(position).getStart_date());
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> arg0) {

            }
        });
    }

    private void initProgressView() {


        mProgresSeconds.setProgress(60);
        mProgressHours.setProgress(24);
        mProgressMinutes.setProgress(60);
        mProgressDays.setProgress(30);

        mProgresSeconds.setText(getString(R.string.progres_text_0));
        mProgressHours.setText(getString(R.string.progres_text_0));
        mProgressMinutes.setText(getString(R.string.progres_text_0));
        mProgressDays.setText(getString(R.string.progres_text_0));
    }

    private void startTimeUpdateHandler(final String startDate) {
        timerTask = new TimerTask() {
            @Override
            public void run() {
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        getDateDifference(startDate);
                    }
                });

            }
        };
        timer.schedule(timerTask, 0, 1000);


    }

    @Override
    protected void onResume() {
        super.onResume();
        if (timer != null)
            timer.cancel();
    }

    public void getDateDifference(String FutureDate) {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

        try {
            Date currentDate = dateFormat.parse(FutureDate);
            System.out.println(currentDate);
            Date oldDate = new Date();

            long diff = currentDate.getTime() - oldDate.getTime();

            long days = diff / (24 * 60 * 60 * 1000);
            diff -= days * (24 * 60 * 60 * 1000);

            long hours = diff / (60 * 60 * 1000);
            diff -= hours * (60 * 60 * 1000);

            long minutes = diff / (60 * 1000);
            diff -= minutes * (60 * 1000);

            long seconds = diff / 1000;

            if (days > 0) {
                mProgressDays.setText(days + "");
                mProgressDays.setProgress(days);
            }
            if (minutes > 0) {
                mProgressMinutes.setText(minutes + "");
                mProgressMinutes.setProgress(minutes);
            }
            if (hours > 0) {
                mProgressHours.setText(hours + "");
                mProgressHours.setProgress(hours);
            }
            if (seconds > 0) {
                mProgresSeconds.setText(seconds + "");
                mProgresSeconds.setProgress(seconds);
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_TOURNAMENT;
        setContentView(R.layout.activity_home);
        super.onCreate(savedInstanceState);
        mContext = this;
        initView();
    }

    @OnClick(R.id.facebook)
    protected void open_facebook() {
        PackageManager packageManager = mContext.getPackageManager();
        String facebookUrl;
        try {
            int versionCode = packageManager.getPackageInfo("com.facebook.katana", 0).versionCode;
            if (versionCode >= 3002850) {
                //newer versions of fb app
                facebookUrl = "fb://facewebmodal/f?href=" + FACEBOOK_URL;
            } else {
                //older versions of fb app
                facebookUrl = "fb://page/" + FACEBOOK_PAGE_ID;
            }
            Intent facebookIntent = new Intent(Intent.ACTION_VIEW);
            facebookIntent.setData(Uri.parse(facebookUrl));
            startActivity(facebookIntent);
        } catch (PackageManager.NameNotFoundException e) {
            //normal web url
            facebookUrl = FACEBOOK_URL;
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(facebookUrl));
        }

    }

    @OnClick(R.id.instagram)
    protected void open_instagram() {
        Uri uri = Uri.parse(AppConstants.INSTAGRAM_URL);
        Intent likeIng = new Intent(Intent.ACTION_VIEW, uri);

        likeIng.setPackage("com.instagram.android");

        try {
            startActivity(likeIng);
        } catch (ActivityNotFoundException e) {
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(AppConstants.INSTAGRAM_URL));
        }
    }

    @OnClick(R.id.twitter)
    protected void open_twitter() {
        Intent twitter = null;
        try {
            // get the Twitter app if possible
            mContext.getPackageManager().getPackageInfo("com.twitter.android", 0);
            twitter = new Intent(Intent.ACTION_VIEW, Uri.parse(AppConstants.TWITTER_URL));
            twitter.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            mContext.startActivity(twitter);
        } catch (Exception e) {
            // no Twitter app, revert to browser
            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(TWITTER_URL));
        }

    }


    private void getLoggedInUserFavouriteTournamentList() {

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

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {

                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TournamentModel mTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mTournamentList != null && mTournamentList.length > 0) {
                                    setTournamnetSpinnerAdapter(mTournamentList);
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

    private void setTournamnetSpinnerAdapter(TournamentModel mTournamentList[]) {

        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        int selectedTournamentPos = 0;
        for (int i = 0; i < list.size(); i++) {
            if (list.get(i).getIs_default() == 1) {
                selectedTournamentPos = i;
                break;
            }
        }
        this.mTournamentList = list;
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                R.layout.row_spinner_item, R.id.title, list);
        sp_tournament.setAdapter(adapter);
        sp_tournament.setSelection(selectedTournamentPos);
    }
}

