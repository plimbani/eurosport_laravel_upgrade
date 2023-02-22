package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;

import androidx.annotation.Nullable;
import androidx.core.content.ContextCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.CountrySpinnerAdapter;
import com.aecor.eurosports.adapter.RoleSpinnerAdapter;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.CountriesModel;
import com.aecor.eurosports.model.ProfileModel;
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

public class ProfileActivity extends BaseAppCompactActivity {
    private static final String TAG = "ProfileActivity";


    @BindView(R.id.input_first_name)
    protected EditText input_first_name;
    @BindView(R.id.input_last_name)
    protected EditText input_last_name;
    @BindView(R.id.input_email)
    protected EditText input_email;
    @BindView(R.id.profile_language_selection)
    protected Spinner profile_language_selection;
    @BindView(R.id.btn_update)
    protected Button btn_update;
    private AppPreference mAppPref;
    private Context mContext;
    private int languagePos = 0;
    private String[] localeKeys;
    private String selectedLocale;
    @BindView(R.id.ll_main)
    protected LinearLayout ll_main;
    @BindView(R.id.sp_role)
    protected Spinner sp_role;
    @BindView(R.id.sp_country)
    protected Spinner sp_country;
    @BindView(R.id.sp_tournament)
    protected Spinner sp_tournament;
    private String mSelectedCountryId;
    private List<CountriesModel> mCountryList;
    private String[] roleArray;
    private TournamentModel[] mTournamentList;
    private String mSelectedTournamentId;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.profile);
        selectedTabName = AppConstants.SCREEN_CONSTANT_USER_SETTINGS;
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        initView();
    }

    private void setRoleAdapter() {
        roleArray = mContext.getResources().getStringArray(R.array.role_array);
        RoleSpinnerAdapter mSpinnerAdapter = new RoleSpinnerAdapter(this, roleArray);
        sp_role.setAdapter(mSpinnerAdapter);
        sp_role.setSelection(0);

    }

    @OnClick(R.id.btn_delete)
    protected void onDeleteUserButtonClicked() {
        ViewDialog.showTwoButtonDialog(((Activity) mContext), getString(R.string.confirm), "Are you sure you want to delete your account?", "Delete", getString(R.string.cancel), new ViewDialog.CustomDialogInterface() {
            @Override
            public void onPositiveButtonClicked() {
                if (Utility.isInternetAvailable(mContext)) {
                    String user_id = mAppPref.getString(AppConstants.PREF_USER_ID);
                    Utility.startProgress(mContext);
                    String url = ApiConstants.DELETE_USER_ACCOUNT + user_id;
                    final JSONObject requestJson = new JSONObject();
                    final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
                    final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                            .POST, url,
                            requestJson, new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {
                            Utility.StopProgress();
                            try {
                                if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                    if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                        Utility.showToast(ProfileActivity.this, response.getString("message"));
                                    }
                                }
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                            AppPreference mAppPref = AppPreference.getInstance(mContext);
                            mAppPref.clear();
                            Utility.setLocale(mContext, "en");
                            Intent intent = new Intent(mContext, LandingActivity.class);
                            ComponentName cn = intent.getComponent();
                            Intent mainIntent = Intent.makeRestartActivityTask(cn);
                            startActivity(mainIntent);
                            finish();
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
        });
    }

    @OnClick(R.id.btn_update)
    protected void onUpdateButtonClicked() {
        if (Utility.isInternetAvailable(mContext)) {
            ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(mAppPref.getString(AppConstants.PREF_PROFILE), ProfileModel.class);
            String user_id = mAppPref.getString(AppConstants.PREF_USER_ID);
            Utility.startProgress(mContext);
            String url = ApiConstants.UPDATE_PROFILE + user_id;
            final JSONObject requestJson = new JSONObject();
            try {
                profileModel.setFirst_name(input_first_name.getText().toString().trim());
                profileModel.setSur_name(input_last_name.getText().toString().trim());
                mAppPref.setString(AppConstants.PREF_PROFILE, GsonConverter.getInstance().encodeToJsonString(profileModel));
                mAppPref.setString(AppConstants.PREF_USER_LOCALE, selectedLocale);
                mAppPref.setString(AppConstants.LANGUAGE_SELECTION, selectedLocale);
                mAppPref.setString(AppConstants.PREF_COUNTRY_ID, mSelectedCountryId);
                mAppPref.setString(AppConstants.PREF_ROLE, sp_role.getSelectedItem().toString());

                requestJson.put("first_name", input_first_name.getText().toString().trim());
                requestJson.put("last_name", input_last_name.getText().toString().trim());
                requestJson.put("locale", selectedLocale);
                requestJson.put("user_id", user_id);
                if (!sp_role.getSelectedItem().toString().equalsIgnoreCase(getString(R.string.role))) {
                    requestJson.put("role", sp_role.getSelectedItem().toString());
                }
                if (!Utility.isNullOrEmpty(mSelectedCountryId)) {
                    requestJson.put("country_id", mSelectedCountryId);
                }
                //check tournament id available in pref or not and also check user has login through fb then and only pass tournament_id
                if (!Utility.isNullOrEmpty(mSelectedTournamentId) && Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID)) && mAppPref.getBoolean(AppConstants.IS_LOGIN_USING_FB)) {
                    requestJson.put("tournament_id", mSelectedTournamentId);
                }

                if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL)) && mAppPref.getBoolean(AppConstants.IS_LOGIN_USING_FB)) {
                    requestJson.put("emailAddress", input_email.getText().toString().trim());
                }

            } catch (JSONException e) {
                e.printStackTrace();
            }
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

                                if (!Utility.isNullOrEmpty(mSelectedTournamentId) && Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID)) && mAppPref.getBoolean(AppConstants.IS_LOGIN_USING_FB)) {
                                    mAppPref.setString(AppConstants.PREF_TOURNAMENT_ID, mSelectedTournamentId);
                                    mAppPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, mSelectedTournamentId);
                                }

                                if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL)) && mAppPref.getBoolean(AppConstants.IS_LOGIN_USING_FB)) {
                                    mAppPref.setString(AppConstants.PREF_EMAIL, input_email.getText().toString().trim());
                                }
                                Utility.showToast(mContext, messgae);

                                setlanguage(selectedLocale);
                            } else {
                                Utility.showToast(mContext, getResources().getString(R.string.update_profile_message));
                            }
                        } else if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("500")) {
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.error), response.getString("message"), mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                                    @Override
                                    public void onPositiveButtonClicked() {

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
        } else {
            checkConnection();
        }
    }

    public void setlanguage(String ar) {
        Locale locale = new Locale(ar);
        Locale.setDefault(locale);
        Configuration config = new Configuration();
        config.locale = locale;
        getBaseContext().getResources().updateConfiguration(config,
                getBaseContext().getResources().getDisplayMetrics());
        Intent intent = getIntent();
        overridePendingTransition(0, 0);
        intent.addFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
        finish();
        overridePendingTransition(0, 0);
        startActivity(intent);
    }

    protected void initView() {
        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);
        setRoleAdapter();
        localeKeys = getResources().getStringArray(R.array.language_locale_keys);
        Utility.setupUI(mContext, ll_main);
        enabledDisableLoginButton(true);
        setData();
        getCountryList();
        setListener();
        showBackButton(getResources().getString(R.string.profile).toUpperCase());
    }

    private void setData() {
        if (!Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL))) {
            input_email.setText(mAppPref.getString(AppConstants.PREF_EMAIL));
            input_email.setFocusable(false);
            input_email.setEnabled(false);
            input_email.setClickable(false);
        } else {
            input_email.setClickable(true);
            input_email.setFocusable(true);
            input_email.setEnabled(true);
            input_email.setText("");
        }


        ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(mAppPref.getString(AppConstants.PREF_PROFILE), ProfileModel.class);
        if (profileModel != null) {
            if (!Utility.isNullOrEmpty(profileModel.getFirst_name())) {
                input_first_name.setText(profileModel.getFirst_name());
            } else {
                input_first_name.setText("");
            }

            if (!Utility.isNullOrEmpty(profileModel.getSur_name())) {
                input_last_name.setText(profileModel.getSur_name());
            } else {
                input_last_name.setText("");
            }
        }

        setLanguageSpinner();
        setRoleSpinnerPreSelectedItem();
    }

    private void setLanguageSpinner() {
        ArrayAdapter<String> adapter = new ArrayAdapter<>(mContext, R.layout.row_spinner_item, R.id.tv_spinner, getResources().getStringArray(R.array.language_selection));
        profile_language_selection.setAdapter(adapter);

        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.LANGUAGE_POSITION)))
            profile_language_selection.setSelection(0);
        else
            profile_language_selection.setSelection(Integer.parseInt(mAppPref.getString(AppConstants.LANGUAGE_POSITION)));
    }

    private void setRoleSpinnerPreSelectedItem() {

        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_ROLE))) {
            sp_role.setSelection(0);
        } else {
            for (int i = 0; i < roleArray.length; i++) {
                if (roleArray[i].equalsIgnoreCase(mAppPref.getString(AppConstants.PREF_ROLE))) {
                    sp_role.setSelection(i);
                    break;
                }
            }
        }
    }

    protected void setListener() {
        GenericTextMatcher textWatcher = new GenericTextMatcher();
        input_last_name.addTextChangedListener(textWatcher);
        input_first_name.addTextChangedListener(textWatcher);

        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL))) {
            input_email.addTextChangedListener(textWatcher);
        }


        profile_language_selection.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                languagePos = position;
                selectedLocale = localeKeys[position];
//                Utility.setLocale(mContext, selectedLocale);
//                initView();
                checkValidation();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                checkValidation();
            }
        });
        sp_country.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {


                if (position > 0) {
                    mSelectedCountryId = mCountryList.get(position).getId();
                }

                checkValidation();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                checkValidation();
            }
        });
        sp_role.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                checkValidation();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                checkValidation();
            }
        });


        sp_tournament.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (position > 0) {
                    mSelectedTournamentId = mTournamentList[position - 1].getId();
                }
                checkValidation();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                checkValidation();
            }
        });
    }

    private void getCountryList() {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.GET_ALL_COUNTRY;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .GET, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    // getTournamentList api calling after getting response of country because of progress bar
                    // both api calling showProgress and stopProgress
                    // spinner will be visible if tournament_id is null inside pref and user has login through fb
                    AppLogger.LogE(TAG, "get Country List");
                    AppLogger.LogE(TAG, mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID));
                    if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID))
                            && mAppPref.getBoolean(AppConstants.IS_LOGIN_USING_FB)) {

                        sp_tournament.setVisibility(View.VISIBLE);
                        getTournamentList();
                    } else {
                        sp_tournament.setVisibility(View.GONE);
                    }
                    try {
                        AppLogger.LogE(TAG, "Get Country List response" + response.toString());
                        if (response.has("countries") && !Utility.isNullOrEmpty(response.getString("countries"))) {
                            CountriesModel[] countries = GsonConverter.getInstance().decodeFromJsonString(response.getString("countries"), CountriesModel[].class);
                            if (countries != null && countries.length > 0) {
                                setCountrySpinnerAdapter(countries);
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

    private void setCountrySpinnerAdapter(CountriesModel countriesModel[]) {
        CountriesModel mHintModel = new CountriesModel();
        mHintModel.setName(getString(R.string.select_your_country));

        mCountryList = new ArrayList<>();
        mCountryList.addAll(Arrays.asList(countriesModel));
        mCountryList.add(0, mHintModel);
        CountrySpinnerAdapter adapter = new CountrySpinnerAdapter((Activity) mContext,
                mCountryList);
        sp_country.setAdapter(adapter);
        sp_country.setSelection(0);
        setCountrySpinnerPreSelectedItem();
    }

    private void setCountrySpinnerPreSelectedItem() {

        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_COUNTRY_ID))) {
            sp_country.setSelection(0);
        } else {
            for (int i = 1; i < mCountryList.size(); i++) {
                if (mCountryList.get(i).getId().equalsIgnoreCase(mAppPref.getString(AppConstants.PREF_COUNTRY_ID))) {
                    sp_country.setSelection(i);
                    break;
                }
            }
        }
    }

    private boolean validate() {
        String fname = input_first_name.getText().toString().trim();
        String sname = input_last_name.getText().toString().trim();
        String email = input_email.getText().toString().trim();
        addOrRemoveBorder();

        if (Utility.isNullOrEmpty(email)) {
            return false;
        }

        if (!Utility.isValidEmail(email)) {
            return false;
        }

        if (Utility.isNullOrEmpty(fname)) {
            return false;
        }

        if (Utility.isNullOrEmpty(sname)) {
            return false;
        }

        if (Utility.isNullOrEmpty(mSelectedCountryId)) {
            return false;
        }
        if (Utility.isNullOrEmpty(sp_role.getSelectedItem().toString())
                && sp_role.getSelectedItem().toString().equalsIgnoreCase(getString(R.string.role))) {

            return false;
        }
        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID)) && Utility.isNullOrEmpty(mSelectedTournamentId)) {
            return false;
        }


        return true;
    }

    private void addOrRemoveBorder() {
        String fname = input_first_name.getText().toString().trim();
        String sname = input_last_name.getText().toString().trim();
        String email = input_email.getText().toString().trim();

        if (Utility.isNullOrEmpty(fname)) {
            input_first_name.setBackgroundResource(R.drawable.edittext_border_red);
        } else {
            input_first_name.setBackgroundResource(R.drawable.edittext_border);
        }

        if (Utility.isNullOrEmpty(sname)) {
            input_last_name.setBackgroundResource(R.drawable.edittext_border_red);
        } else {
            input_last_name.setBackgroundResource(R.drawable.edittext_border);
        }

        if (Utility.isNullOrEmpty(mSelectedCountryId)) {
            sp_country.setBackgroundResource(R.drawable.spinner_bg_image_gray_error);
        } else {
            sp_country.setBackgroundResource(R.drawable.spinner_bg_image_gray);
        }
        if (sp_role.getSelectedItem().toString().equalsIgnoreCase(getString(R.string.role))) {
            sp_role.setBackgroundResource(R.drawable.spinner_bg_image_gray_error);

        } else {
            sp_role.setBackgroundResource(R.drawable.spinner_bg_image_gray);
        }

        if (Utility.isNullOrEmpty(mSelectedTournamentId)) {
            sp_tournament.setBackgroundResource(R.drawable.spinner_bg_image_gray_error);

        } else {
            sp_tournament.setBackgroundResource(R.drawable.spinner_bg_image_gray);
        }

        if (Utility.isNullOrEmpty(email)) {
            input_email.setBackgroundResource(R.drawable.edittext_border_red);
        } else {
            input_email.setBackgroundResource(R.drawable.edittext_border);
        }
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
            btn_update.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
            btn_update.setBackgroundResource(R.drawable.btn_yellow);
        } else {
            btn_update.setEnabled(false);
            btn_update.setTextColor(Color.BLACK);
            btn_update.setBackgroundResource(R.drawable.btn_disable);
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

    @Override
    public void onBackPressed() {
        if (Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_EMAIL)) || Utility.isNullOrEmpty(mAppPref.getString(AppConstants.PREF_TOURNAMENT_ID))) {
            Utility.showToast(ProfileActivity.this, getString(R.string.please_update_profile));
            return;
        }
        Intent mSettingsIntent = new Intent(mContext, SettingsActivity.class);
        startActivity(mSettingsIntent);
        finish();
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // app icon in action bar clicked; go home
                onBackPressed();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void getTournamentList() {

        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.GET_TOURNAMENTS;
            final JSONObject requestJson = new JSONObject();
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
                                mTournamentList = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
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
        } else {
            checkConnection();
        }
    }


    private void setTournamnetSpinnerAdapter(TournamentModel mTournamentList[]) {
        TournamentModel mHintModel = new TournamentModel();
        mHintModel.setName(getString(R.string.select_tournament));

        List<TournamentModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        list.add(0, mHintModel);
        TournamentSpinnerAdapter adapter = new TournamentSpinnerAdapter((Activity) mContext,
                list);
        sp_tournament.setAdapter(adapter);
        sp_tournament.setSelection(0);

    }
}
