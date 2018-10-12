package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
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
import com.aecor.eurosports.ui.ViewDialog;
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
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.github.lzyzsd.circleprogress.DonutProgress;

import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.Timer;
import java.util.TimerTask;

import butterknife.BindView;
import butterknife.OnClick;

import static com.aecor.eurosports.util.AppConstants.FACEBOOK_PAGE_ID;
import static com.aecor.eurosports.util.AppConstants.FACEBOOK_URL;

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
    @BindView(R.id.tv_progress_days)
    protected TextView tv_progress_days;
    @BindView(R.id.tv_progress_minutes)
    protected TextView tv_progress_minutes;
    @BindView(R.id.tv_progress_hours)
    protected TextView tv_progress_hours;
    @BindView(R.id.tv_progress_seconds)
    protected TextView tv_progress_seconds;
    private AppPreference mPreference;
    private Timer timer = new Timer();
    private TimerTask timerTask;
    private int tournamentPosition;

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
                    tournamentPosition = position;
                    AppLogger.LogE(TAG, "Tournament Position -> " + tournamentPosition);
                    mPreference.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, mTournamentList.get(position).getTournament_id());
                    if (!Utility.isNullOrEmpty(mTournamentList.get(position).getName())) {
                        tv_tournamentName.setText(mTournamentList.get(position).getName().replace(" ", "\n"));
                    } else {
                        tv_tournamentName.setText("");
                    }
                }
                if (mTournamentList != null && mTournamentList.get(position) != null && !Utility.isNullOrEmpty(mTournamentList.get(position).getTournamentLogo())) {
                    Glide.with(mContext)
                            .load(mTournamentList.get(position).getTournamentLogo())
                            .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                            .skipMemoryCache(true)
                            .into(new SimpleTarget<Bitmap>() {
                                @Override
                                public void onLoadFailed(Exception e, Drawable errorDrawable) {
                                    super.onLoadFailed(e, errorDrawable);
                                    iv_tournamentLogo.setImageResource(R.drawable.globe);
                                }

                                @Override
                                public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                                    iv_tournamentLogo.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH_LARGE, AppConstants.MAX_IMAGE_HEIGHT_LARGE));
                                }
                            });
                } else {
                    iv_tournamentLogo.setImageResource(R.drawable.globe);
                }

                if (mTournamentList != null && mTournamentList.get(position) != null && !Utility.isNullOrEmpty(mTournamentList.get(position).getTournamentStartTime()) && !Utility.isNullOrEmpty(mTournamentList.get(position).getEnd_date())) {
                    String language = mPreference.getString(AppConstants.LANGUAGE_SELECTION);
                    if (Utility.isNullOrEmpty(language)) {
                        language = "en";
                    }
                    tv_tournamentDate.setText(Utility.getFormattedTournamentDate(mTournamentList.get(position).getStart_date(), mTournamentList.get(position).getEnd_date(), language, mContext));
                    if (timer != null) {
                        timer.cancel();
                        timer = new Timer();
                    }
                    initProgressView();
                    startTimeUpdateHandler(mTournamentList.get(position).getTournamentStartTime());
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

        tv_progress_seconds.setText(getString(R.string.progres_text_0));
        tv_progress_minutes.setText(getString(R.string.progres_text_0));
        tv_progress_hours.setText(getString(R.string.progres_text_0));
        tv_progress_days.setText(getString(R.string.progres_text_0));
    }

    private void startTimeUpdateHandler(final String startDate) {
//        if (timer != null)
//            timer.cancel();

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
    public void onBackPressed() {
        super.onBackPressed();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
            finishAffinity();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        sp_tournament.setSelection(tournamentPosition);
    }

    public void getDateDifference(String FutureDate) {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());

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

            if (days >= 0) {
                tv_progress_days.setText(days + "");
                mProgressDays.setProgress(30 - days);
            }
            if (minutes >= 0) {
                tv_progress_minutes.setText(minutes + "");
                mProgressMinutes.setProgress(60 - minutes);
            }
            if (hours >= 0) {
                tv_progress_hours.setText(hours + "");
                mProgressHours.setProgress(24 - hours);
            }
            if (seconds >= 0) {
                tv_progress_seconds.setText(seconds + "");
                mProgresSeconds.setProgress(60 - seconds);
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
//            String packageName = CustomTabsHelper.getPackageNameToUse((Activity)mContext);
//            CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
//            CustomTabsIntent customTabsIntent = builder.build();
//            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
//            customTabsIntent.launchUrl(mContext, Uri.parse(facebookUrl));
            Intent facebook = new Intent(mContext, WebViewActivity.class);
            facebook.putExtra(AppConstants.WEBVIEW_INTENT, AppConstants.ARG_FACEBOOK);
            startActivity(facebook);
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
            Intent instagram = new Intent(mContext, WebViewActivity.class);
            instagram.putExtra(AppConstants.WEBVIEW_INTENT, AppConstants.ARG_INSTAGRAM);
            startActivity(instagram);
        }
    }

    @OnClick(R.id.twitter)
    protected void open_twitter() {
        try {
            // get the Twitter app if possible
            mContext.getPackageManager().getPackageInfo("com.twitter.android", 0);
            Intent twitter = new Intent(Intent.ACTION_VIEW, Uri.parse(AppConstants.TWITTER_URL));
            twitter.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            mContext.startActivity(twitter);
        } catch (Exception e) {
            // no Twitter app, revert to browser
            /*CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
            CustomTabsIntent customTabsIntent = builder.build();
            builder.setToolbarColor(getResources().getColor(R.color.colorPrimaryDark));
            customTabsIntent.launchUrl(mContext, Uri.parse(TWITTER_URL));*/
            Intent twitter = new Intent(mContext, WebViewActivity.class);
            twitter.putExtra(AppConstants.WEBVIEW_INTENT, AppConstants.ARG_TWITTER);
            startActivity(twitter);
        }

    }


    private void getLoggedInUserFavouriteTournamentList() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
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
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {

                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TournamentModel mTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mTournamentList != null && mTournamentList.length > 0) {
                                    setTournamnetSpinnerAdapter(mTournamentList);
                                }
                            } else {
                                TournamentModel mEmptyTournamentModel = new TournamentModel();
                                mEmptyTournamentModel.setName(getString(R.string.no_tournament_selected_as_default));
                                TournamentModel[] mTournamentList = new TournamentModel[1];
                                mTournamentList[0] = mEmptyTournamentModel;
                                setEmptyTournamentAdapter(mTournamentList);
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
        } else {
            checkConnection();
        }
    }

    @OnClick(R.id.btn_teams)
    protected void onTeamsClick() {
        selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;
        Intent mClubs = new Intent(mContext, ClubsActivity.class);
        mClubs.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mClubs);
        changeBottomTabAccordingToFlag();
    }

    private void setTournamnetSpinnerAdapter(TournamentModel mTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        for (int i = 0; i < list.size(); i++) {
            if (list.get(i).getIs_default() == 1) {
                mPreference.setString(AppConstants.PREF_TOURNAMENT_ID, list.get(i).getTournament_id());
                AppLogger.LogE(TAG, "selected pos" + tournamentPosition);
                tournamentPosition = i;
                break;
            }
        }
        this.mTournamentList = list;

        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                list);
        sp_tournament.setAdapter(adapter);
        sp_tournament.setSelection(tournamentPosition);
    }

    private void setEmptyTournamentAdapter(TournamentModel mTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        this.mTournamentList = list;
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                list);
        sp_tournament.setAdapter(adapter);
    }

    @OnClick(R.id.iv_tournament_detail)
    protected void onTournamentDetailClicked() {
        String mEuroSportsContactDetails = "";
        mEuroSportsContactDetails = getString(R.string.name) + " ";
        if (!Utility.isNullOrEmpty(mTournamentList.get(tournamentPosition).getFirst_name())) {
            mEuroSportsContactDetails = mEuroSportsContactDetails + mTournamentList.get(tournamentPosition).getFirst_name();
        }
        if (!Utility.isNullOrEmpty(mTournamentList.get(tournamentPosition).getLast_name())) {
            mEuroSportsContactDetails = mEuroSportsContactDetails + " " + mTournamentList.get(tournamentPosition).getLast_name();
        }

        if (!Utility.isNullOrEmpty(mTournamentList.get(tournamentPosition).getTelephone())) {
            mEuroSportsContactDetails = mEuroSportsContactDetails + "<br><br>" + getString(R.string.contact_number) + " <a href=tel:" + mTournamentList.get(tournamentPosition).getTelephone() + ">" + mTournamentList.get(tournamentPosition).getTelephone() + "</a>";
        }

        ViewDialog.showContactDialog((Activity) mContext, getString(R.string.euro_sportring_contact), mEuroSportsContactDetails, getString(R.string.close), getString(R.string.cancel), new ViewDialog.CustomDialogInterface() {
            @Override
            public void onPositiveButtonClicked() {

            }
        });
    }

    @OnClick(R.id.btn_final_placings)
    protected void onFinalPlacingsClicked() {
        Intent mAgeCategories = new Intent(mContext, AgeCategoriesActivity.class);
        mAgeCategories.putExtra(AppConstants.KEY_SCREEN_TITLE, getString(R.string.final_placings_title));
        startActivity(mAgeCategories);
    }


}

