package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import com.aecor.eurosports.R;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;

import butterknife.BindView;
import butterknife.ButterKnife;

public class WebViewActivity extends BaseAppCompactActivity {

    @BindView(R.id.webview)
    protected WebView webView;

    private Context mContext;
    private ProgressHUD progressHUD;

    @Override
    protected void initView() {
        progressHUD = Utility.getProgressDialog(mContext);

        Intent getMediaName = getIntent();
        String media_name = getMediaName.getStringExtra(AppConstants.WEBVIEW_INTENT);
        showBackButton(media_name);
        webView.setWebViewClient(new MyWebClient());
        webView.getSettings().setJavaScriptEnabled(true);

        if (!Utility.isNullOrEmpty(media_name) && media_name.equalsIgnoreCase(AppConstants.ARG_FACEBOOK))
            webView.loadUrl(AppConstants.FACEBOOK_URL);
        else if (!Utility.isNullOrEmpty(media_name) && media_name.equalsIgnoreCase(AppConstants.ARG_INSTAGRAM))
            webView.loadUrl(AppConstants.INSTAGRAM_URL);
        else if (!Utility.isNullOrEmpty(media_name) && media_name.equalsIgnoreCase(AppConstants.ARG_TWITTER))
            webView.loadUrl(AppConstants.TWITTER_URL);
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_web_view);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }


    protected class MyWebClient extends WebViewClient {

        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            // TODO Auto-generated method stub
            view.loadUrl(url);
            return true;

        }

        @Override
        public void onPageFinished(WebView view, String url) {
            super.onPageFinished(view, url);
            Utility.StopProgress(progressHUD);
        }
    }
}
