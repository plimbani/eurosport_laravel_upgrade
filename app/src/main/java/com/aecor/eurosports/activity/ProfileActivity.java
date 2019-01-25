package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.CountrySpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.CountriesModel;
import com.aecor.eurosports.model.ProfileModel;
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
    private String mSelectedCountryId;
    private List<CountriesModel> mCountryList;
    private String[] roleArray;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.profile);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        initView();
    }

    private void setRoleAdapter() {
        roleArray = mContext.getResources().getStringArray(R.array.role_array);
        ArrayAdapter<String> spinnerAdapter = new ArrayAdapter<String>(this, R.layout.row_spinner_item, R.id.tv_spinner, roleArray) {

            @Override
            public boolean isEnabled(int position) {
                return position != 0;
            }

            @Override
            public boolean areAllItemsEnabled() {
                return false;
            }

            @Override
            public View getDropDownView(int position, View convertView, ViewGroup parent) {
                View v = convertView;
                if (v == null) {
                    Context mContext = this.getContext();
                    LayoutInflater vi = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                    v = vi.inflate(R.layout.row_spinner_item, null);
                }

                TextView tv = (TextView) v.findViewById(R.id.tv_spinner);
                tv.setText(roleArray[position]);

                switch (position) {
                    case 0:
                        tv.setTextColor(Color.GRAY);
                        break;

                    default:
                        tv.setTextColor(Color.BLACK);
                        break;
                }
                return v;
            }
        };

        sp_role.setAdapter(spinnerAdapter);
        sp_role.setSelection(0);

    }

    @OnClick(R.id.btn_update)
    protected void onUpdateButtonClicked() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.setLocale(mContext, selectedLocale);
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
        } else {
            checkConnection();
        }
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
        } else {
            input_email.setText("");
        }


        ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(mAppPref.getString(AppConstants.PREF_PROFILE), ProfileModel.class);
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

        if (Utility.isNullOrEmpty(fname)) {
            return false;
        }
        if (Utility.isNullOrEmpty(mSelectedCountryId)) {
            return false;
        }
        if (Utility.isNullOrEmpty(sp_role.getSelectedItem().toString())
                && !sp_role.getSelectedItem().toString().equalsIgnoreCase(getString(R.string.role))) {
            return false;
        }

        if (Utility.isNullOrEmpty(sname)) {
            return false;
        }
        return true;
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
        super.onBackPressed();
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
}
