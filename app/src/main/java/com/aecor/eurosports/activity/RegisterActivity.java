package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.widget.Spinner;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;

import org.json.JSONObject;

import java.util.Arrays;

import butterknife.BindView;
import butterknife.ButterKnife;

public class RegisterActivity extends BaseActivity {
    private final String TAG = RegisterActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.sp_tournament)
    protected Spinner sp_tournament;

    @Override
    public void initView() {
        getTournamentList();
    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        ButterKnife.bind(this);
        Utility.setupUI(mContext, findViewById(R.id.ll_main));
        mContext = this;
        initView();
    }

    private void getTournamentList() {
        Utility.startProgress(mContext);
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
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Tournament List response" + response.toString());
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
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                R.layout.row_spinner_item, R.layout.row_spinner_selected_item, Arrays.asList(mTournamentList));
        sp_tournament.setAdapter(adapter);
    }
}
