package com.aecor.eurosports.activity;

import android.os.Bundle;
import android.view.View;
import android.widget.LinearLayout;

import androidx.annotation.Nullable;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;

import butterknife.BindView;

/**
 * Created by system-local on 26-04-2017.
 */

public class PrivacyAndTermsActivity extends BaseAppCompactActivity {

    private boolean isFromSignup = false;
    @BindView(R.id.ll_footer)
    protected LinearLayout ll_footer;

    @Override
    protected void initView() {
        showBackButton(getString(R.string.privacy_terms));
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.privacy_terms);
        super.onCreate(savedInstanceState);
        initView();

        if (getIntent() != null && getIntent().getExtras() != null && getIntent().getExtras().containsKey(AppConstants.KEY_IS_FROM_SIGNUP)) {
            isFromSignup = getIntent().getExtras().getBoolean(AppConstants.KEY_IS_FROM_SIGNUP);
            if (isFromSignup) {
                ll_footer.setVisibility(View.GONE);
            }
        }
    }


}
