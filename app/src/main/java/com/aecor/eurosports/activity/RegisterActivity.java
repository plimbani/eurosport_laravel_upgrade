package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.Html;
import android.text.SpannableString;
import android.text.Spanned;
import android.text.TextWatcher;
import android.text.method.LinkMovementMethod;
import android.text.style.ClickableSpan;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;

import androidx.core.content.ContextCompat;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.RoleSpinnerAdapter;
import com.aecor.eurosports.adapter.TournamentSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
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
import butterknife.OnClick;

public class RegisterActivity extends BaseAppCompactActivity {
    private final String TAG = RegisterActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.sp_tournament)
    protected Spinner sp_tournament;
    @BindView(R.id.firstname)
    protected EditText fname;
    @BindView(R.id.surname)
    protected EditText sname;
    @BindView(R.id.register_email_address)
    protected EditText email;
    @BindView(R.id.register_password)
    protected EditText register_password;
    @BindView(R.id.register_confirm_password)
    protected EditText confirm_password;
    @BindView(R.id.register)
    protected Button register;
    private long tournament_id = 0;
    private TournamentModel mTournamentList[];
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    @BindView(R.id.tv_privacy_terms)
    protected TextView tv_privacy_terms;
    @BindView(R.id.sp_role)
    protected Spinner sp_role;
    private String[] roleArray;

    @Override
    public void initView() {

        SpannableString myString = new SpannableString(Html.fromHtml(getString(R.string.terms_of_use)));

        ClickableSpan clickableSpan = new ClickableSpan() {
            @Override
            public void onClick(View textView) {
                Intent mTermsAndConditionActivity = new Intent(mContext, PrivacyAndTermsActivity.class);
                mTermsAndConditionActivity.putExtra(AppConstants.KEY_IS_FROM_SIGNUP, true);
                startActivity(mTermsAndConditionActivity);
                textView.invalidate();
            }
        };


        myString.setSpan(clickableSpan, 29, 42, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);


        tv_privacy_terms.setMovementMethod(LinkMovementMethod.getInstance());
        tv_privacy_terms.setText(myString);
        tv_privacy_terms.setHighlightColor(Color.TRANSPARENT);


        Utility.setupUI(mContext, ll_main_layout);
        getTournamentList();
        enabledDisableRegisterButton(false);
        setListener();
        setRoleAdapter();

        if (BuildConfig.isEasyMatchManager) {
            sp_tournament.setVisibility(View.GONE);
        } else {
            sp_tournament.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public void setListener() {
        GenericTextMatcher mTextChangeLister = new GenericTextMatcher();
        email.addTextChangedListener(mTextChangeLister);
        fname.addTextChangedListener(mTextChangeLister);
        sname.addTextChangedListener(mTextChangeLister);
        confirm_password.addTextChangedListener(mTextChangeLister);
        register_password.addTextChangedListener(mTextChangeLister);
        sp_tournament.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (position > 0) {
                    tournament_id = Long.parseLong(mTournamentList[position - 1].getId());
                }
                checkValidation();
            }

            @Override
            public void onNothingSelected(AdapterView<?> arg0) {
                checkValidation();
            }
        });
    }

    private void setRoleAdapter() {
        roleArray = mContext.getResources().getStringArray(R.array.role_array);
        RoleSpinnerAdapter mSpinnerAdapter = new RoleSpinnerAdapter(this, roleArray);
        sp_role.setAdapter(mSpinnerAdapter);
        sp_role.setSelection(0);

    }

    @OnClick(R.id.iv_header_logo)
    protected void onHeaderLogoClicked() {
        Intent intent = new Intent(mContext, LandingActivity.class);
        intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(intent);
        finish();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_register);
        super.onCreate(savedInstanceState);
        mContext = this;
        initView();
    }

    @OnClick(R.id.register)
    public void register() {
        if (!confirm_password.getText().toString().equals(register_password.getText().toString().trim())) {
            Utility.showToast(mContext, getString(R.string.password_do_not_match));
        } else if (confirm_password.length() <= 4 && register_password.length() <= 4) {
            Utility.showToast(mContext, getString(R.string.password_must_be_at_least_5_char_long));
        } else {
            register_user();
        }
    }

    private void register_user() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.REGISTER;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("email", email.getText().toString().trim());
                requestJson.put("password", register_password.getText().toString().trim());
                requestJson.put("first_name", fname.getText().toString().trim());
                requestJson.put("sur_name", sname.getText().toString().trim());
                requestJson.put("tournament_id", tournament_id);
                if (!sp_role.getSelectedItem().toString().equalsIgnoreCase(getString(R.string.role)))
                    requestJson.put("role", sp_role.getSelectedItem().toString());

            } catch (JSONException e) {
                e.printStackTrace();
            }

            AppLogger.LogE(TAG, "***** Register request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Register response *****" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                String messgae = response.getString("message");
                                Utility.showToast(mContext, messgae);
                            } else {
                                Utility.showToast(mContext, getResources().getString(R.string.register_success));
                            }

                            Intent intent = new Intent(mContext, SignInActivity.class);
                            intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
                            intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
                            startActivity(intent);
                            finish();
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
                        AppLogger.LogE(TAG, "***** Register Error *****" + error.toString());
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

    private void checkValidation() {
        if (!validate()) {
            enabledDisableRegisterButton(false);
        } else {
            enabledDisableRegisterButton(true);
        }
    }

    public boolean validate() {

        String emailOrPhone = email.getText().toString().trim();
        String password = register_password.getText().toString().trim();
        String confirmPassword = confirm_password.getText().toString().trim();
        String firstname = fname.getText().toString().trim();
        String surname = sname.getText().toString().trim();

        if (firstname.isEmpty()) {
            return false;
        }

        if (surname.isEmpty()) {
            return false;
        }
        if (emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone)) {
            return false;
        }

        if (password.isEmpty()) {
            return false;
        }
        if (confirmPassword.isEmpty()) {
            return false;
        }

        if (!BuildConfig.isEasyMatchManager && !validate_spinner()) {
            return false;
        }

        return true;

    }

    private boolean validate_spinner() {
        return tournament_id != 0;
    }

    private void enabledDisableRegisterButton(boolean isEnable) {
        if (isEnable) {
            register.setEnabled(true);
            register.setTextColor(ContextCompat.getColor(mContext, R.color.btn_active_text_color));
            register.setBackgroundResource(R.drawable.btn_yellow);
        } else {
            register.setEnabled(false);
            register.setTextColor(Color.BLACK);
            register.setBackgroundResource(R.drawable.btn_disable);
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


    @OnClick(R.id.iv_back)
    protected void onBackButtonPressed() {
        loadBackActivity();
    }

    private void loadBackActivity() {
        Intent mLandingActivityIntent = new Intent(mContext, LandingActivity.class);
        mLandingActivityIntent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        mLandingActivityIntent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(mLandingActivityIntent);
        finish();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // app icon in action bar clicked; go home
                loadBackActivity();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }


    @Override
    public void onBackPressed() {
//        super.onBackPressed();
        loadBackActivity();
    }
}
