package com.aecor.eurosports.activity;

import android.app.Activity;
import android.app.Application;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.nfc.Tag;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Base64;
import android.util.DisplayMetrics;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
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
import com.bumptech.glide.Glide;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
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

public class ProfileActivity extends BaseAppCompactActivity implements ImageOptionDialogActivity.onImageSelectedInterface {
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
    private int tournamet_id = 0;
    private int selectedTournamentPos;
    private List<TournamentModel> mTournamentList;
    private int languagePos = 0;
    private String[] localeKeys;
    private String selectedLocale;
    @BindView(R.id.ll_main)
    protected LinearLayout ll_main;

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
            if (!Utility.isNullOrEmpty(input_password.getText().toString().trim())) {
                requestJson.put("password", input_password.getText().toString().trim());
            }
            requestJson.put("first_name", input_first_name.getText().toString().trim());
            requestJson.put("last_name", input_last_name.getText().toString().trim());
            requestJson.put("tournament_id", tournamet_id);
            requestJson.put("locale", selectedLocale);
            requestJson.put("user_id", user_id);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if (Utility.isInternetAvailable(mContext)) {
            AppLogger.LogE(TAG, "***** Profile update request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
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
                                mAppPref.setString(AppConstants.LANGUAGE_SELECTION, selectedLocale);
                                mAppPref.setString(AppConstants.LANGUAGE_POSITION, languagePos + "");
                                Utility.showToast(mContext, messgae);
                                Intent mIntent = getIntent();
                                finish();
                                startActivity(mIntent);
                            } else {
                                Utility.showToast(mContext, getResources().getString(R.string.update_profile_message));
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

    protected void initView() {
        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);
        localeKeys = getResources().getStringArray(R.array.language_locale_keys);
        Utility.setupUI(mContext, ll_main);
        enabledDisableLoginButton(true);
        setData();
        getTournamentList();
        setListener();
        showBackButton(getString(R.string.update_profile));
    }

    private void setData() {
        input_email.setText(mAppPref.getString(AppConstants.PREF_EMAIL));
        input_password.setText(mAppPref.getString(AppConstants.PREF_PASSWORD));
        ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(mAppPref.getString(AppConstants.PREF_PROFILE), ProfileModel.class);
        input_first_name.setText(profileModel.getFirst_name());
        tournamet_id = Integer.parseInt(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID));
        input_last_name.setText(profileModel.getSur_name());
        if (!Utility.isNullOrEmpty(profileModel.getProfile_image_url())) {

            Glide.with(mContext)
                    .load(profileModel.getProfile_image_url())
                    .asBitmap()
                    .into(new SimpleTarget<Bitmap>() {
                        @Override
                        public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                            iv_profileImage.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH_LARGE, AppConstants.MAX_IMAGE_HEIGHT_LARGE));
                        }
                    });
        } else {
            iv_profileImage.setImageResource(R.drawable.profile_place_holder);
        }
        setLanguageSpinner();
    }

    private void setLanguageSpinner() {
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(mContext, R.layout.row_spinner_item, R.id.tv_spinner, getResources().getStringArray(R.array.language_selection));
        profile_language_selection.setAdapter(adapter);

        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.LANGUAGE_POSITION)))
            profile_language_selection.setSelection(0);
        else
            profile_language_selection.setSelection(Integer.parseInt(mAppPref.getString(AppConstants.LANGUAGE_POSITION)));
    }

    protected void setListener() {
        GenericTextMatcher textWatcher = new GenericTextMatcher();
        input_last_name.addTextChangedListener(textWatcher);
        input_first_name.addTextChangedListener(textWatcher);
        input_password.addTextChangedListener(textWatcher);
        profile_sp_tournament.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (position > 0) {
                    if (mTournamentList != null && mTournamentList.get(position) != null && Utility.isNullOrEmpty(mTournamentList.get(position).getTournament_id())) {
                        tournamet_id = Integer.parseInt(mTournamentList.get(position).getId());
                    }
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
        profile_language_selection.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                languagePos = position;
                selectedLocale = localeKeys[position];
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

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .GET, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Tournament List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TournamentModel[] mTournamentModal = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mTournamentModal != null && mTournamentModal.length > 0) {
                                    setTournamnetSpinnerAdapter(mTournamentModal);
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
            if (list.get(i).getId().equalsIgnoreCase(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID))) {
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

    private boolean validate() {
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

        if (Utility.isNullOrEmpty(sname)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }
        if (!Utility.isNullOrEmpty(pass) && pass.length() < 5) {
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
        String tempImageBase64String = null;

        if (selectedBitmap != null) {
            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            selectedBitmap.compress(Bitmap.CompressFormat.JPEG, 70, baos);

            if (selectedBitmap != null) {// important! prevent out of memory
                selectedBitmap.recycle();
                selectedBitmap = null;
            }
            byte[] b = baos.toByteArray();
            try {
                System.gc();
                tempImageBase64String = Base64.encodeToString(b, Base64.DEFAULT);
            } catch (Exception e) {
                e.printStackTrace();
            } catch (OutOfMemoryError e) {
                baos = new ByteArrayOutputStream();
                selectedBitmap.compress(Bitmap.CompressFormat.JPEG, 50, baos);
                b = baos.toByteArray();
                tempImageBase64String = Base64.encodeToString(b, Base64.DEFAULT);

            }
        }
        Utility.startProgress(mContext);
        String url = ApiConstants.UPDATE_PROFILE_IMAGE;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("user_id", Utility.getUserId(mContext));
            if (!Utility.isNullOrEmpty(tempImageBase64String)) {
                requestJson.put("user_image", "data:image/png;base64," + tempImageBase64String);
            } else {
                requestJson.put("user_image", "");
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Upload Profile Image Response " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                Utility.showToast(mContext, getString(R.string.profile_updated_successfully));
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

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            checkValidation();
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            checkValidation();
        }

        @Override
        public void afterTextChanged(Editable s) {
            checkValidation();
        }
    }
}
