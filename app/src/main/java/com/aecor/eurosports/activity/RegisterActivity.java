package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class RegisterActivity extends BaseActivity {
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

    @Override
    public void initView() {
        sp_tournament.setSelection(0);
        getTournamentList();
        enabledDisableRegisterButton(false);
        setListener();
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

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    @OnClick(R.id.register)
    public void register() {
        register_user();
    }

    private void register_user() {
        Utility.startProgress(mContext);
        String url = ApiConstants.REGISTER;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("email", email.getText().toString().trim());
            requestJson.put("password", register_password.getText().toString().trim());
            requestJson.put("first_name", fname.getText().toString().trim());
            requestJson.put("sur_name", sname.getText().toString().trim());
            requestJson.put("tournament_id", tournament_id);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if (Utility.isInternetAvailable(mContext)) {
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

                            startActivity(new Intent(mContext, SignInActivity.class));
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
        }
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
        }
    }

    private void checkValidation() {
        if (!validate() || !validate_spinner()) {
            enabledDisableRegisterButton(false);
        } else {
            enabledDisableRegisterButton(true);
        }
    }

    public boolean validate() {
        boolean valid = false;

        String emailOrPhone = email.getText().toString().trim();
        String password = register_password.getText().toString().trim();
        String confirmPassword = confirm_password.getText().toString().trim();
        String firstname = fname.getText().toString().trim();
        String surname = sname.getText().toString().trim();

        if (firstname.isEmpty()) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (surname.isEmpty()) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (emailOrPhone.isEmpty() || !Utility.isValidEmail(emailOrPhone)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (password.isEmpty() || password.length() < 5) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        if (confirmPassword.isEmpty() || !confirmPassword.equals(password)) {
            valid = false;
            return valid;
        } else {
            valid = true;
        }

        return valid;
    }

    private boolean validate_spinner() {
        if (tournament_id == 0)
            return false;
        else
            return true;
    }

    private void enabledDisableRegisterButton(boolean isEnable) {
        if (isEnable) {
            register.setEnabled(true);
            register.setBackground(getResources().getDrawable(R.drawable.btn_yellow));
        } else {
            register.setEnabled(false);
            register.setBackground(getResources().getDrawable(R.drawable.btn_disable));
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
        sp_tournament.setAdapter(adapter);
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
