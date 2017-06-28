package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.EditText;
import android.widget.ListView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.AgeCategoriesAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeCategoriesModel;
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
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class AgeCategoriesActivity extends BaseAppCompactActivity {

    private final String TAG = AgeCategoriesActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_age_search)
    protected EditText et_age_search;
    @BindView(R.id.age_categories_list)
    protected ListView age_list;
    private AppPreference mPreference;
    private AgeCategoriesAdapter adapter;

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        getAgeCategories();
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_AGE_CATEGORIES;
        setContentView(R.layout.activity_age_categories);
        super.onCreate(savedInstanceState);
        mContext = this;
        initView();
    }

    private void getAgeCategories() {
        Utility.startProgress(mContext);
        String url = ApiConstants.AGE_CATEGORIES;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put(AppConstants.PREF_TOURNAMENT_ID, mPreference.getInt(AppConstants.PREF_TOURNAMENT_ID) );
            } catch (JSONException e) {
                e.printStackTrace();
            }

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Tournament List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                AgeCategoriesModel mAgeList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), AgeCategoriesModel[].class);
                                if (mAgeList != null && mAgeList.length > 0) {
                                    setAgeAdapter(mAgeList);
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
            } , mPreference.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest);
        }
    }

    private void setAgeAdapter(AgeCategoriesModel mTournamentList[]) {
        List<AgeCategoriesModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        adapter = new AgeCategoriesAdapter((Activity) mContext, list);
        age_list.setAdapter(adapter);
    }

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            adapter.getFilter().filter(s.toString());
        }

        @Override
        public void afterTextChanged(Editable s) {
        }
    }
}
