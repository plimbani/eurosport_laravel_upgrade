package com.aecor.eurosports.activity;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;

import androidx.annotation.Nullable;
import androidx.core.content.ContextCompat;

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

import org.json.JSONException;
import org.json.JSONObject;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class GetStartedActivity extends BaseActivity {

    private final String TAG = GetStartedActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_enter_access_code)
    protected EditText et_enter_access_code;
    @BindView(R.id.btnSubmit)
    protected Button btnSubmit;
    private AppPreference mAppSharedPref;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_access_code);
        Utility.setupUI(this,findViewById(R.id.ll_main_layout));

        ButterKnife.bind(this);
        mContext = this;
        initView();
        setListener();
    }

    @Override
    protected void initView() {
        mAppSharedPref = AppPreference.getInstance(mContext);
    }

    @Override
    protected void setListener() {
        et_enter_access_code.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                validate();
            }

            @Override
            public void afterTextChanged(Editable editable) {

            }
        });
    }

    @SuppressLint("NewApi")
    private void validate() {
        if (et_enter_access_code.getText().toString().trim().length() > 0) {
            btnSubmit.setEnabled(true);
            btnSubmit.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
            btnSubmit.setBackground(getResources().getDrawable(R.drawable.btn_yellow));
        } else {
            btnSubmit.setTextColor(Color.BLACK);
            btnSubmit.setEnabled(false);
            btnSubmit.setBackground(getResources().getDrawable(R.drawable.btn_disable));
        }
    }

    @OnClick(R.id.btnSubmit)
    public void onSubmitClick() {
        if (Utility.isInternetAvailable(mContext)) {
            callAccessCodeApi(et_enter_access_code.getText().toString().trim());
        } else {
            checkConnection();
        }
    }


    private void callAccessCodeApi(String accessCode) {

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

                        if (Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_COUNTRY_ID)) || Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_EMAIL))) {
                            startActivity(new Intent(GetStartedActivity.this, ProfileActivity.class));
                        } else {
                            startActivity(new Intent(GetStartedActivity.this, FavouritesActivity.class));
                        }
                        finish();


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
}
