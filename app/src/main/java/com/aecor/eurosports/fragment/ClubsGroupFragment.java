package com.aecor.eurosports.fragment;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.HomeActivity;
import com.aecor.eurosports.adapter.DivisionAdapter;
import com.aecor.eurosports.adapter.GroupAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeGroupModel;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.model.DivisionGroupModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.SimpleDividerItemDecoration;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubsGroupFragment extends Fragment {

    private final String TAG = ClubsGroupFragment.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_age_search)
    protected EditText et_age_search;
    @BindView(R.id.age_categories_list)
    protected RecyclerView rv_groupList;
    @BindView(R.id.rv_divisions)
    protected RecyclerView rv_divisions;
    private AppPreference mPreference;
    private GroupAdapter mGroupAdapter;
    private DivisionAdapter mDivisionAdapter;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    @BindView(R.id.rl_search)
    protected RelativeLayout rl_search;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    @BindView(R.id.iv_close)
    protected ImageView iv_close;
    protected boolean isVisible;

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if (isVisibleToUser && getActivity() != null) {
            getTournamentGroup();
        }
    }

    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN);

        mPreference = AppPreference.getInstance(mContext);
        rv_groupList.setLayoutManager(new LinearLayoutManager(mContext));
        rv_groupList.setItemAnimator(new DefaultItemAnimator());
        rv_groupList.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        rv_groupList.setNestedScrollingEnabled(false);
        rv_groupList.setHasFixedSize(false);
        rv_divisions.setLayoutManager(new LinearLayoutManager(mContext));
        rv_divisions.setItemAnimator(new DefaultItemAnimator());
        rv_divisions.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        rv_divisions.setNestedScrollingEnabled(false);
        rv_divisions.setVisibility(View.GONE);
        rv_divisions.setHasFixedSize(false);
        ll_no_item_view.setVisibility(View.GONE);
        tv_no_item.setVisibility(View.GONE);
        rl_search.setVisibility(View.GONE);
        iv_close.setVisibility(View.GONE);
        setListener();
        et_age_search.setHint(getString(R.string.hint_search_groups));

    }

    protected void setListener() {
        GenericTextMatcher mTextWatcher = new GenericTextMatcher();
        et_age_search.addTextChangedListener(mTextWatcher);

    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.club_content, container, false);
        ButterKnife.bind(this, view);
        mContext = getActivity();
        initView();
        return view;
    }

    private void getTournamentGroup() {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.TOURNAMENT_GROUP;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
            } catch (JSONException e) {
                e.printStackTrace();
            }

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressHUD);
                    try {
                        AppLogger.LogE(TAG, "get Tournament Group Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code"))
                                && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                AgeGroupModel mAgeGroupData = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), AgeGroupModel.class);
                                if (mAgeGroupData != null) {
                                    if (mAgeGroupData.getRound_robin_groups() != null
                                            && mAgeGroupData.getRound_robin_groups().size() > 0) {
                                        setClubGroupAdapter(mAgeGroupData.getRound_robin_groups(), mAgeGroupData);
                                    }
                                    if (mAgeGroupData.getDivision_groups() != null
                                            && mAgeGroupData.getDivision_groups().size() > 0) {
                                        setDivisionAdapter(mAgeGroupData.getDivision_groups(), mAgeGroupData);
                                    }
                                } else {
                                    showNoItemView();
                                }
                            }
                        } else if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code"))
                                && response.getString("status_code").equalsIgnoreCase("500")) {
                            String msg = getString(R.string.selected_tournament_has_expired);
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

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress(mProgressHUD);
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }


    private void setDivisionAdapter(List<DivisionGroupModel> list, AgeGroupModel mAgeGroupData) {
        mDivisionAdapter = new DivisionAdapter((Activity) mContext, list, mAgeGroupData);
        rv_divisions.setAdapter(mDivisionAdapter);
        rv_divisions.setVisibility(View.VISIBLE);
    }

    private void setClubGroupAdapter(List<ClubGroupModel> list, AgeGroupModel mAgeGroupData) {
        Collections.sort(list, new Comparator<ClubGroupModel>() {
            @Override
            public int compare(ClubGroupModel lhs, ClubGroupModel rhs) {
                //here getTitle() method return app name...
                if (lhs.getDisplay_name() == null) {
                    return (rhs.getDisplay_name() == null) ? 0 : -1;
                }
                if (rhs.getDisplay_name() == null) {
                    return 1;
                }
                return lhs.getDisplay_name().compareTo(rhs.getDisplay_name());
            }
        });
        mGroupAdapter = new GroupAdapter((Activity) mContext, list, mAgeGroupData);
        rv_groupList.setAdapter(mGroupAdapter);
        rv_groupList.setVisibility(View.VISIBLE);
        rl_search.setVisibility(View.VISIBLE);
    }

    private void showNoItemView() {
        ll_no_item_view.setVisibility(View.VISIBLE);
        tv_no_item.setVisibility(View.VISIBLE);
        tv_no_item.setText(getResources().getString(R.string.no_data_available));
        rl_search.setVisibility(View.GONE);
        rv_groupList.setVisibility(View.GONE);
    }

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            if (mGroupAdapter != null && mGroupAdapter.getFilter() != null) {
                mGroupAdapter.getFilter().filter(s.toString());
                rv_groupList.setVisibility(View.VISIBLE);
            }
            if (mDivisionAdapter != null && mDivisionAdapter.getFilter() != null) {
                mDivisionAdapter.getFilter().filter(s.toString());
                rv_divisions.setVisibility(View.VISIBLE);
             }
            if (s.toString().length() > 0) {
                iv_close.setVisibility(View.VISIBLE);
            } else {
                iv_close.setVisibility(View.GONE);
            }

        }

        @Override
        public void afterTextChanged(Editable s) {
        }
    }

    @OnClick(R.id.iv_close)
    protected void onCloseButtonClicked() {
        et_age_search.setText("");
    }
}