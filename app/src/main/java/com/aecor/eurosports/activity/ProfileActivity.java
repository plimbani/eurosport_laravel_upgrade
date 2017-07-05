package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.DisplayMetrics;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.application.ApplicationClass;
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
import java.util.Locale;

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
    @BindView(R.id.profile_language_selection)
    protected Spinner profile_language_selection;
    @BindView(R.id.btn_update)
    protected Button btn_update;

    private AppPreference mAppPref;
    private Context mContext;
    private int tournamet_id;
    private int selectedTournamentPos;
    private List<TournamentModel> mTournamentList;
    private String languageCode="en";

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
                                mAppPref.setString(AppConstants.LANGUAGE_SELECTION,languageCode);
                                Utility.showToast(mContext, messgae);
                                AppLogger.LogE(TAG, "***** Language response *****" + mAppPref.getString(AppConstants.LANGUAGE_POSITION));
                                AppLogger.LogE(TAG, "***** Language App response *****" + mAppPref.getString(AppConstants.LANGUAGE_SELECTION));
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
        setData();
        getLoggedInUserFavouriteTournamentList();
        setListener();
    }

    private void setData(){
        input_email.setText(mAppPref.getString(AppConstants.PREF_EMAIL));
        input_password.setText(mAppPref.getString(AppConstants.PREF_PASSWORD));
        ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(mAppPref.getString(AppConstants.PREF_PROFILE), ProfileModel.class);
        input_first_name.setText(profileModel.getFirst_name());
        tournamet_id = Integer.parseInt(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID));
        input_last_name.setText(profileModel.getSur_name());
        setLanguageSpinner();
    }

    private void setLanguageSpinner() {
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(mContext, R.layout.row_spinner_item,R.id.tv_spinner, getResources().getStringArray(R.array.language_selection));
        profile_language_selection.setAdapter(adapter);
        if(Utility.isNullOrEmpty(mAppPref.getString(AppConstants.LANGUAGE_POSITION)))
            profile_sp_tournament.setSelection(0);
        else
            profile_sp_tournament.setSelection(Integer.parseInt(mAppPref.getString(AppConstants.LANGUAGE_POSITION)));
    }

    protected void setListener() {
        GenericTextMatcher textWatcher = new GenericTextMatcher();
        input_last_name.addTextChangedListener(textWatcher);
        input_first_name.addTextChangedListener(textWatcher);
        input_password.addTextChangedListener(textWatcher);
        profile_sp_tournament.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                tournamet_id = Integer.parseInt(mTournamentList.get(position).getId());
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
        profile_language_selection.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                switch(position){
                    case 0:
                        languageCode="en";
                        break;
                    case 1:
                        languageCode="fr";
                        break;
                    case 2:
                        languageCode="it";
                        break;
                    case 3:
                        languageCode="de";
                        break;
                    case 4:
                        languageCode="nl";
                        break;
                    case 5:
                        languageCode="cs";
                        break;
                    case 6:
                        languageCode="da";
                        break;
                    case 7:
                        languageCode="pl";
                        break;
                }
                mAppPref.setString(AppConstants.LANGUAGE_POSITION,position+"");
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
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

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
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
        TournamentModel mHintModel = new TournamentModel();
        mHintModel.setName(getString(R.string.select_default_tournament));

        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        list.add(0, mHintModel);
        selectedTournamentPos = 0;
        for (int i = 1; i < list.size(); i++) {
            if (list.get(i).getIs_default() == 1) {
                selectedTournamentPos = i;
                break;
            }
        }
        this.mTournamentList = list;
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                R.layout.row_spinner_item, R.id.title, list);
        profile_sp_tournament.setAdapter(adapter);
        profile_sp_tournament.setSelection(selectedTournamentPos);
    }

    public boolean validate() {
        boolean valid = false;
        String fname = input_first_name.getText().toString().trim();
        String sname = input_last_name.getText().toString().trim();
        String pass = input_password.getText().toString().trim();

        if (Utility.isNullOrEmpty(fname)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (Utility.isNullOrEmpty(fname)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (Utility.isNullOrEmpty(fname) || pass.length() < 5) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }
        return valid;
    }

    private void checkValidation() {
        if (!validate()) {
            enabledDisableLoginButton(false);
        } else {
            enabledDisableLoginButton(true);
        }
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
