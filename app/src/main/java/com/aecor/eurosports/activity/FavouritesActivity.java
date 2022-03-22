package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewTreeObserver;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;

import androidx.core.content.ContextCompat;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.EasyMatchManagerFavAdapter;
import com.aecor.eurosports.adapter.FavouriteListAdapter;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class FavouritesActivity extends BaseAppCompactActivity {
    private final String TAG = FavouritesActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.favourite_list)
    protected ListView favouriteList;
    private TournamentModel mAllTournamentList[];
    @BindView(R.id.v_seperator)
    protected View v_seperator;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    @BindView(R.id.llFooter)
    protected LinearLayout llFooter;
    @BindView(R.id.ll_footer)
    protected LinearLayout ll_footer;
    @BindView(R.id.viewFavDefault)
    protected LinearLayout viewFavDefault;
    private FavouriteListAdapter adapter;
    private EasyMatchManagerFavAdapter easyMatchManagerFavAdapter;
    private AppPreference mAppSharedPref;
    private EditText et_enter_access_code;

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);

        mAppSharedPref = AppPreference.getInstance(mContext);

//        favouriteList.addFooterView(new View(mContext));

        if (BuildConfig.isEasyMatchManager) {
            easyMatchManagerFavAdapter = new EasyMatchManagerFavAdapter((Activity) mContext, new ArrayList<TournamentModel>(), new EasyMatchManagerFavAdapter.OnFavRowClick() {
                @Override
                public void onFavRowClick(String access_code) {
                    callAccessCodeApi(access_code, true);
                }
            });
            favouriteList.setAdapter(easyMatchManagerFavAdapter);
            viewFavDefault.setVisibility(View.GONE);
            View footerView = ((LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.row_follow_another_tournament, null, false);
//            favouriteList.addFooterView(footerView);
            llFooter.removeAllViews();
            llFooter.addView(footerView);

            final Button btn_submit = (Button) footerView.findViewById(R.id.btn_submit);
            et_enter_access_code = (EditText) footerView.findViewById(R.id.et_enter_access_code);
            et_enter_access_code.addTextChangedListener(new TextWatcher() {
                @Override
                public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

                }

                @Override
                public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                    if (et_enter_access_code.getText().toString().trim().length() > 0) {
                        btn_submit.setEnabled(true);
                        btn_submit.setBackgroundResource(R.drawable.btn_yellow);
                        btn_submit.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
                    } else {
                        btn_submit.setEnabled(false);
                        btn_submit.setBackgroundResource(R.drawable.btn_disable);
                        btn_submit.setTextColor(Color.BLACK);
                    }
                }

                @Override
                public void afterTextChanged(Editable editable) {

                }
            });

            btn_submit.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    callAccessCodeApi(et_enter_access_code.getText().toString().trim(), false);
                }
            });
            getLoggedInUserFavouriteTournamentList();
        } else {
            adapter = new FavouriteListAdapter((Activity) mContext, new ArrayList<TournamentModel>(), new ArrayList<TournamentModel>());
            favouriteList.setAdapter(adapter);
            viewFavDefault.setVisibility(View.VISIBLE);
            getTournamentList();
        }


        ll_main_layout.getViewTreeObserver().addOnGlobalLayoutListener(new ViewTreeObserver.OnGlobalLayoutListener() {
            @Override
            public void onGlobalLayout() {
                if (Utility.isSoftKeyBoardOpen(ll_main_layout)) {
                    ll_footer.setVisibility(View.GONE);
                } else {
                    // keyboard is closed
                    Thread thread = new Thread() {
                        @Override
                        public void run() {
                            try {
                                Thread.sleep(50);
                            } catch (InterruptedException e) {
                            }

                            runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    // Do some stuff
                                    ll_footer.setVisibility(ViewGroup.VISIBLE);
                                }
                            });
                        }
                    };
                    thread.start();
                }


            }
        });


    }

    private void callAccessCodeApi(String accessCode, final boolean isFromAdapter) {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressDialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.ACCESS_CODE;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("accessCode", accessCode);
            } catch (JSONException e) {
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

                        AppLogger.LogE(TAG, "access code response" + response.toString());
                        if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                            TournamentModel mTempFavTournament = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel.class);
                            if (mTempFavTournament.getId() != null) {
                                mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, mTempFavTournament.getId());
                                mAppSharedPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, mTempFavTournament.getId());
                            }
                        }
                        if (et_enter_access_code != null) {
                            et_enter_access_code.setText("");
                        }
//                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                        if (!isFromAdapter && !getIntent().getBooleanExtra("isFirstTime", false)) {
                            startActivity(new Intent(FavouritesActivity.this, HomeActivity.class));
                        } else {
                            getLoggedInUserFavouriteTournamentList();
                        }
//                        } else {
//                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
//                                Utility.showToast(FavouritesActivity.this, response.getString("message"));
//                            }
//                        }

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
        } else {
            checkConnection();
        }


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

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent mIntent = new Intent(mContext, HomeActivity.class);
        mIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mIntent);
    }

    private void getTournamentList() {
        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressDialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_TOURNAMENTS;
            final JSONObject requestJson = new JSONObject();

            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .GET, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressDialog);
                    try {
                        AppLogger.LogE(TAG, "Get Tournament List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                mAllTournamentList = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mAllTournamentList != null && mAllTournamentList.length > 0) {
                                    getLoggedInUserFavouriteTournamentList();
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
                        Utility.StopProgress(mProgressDialog);
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

    private void getLoggedInUserFavouriteTournamentList() {

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
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TournamentModel mFavTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mFavTournamentList != null && mFavTournamentList.length > 0) {
                                    setTournamnetAdapter(mAllTournamentList, mFavTournamentList);
                                } else {
                                    setTournamnetAdapter(mAllTournamentList, null);
                                    if (BuildConfig.isEasyMatchManager) {
                                        Intent intent = new Intent(FavouritesActivity.this, GetStartedActivity.class);
                                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                        startActivity(intent);
                                        finish();
                                    }
                                }
                            } else {
                                setTournamnetAdapter(mAllTournamentList, null);
                                if (BuildConfig.isEasyMatchManager) {
                                    Intent intent = new Intent(FavouritesActivity.this, GetStartedActivity.class);
                                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                    startActivity(intent);
                                    finish();
                                }
                            }
                        }
                        /*if (BuildConfig.isEasyMatchManager && (mAllTournamentList == null || mAllTournamentList.length == 0)) {
                            Intent intent = new Intent(FavouritesActivity.this, GetStartedActivity.class);
                            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                            startActivity(intent);
                            finish();
                        }*/
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
        } else {
            checkConnection();
        }
    }


    private void setTournamnetAdapter(TournamentModel mTournamentList[], TournamentModel mFavTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        List<TournamentModel> favList = new ArrayList<>();
        if (mTournamentList != null) {
            list.addAll(Arrays.asList(mTournamentList));
        }
        if (mFavTournamentList != null) {
            favList.addAll(Arrays.asList(mFavTournamentList));
        }
        Collections.reverse(list);

        if (list.size() > 5) {
            v_seperator.setVisibility(View.GONE);
        } else {
            v_seperator.setVisibility(View.VISIBLE);
        }
//        if (list.size() > 5) {
//            favouriteList.setOverscrollFooter(new ColorDrawable(Color.TRANSPARENT));
//        }

//        favouriteList.addFooterView(new View(mContext));

        Collections.sort(list, new Comparator<TournamentModel>() {
            DateFormat f = new SimpleDateFormat("dd/MM/yyyy");

            public int compare(TournamentModel o1, TournamentModel o2) {
                if (o1.getStart_date() == null) {
                    return (o2.getStart_date() == null) ? 0 : -1;
                }
                if (o2.getStart_date() == null) {
                    return 1;
                }

                try {
                    return f.parse(o2.getStart_date()).compareTo(f.parse(o1.getStart_date()));
                } catch (ParseException e) {
                    throw new IllegalArgumentException(e);
                }
            }
        });
        if (BuildConfig.isEasyMatchManager) {
            easyMatchManagerFavAdapter.updateList(favList);
        } else {
            adapter.updateList(list, favList);
        }

/*        if (BuildConfig.isEasyMatchManager) {
            View footerView = ((LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.row_follow_another_tournament, null, false);
            favouriteList.addFooterView(footerView);

            final Button btn_submit = (Button) footerView.findViewById(R.id.btn_submit);
            final EditText et_enter_access_code = (EditText) footerView.findViewById(R.id.et_enter_access_code);
            et_enter_access_code.addTextChangedListener(new TextWatcher() {
                @Override
                public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

                }

                @Override
                public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                    if (et_enter_access_code.getText().toString().trim().length() > 0) {
                        btn_submit.setEnabled(true);
                        btn_submit.setBackgroundResource(R.drawable.btn_yellow);
                        btn_submit.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
                    } else {
                        btn_submit.setEnabled(false);
                        btn_submit.setBackgroundResource(R.drawable.btn_disable);
                        btn_submit.setTextColor(Color.BLACK);
                    }
                }

                @Override
                public void afterTextChanged(Editable editable) {

                }
            });

        }*/

    }
}
