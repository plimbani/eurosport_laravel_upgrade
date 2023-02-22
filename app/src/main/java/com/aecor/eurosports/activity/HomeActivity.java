package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Parcelable;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TeamDetailModel;
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
import com.github.lzyzsd.circleprogress.DonutProgress;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.Date;
import java.util.List;
import java.util.TimeZone;
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
    @BindView(R.id.btn_fav_team)
    protected Button btn_fav_team;
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

                    if (mTournamentList.get(position).getTeamId() == 0 && mTournamentList.get(position).getClubId() == 0) {
                        btn_fav_team.setVisibility(View.GONE);
                    } else {
                        btn_fav_team.setVisibility(View.VISIBLE);
                    }
                    if (!Utility.isNullOrEmpty(mTournamentList.get(position).getId()) && !Utility.isNullOrEmpty(mTournamentList.get(position).getTournament_id())) {
                        mPreference.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, mTournamentList.get(position).getTournament_id());
                        mPreference.setString(AppConstants.PREF_SESSION_TOURNAMENT_STATUS, mTournamentList.get(position).getStatus());
                    }
                    if (!Utility.isNullOrEmpty(mTournamentList.get(position).getName())) {
                        tv_tournamentName.setText(mTournamentList.get(position).getName());
                    } else {
                        tv_tournamentName.setText("");
                    }
                }
                if (mTournamentList != null && mTournamentList.get(position) != null && !Utility.isNullOrEmpty(mTournamentList.get(position).getTournamentLogo())) {
                    Glide.with(mContext).load(mTournamentList.get(position).getTournamentLogo()).diskCacheStrategy(DiskCacheStrategy.NONE).skipMemoryCache(true).dontAnimate().placeholder(R.drawable.globe).error(R.drawable.globe).override(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT).into(iv_tournamentLogo);
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


    public void getDateDifference(String FutureDate) {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        dateFormat.setTimeZone(TimeZone.getTimeZone("Europe/London"));

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
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method.POST, url, requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {

                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TournamentModel mTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mTournamentList != null && mTournamentList.length > 0) {
                                    AppPreference.getInstance(HomeActivity.this).setString(AppConstants.TOURNAMENT_LIST, GsonConverter.getInstance().encodeToJsonString(mTournamentList));
                                    setTournamnetSpinnerAdapter(mTournamentList);
                                    TournamentModel[] temp = AppPreference.getInstance(HomeActivity.this).getTournamentList(HomeActivity.this);
                                } else {
                                    if (BuildConfig.isEasyMatchManager) {
                                        Intent intent = new Intent(HomeActivity.this, GetStartedActivity.class);
                                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                        startActivity(intent);
                                        finish();
                                    }
                                }
                            } else {
                                TournamentModel mEmptyTournamentModel = new TournamentModel();
                                mEmptyTournamentModel.setName(getString(R.string.no_tournament_selected_as_default));
                                TournamentModel[] mTournamentList = new TournamentModel[1];
                                mTournamentList[0] = mEmptyTournamentModel;
                                setEmptyTournamentAdapter(mTournamentList);
                                if (BuildConfig.isEasyMatchManager) {
                                    Intent intent = new Intent(HomeActivity.this, GetStartedActivity.class);
                                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                    startActivity(intent);
                                    finish();
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
        } else {
            checkConnection();
        }
    }

    @OnClick(R.id.btn_teams)
    protected void onTeamsClick() {
        selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;
        if (!Utility.isNullOrEmpty(mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_STATUS)) && mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_STATUS).equalsIgnoreCase("Preview")) {
            ViewDialog.showSingleButtonDialog(this, getString(R.string.preview), getString(R.string.preview_message), getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                @Override
                public void onPositiveButtonClicked() {

                }
            });
        } else {
            Intent mClubs = new Intent(mContext, ClubsActivity.class);
            mClubs.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(mClubs);
            changeBottomTabAccordingToFlag();
        }
    }

    @OnClick(R.id.btn_fav_team)
    protected void onFavTeamsClick() {
        getTeamList();
    }

    private void getTeamList() {

        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.GET_TEAM_LIST;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            try {
                requestJson.put(AppConstants.PREF_TOURNAMENT_ID, mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
//                if (!Utility.isNullOrEmpty(ageGroupId)) {
//                    requestJson.put("age_group_id", ageGroupId);
//                }
                requestJson.put("club_id", mTournamentList.get(tournamentPosition).getClubId());

//                if (!Utility.isNullOrEmpty(groupId)) {
//                    requestJson.put("group_id", groupId);
//                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
            AppLogger.LogE(TAG, "url" + url);
            AppLogger.LogE(TAG, "requestJson " + requestJson.toString());
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method.POST, url, requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        if (response != null && !Utility.isNullOrEmpty(response.toString())) {
                            AppLogger.LogE(TAG, "get team list" + response.toString());
                            if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                    TeamDetailModel mTeamList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamDetailModel[].class);
                                    if (mTeamList != null && mTeamList.length > 0) {
                                        Intent mTeamDetailIntent = new Intent(mContext, TeamActivity.class);
                                        List<TeamDetailModel> list = new ArrayList<>();
                                        for (TeamDetailModel teamDetailModel : mTeamList) {
                                            if (teamDetailModel.getId().equals(String.valueOf(mTournamentList.get(tournamentPosition).getTeamId())) && teamDetailModel.getClub_id().equals(String.valueOf(mTournamentList.get(tournamentPosition).getClubId()))) {
//                                                mTeamDetailIntent.putExtra(AppConstants.ARG_TEAM_DETAIL, mTeamList[tournamentPosition]);
                                                mTeamDetailIntent.putExtra(AppConstants.ARG_TEAM_DETAIL, teamDetailModel);
                                            }
                                            list.add(teamDetailModel);
                                        }
                                        Bundle bundle = new Bundle();
                                        bundle.putParcelableArrayList(AppConstants.ARG_ALL_TEAM_LIST, (ArrayList<? extends Parcelable>) list);
                                        mTeamDetailIntent.putExtras(bundle);
                                        startActivity(mTeamDetailIntent);
                                    }

                                }
                            } else if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("500")) {
                                String msg = "Selected tournament has expired";
                                if (response.has("message")) {
                                    msg = response.getString("message");
                                }
                                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.error), msg, mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                                    @Override
                                    public void onPositiveButtonClicked() {
                                        Intent intent = new Intent(mContext, HomeActivity.class);
                                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                        mContext.startActivity(intent);
                                        ((Activity) mContext).finish();
                                    }

                                });
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

    private List<TournamentModel> moveItemToTop(List<TournamentModel> lists, int positionOfItem) {

        if (lists == null || positionOfItem < 0 || positionOfItem >= lists.size()) {
            return lists;
        }

        TournamentModel mTournamentModel = lists.get(positionOfItem);
        lists.remove(positionOfItem);
        lists.add(0, mTournamentModel);


        return lists;
    }

    private void setTournamnetSpinnerAdapter(TournamentModel mTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        Collections.reverse(list);

        for (int i = 0; i < list.size(); i++) {
            DateFormat f = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
            DateFormat targetFormat = new SimpleDateFormat("dd/MM/yyyy");
            Date date = null;
            try {
                date = f.parse(list.get(i).getStart_date());
            } catch (ParseException e) {
                e.printStackTrace();
            }
            list.get(i).setmTempStartDate(targetFormat.format(date));

        }
        Collections.sort(list, new Comparator<TournamentModel>() {
            DateFormat f = new SimpleDateFormat("dd/MM/yyyy");

            public int compare(TournamentModel o1, TournamentModel o2) {
                if (o1.getmTempStartDate() == null) {
                    return (o2.getmTempStartDate() == null) ? 0 : -1;
                }
                if (o2.getmTempStartDate() == null) {
                    return 1;
                }

                try {
                    return f.parse(o2.getmTempStartDate()).compareTo(f.parse(o1.getmTempStartDate()));
                } catch (ParseException e) {
                    throw new IllegalArgumentException(e);
                }
            }
        });
        for (int i = 0; i < list.size(); i++) {
            if (list.get(i).getIs_default() == 1) {
                mPreference.setString(AppConstants.PREF_TOURNAMENT_ID, list.get(i).getTournament_id());
                AppLogger.LogE(TAG, "selected pos" + tournamentPosition);
                tournamentPosition = i;
                break;
            }
        }
        if (!Utility.isNullOrEmpty(mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID))) {
            if (list.size() > 0) {
                for (int i = 0; i < list.size(); i++) {
                    if (list.get(i).getTournament_id().equalsIgnoreCase(mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID))) {
                        tournamentPosition = i;
                        break;
                    }
                }
            }
        }
        list = moveItemToTop(list, tournamentPosition);
        this.mTournamentList = list;
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext, list);
        sp_tournament.setAdapter(adapter);

        // Sets selected tournament
        sp_tournament.setSelection(0);

    }

    private void setEmptyTournamentAdapter(TournamentModel mTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        this.mTournamentList = list;
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext, list);
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

        ViewDialog.showContactDialog((Activity) mContext, getString(R.string.tournament_contact), mEuroSportsContactDetails, getString(R.string.close), getString(R.string.cancel), new ViewDialog.CustomDialogInterface() {
            @Override
            public void onPositiveButtonClicked() {

            }
        });
    }

    @OnClick(R.id.btn_final_placings)
    protected void onFinalPlacingsClicked() {
        if (!Utility.isNullOrEmpty(mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_STATUS)) && mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_STATUS).equalsIgnoreCase("Preview")) {
            ViewDialog.showSingleButtonDialog(this, getString(R.string.preview), getString(R.string.preview_message), getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                @Override
                public void onPositiveButtonClicked() {

                }
            });
        } else {
            Intent mAgeCategories = new Intent(mContext, AgeCategoriesActivity.class);
//            mAgeCategories.putExtra(AppConstants.KEY_SCREEN_TITLE, getString(R.string.final_placings_title));
            startActivity(mAgeCategories);
        }
    }
}