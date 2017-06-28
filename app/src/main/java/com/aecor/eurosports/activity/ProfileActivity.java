package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ProfileModel;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 26-04-2017.
 */

public class ProfileActivity extends BaseActivity implements ImageOptionDialogActivity.onImageSelectedInterface {
    private static final String TAG = "ProfileActivity";

    @BindView(R.id.iv_profileImage)
    protected ImageView iv_profileImage;
    @BindView(R.id.input_first_name)
    protected EditText input_first_name;
    @BindView(R.id.input_last_name)
    protected EditText input_last_name;
    @BindView(R.id.input_email)
    protected EditText input_email;
    @BindView(R.id.input_password)
    protected EditText input_password;
    @BindView(R.id.profile_sp_tournament)
    protected Spinner profile_sp_tournament;
    @BindView(R.id.btn_update)
    protected Button btn_update;

    private AppPreference mAppPref;
    private Context mContext;
    private int tournamet_id=0;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.profile);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        initView();
    }

    @OnClick(R.id.btn_update)
    protected void onUpdateButtonClicked() {
        String user_id = mAppPref.getString(AppConstants.PREF_USER_ID);
        Utility.startProgress(mContext);
        String url = ApiConstants.UPDATE_PROFILE + user_id;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("password", input_password.getText().toString().trim());
            requestJson.put("first_name", input_first_name.getText().toString().trim());
            requestJson.put("last_name", input_last_name.getText().toString().trim());
            requestJson.put("tournament_id", tournamet_id);
//            requestJson.put("profile_image_url", "");
            requestJson.put("user_id", user_id);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if (Utility.isInternetAvailable(mContext)) {
            AppLogger.LogE(TAG, "***** Profile update request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Profile update response *****" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                String messgae = response.getString("message");
                                Utility.showToast(mContext, messgae);
                            } else {
                                Utility.showToast(mContext, getResources().getString(R.string.update_profile));
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
            }, mAppPref.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest);
        }
    }

    protected void initView() {
        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);
        getTournamentList();
        setData();
        setListener();
    }

    private void setData(){
        input_email.setText(mAppPref.getString(AppConstants.PREF_EMAIL));
        input_password.setText(mAppPref.getString(AppConstants.PREF_PASSWORD));
        ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(mAppPref.getString(AppConstants.PREF_PROFILE), ProfileModel.class);
        input_first_name.setText(profileModel.getFirst_name());
        tournamet_id = profileModel.getTournament_id();
        input_last_name.setText(profileModel.getSur_name());
    }

    protected void setListener() {
        GenericTextMatcher textWatcher = new GenericTextMatcher();
        input_last_name.addTextChangedListener(textWatcher);
        input_first_name.addTextChangedListener(textWatcher);
        input_password.addTextChangedListener(textWatcher);
        profile_sp_tournament.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                tournamet_id = position;
                checkValidation();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
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
        TournamentModel mHintModel = new TournamentModel();
        mHintModel.setName(getString(R.string.select_tournament));

        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        list.add(0, mHintModel);
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                R.layout.row_spinner_item, R.id.title, list);
        profile_sp_tournament.setAdapter(adapter);
    }

    public boolean validate() {
        boolean valid = false;
        String fname = input_first_name.getText().toString().trim();
        String sname = input_last_name.getText().toString().trim();
        String pass = input_password.getText().toString().trim();

        if (fname.isEmpty()) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (sname.isEmpty()) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (pass.isEmpty() || pass.length() < 5) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }
        return valid;
    }

    private void checkValidation() {
        if (!validate() || !validate_spinner()) {
            enabledDisableLoginButton(false);
        } else {
            enabledDisableLoginButton(true);
        }
    }

    private boolean validate_spinner() {
        if(tournamet_id == 0)
            return false;
        else
            return true;
    }

    private void enabledDisableLoginButton(boolean isEnable) {
        if (isEnable) {
            btn_update.setEnabled(true);
            btn_update.setBackgroundResource(R.drawable.btn_yellow);
        } else {
            btn_update.setEnabled(false);
            btn_update.setBackgroundResource(R.drawable.btn_disable);
        }
    }


    @OnClick(R.id.iv_change_profile_pic)
    protected void changeProfileImage() {
        Intent mImageOptionDialogIntent = new Intent(mContext, ImageOptionDialogActivity.class);
        ImageOptionDialogActivity.mCallback = this;
        startActivity(mImageOptionDialogIntent);

    }

    @Override
    public void selectedImageBitmap(Bitmap btm) {
        if (btm != null) {
            iv_profileImage.setImageBitmap(btm);
        } else {
            iv_profileImage.setImageResource(0);
            iv_profileImage.setImageResource(R.drawable.profile_placeholder);
        }

        uploadProfileImage(btm);
    }

    private void uploadProfileImage(Bitmap selectedBitmap) {

    }

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            checkValidation();
        }

        @Override
        public void afterTextChanged(Editable s) {
        }
    }
}
