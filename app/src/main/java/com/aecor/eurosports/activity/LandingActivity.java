package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;
import android.widget.Toast;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.GraphRequest;
import com.facebook.GraphResponse;
import com.facebook.Profile;
import com.facebook.login.LoginManager;
import com.facebook.login.LoginResult;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Collections;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class LandingActivity extends BaseActivity {

    private Context mContext;
    private AppPreference mAppPref;

    @BindView(R.id.tvAppVersion)
    TextView tvAppVersion;

    //Facebook integration
    private CallbackManager mFacebookCallbackManager;

    @Override
    public void initView() {

    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_landing);

        ButterKnife.bind(this);
        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);
        mAppPref.clear();
        this.mFacebookCallbackManager = CallbackManager.Factory.create();

        // Adds application version at the bottom of the screen
        try {
            PackageInfo pInfo = getPackageManager().getPackageInfo(getPackageName(), 0);
            String version = pInfo.versionName;
            tvAppVersion.setText(String.format(getString(R.string.app_version), version));
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }
    }

    @OnClick(R.id.signin)
    protected void signin() {
        boolean ss = getIntent().getBooleanExtra("isFromUrl", false);
        Intent intent = new Intent(mContext, SignInActivity.class);
        intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(intent);
        finish();
    }

    @OnClick(R.id.register)
    protected void register() {
        Intent intent = new Intent(mContext, RegisterActivity.class);
        intent.putExtra("accessCode", getIntent().getStringExtra("accessCode"));
        intent.putExtra("isFromUrl", getIntent().getBooleanExtra("isFromUrl", false));
        startActivity(intent);
        finish();
    }

    @OnClick(R.id.facebook)
    protected void facebookSignIn() {
        try {
            LoginManager.getInstance().logInWithReadPermissions(this, Collections.singletonList("email"));
            LoginManager.getInstance().registerCallback(this.mFacebookCallbackManager, new mFacebookCallBack());
        }catch (Exception e){
            e.printStackTrace();
        }
        }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

            this.mFacebookCallbackManager.onActivityResult(requestCode, resultCode, data);

    }

    private class mFacebookCallBack implements FacebookCallback<LoginResult> {
        public void onSuccess(LoginResult loginResult) {
          //  Profile p = Profile.getCurrentProfile();

            GraphRequest request = GraphRequest.newMeRequest(
                    loginResult.getAccessToken(),
                    new GraphRequest.GraphJSONObjectCallback() {
                        @Override
                        public void onCompleted(
                                JSONObject object,
                                GraphResponse response) {
                            Log.v("LoginActivity Response ", response.toString());

                            try {
                               // Name = object.getString("name");

                               String FEmail = object.getString("email");
                                Log.v("Email = ", " " + FEmail);
                              //  Toast.makeText(getApplicationContext(), "Name " + Name, Toast.LENGTH_LONG).show();


                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    });
            Bundle parameters = new Bundle();
            parameters.putString("fields", "id,name,email,gender");
            request.setParameters(parameters);
            request.executeAsync();
            AppLogger.LogE("TAG", "loginResult" + loginResult);
            AppLogger.LogE("TAG", "loginResult" + loginResult.getAccessToken().getToken());
            AppLogger.LogE("TAG", "loginResult" + loginResult.getAccessToken().getUserId());

            if (!Utility.isNullOrEmpty(loginResult.getAccessToken().getToken())) {
           //     socialLogin(loginResult.getAccessToken().getToken(), AppConstants.PROVIDER_SOCIAL_FACEBOOK);
            }

        }

        public void onCancel() {
            AppLogger.LogE("TAG", "onCancel");
        }

        public void onError(FacebookException exception) {
            AppLogger.LogE("TAG", "exception" + exception);

        }
    }


}
