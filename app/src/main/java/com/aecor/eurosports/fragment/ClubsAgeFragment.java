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
import com.aecor.eurosports.adapter.AgeAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeCategoriesModel;
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

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubsAgeFragment extends Fragment {

    private final String TAG = ClubsAgeFragment.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_age_search)
    protected EditText et_age_search;
    @BindView(R.id.age_categories_list)
    protected RecyclerView age_list;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    @BindView(R.id.rl_search)
    protected RelativeLayout rl_search;
    private AppPreference mPreference;
    private AgeAdapter adapter;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    @BindView(R.id.iv_close)
    protected ImageView iv_close;

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if (isVisibleToUser && getActivity() != null) {
            getAgeCategories();
        }
    }

    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        mPreference = AppPreference.getInstance(mContext);
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(mContext);
        age_list.setLayoutManager(mLayoutManager);
        age_list.setItemAnimator(new DefaultItemAnimator());
        age_list.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        setListener();
        ll_no_item_view.setVisibility(View.GONE);
        tv_no_item.setVisibility(View.GONE);
        rl_search.setVisibility(View.GONE);
        iv_close.setVisibility(View.GONE);
        et_age_search.setHint(getString(R.string.hint_search_age_category));
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

    private void getAgeCategories() {


        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.AGE_CATEGORIES;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put(AppConstants.PREF_TOURNAMENT_ID, mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
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
                        if (response != null && !Utility.isNullOrEmpty(response.toString())) {
                            AppLogger.LogE(TAG, "Get Tournament Age List response" + response.toString());
                            if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                    AgeCategoriesModel mAgeList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), AgeCategoriesModel[].class);
                                    if (mAgeList != null && mAgeList.length > 0) {
                                        setAgeAdapter(mAgeList);
                                    } else {
                                        showNoItemView();
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
                        } else {
                            showNoItemView();

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

    private void setAgeAdapter(AgeCategoriesModel mTournamentList[]) {

        List<AgeCategoriesModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        Collections.sort(list, new Comparator<AgeCategoriesModel>() {
            @Override
            public int compare(AgeCategoriesModel lhs, AgeCategoriesModel rhs) {
                //here getTitle() method return app name...
                if (lhs.getGroup_name() == null) {
                    return (rhs.getGroup_name() == null) ? 0 : -1;
                }
                if (rhs.getGroup_name() == null) {
                    return 1;
                }
                return lhs.getGroup_name().compareTo(rhs.getGroup_name());
            }
        });
        adapter = new AgeAdapter((Activity) mContext, list, false, false);
        age_list.setAdapter(adapter);
        rl_search.setVisibility(View.VISIBLE);
        age_list.setVisibility(View.VISIBLE);
    }

    private void showNoItemView() {
        ll_no_item_view.setVisibility(View.VISIBLE);
        tv_no_item.setVisibility(View.VISIBLE);
        tv_no_item.setText(getResources().getString(R.string.no_data_available));
        rl_search.setVisibility(View.GONE);
        age_list.setVisibility(View.GONE);

    }

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            if (adapter != null && adapter.getFilter() != null) {
                adapter.getFilter().filter(s.toString());
                if (s.toString().length() > 0) {
                    iv_close.setVisibility(View.VISIBLE);
                } else {
                    iv_close.setVisibility(View.GONE);
                }
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
