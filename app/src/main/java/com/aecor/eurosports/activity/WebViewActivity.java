package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import com.aecor.eurosports.R;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.bumptech.glide.util.Util;

import butterknife.BindView;
import butterknife.ButterKnife;

public class WebViewActivity extends BaseAppCompactActivity {

    @BindView(R.id.webview)
    protected WebView webView;

    private Context mContext;

    @Override
    protected void initView() {

        Intent getMediaName = getIntent();
        String media_name = getMediaName.getStringExtra(AppConstants.WEBVIEW_INTENT);
        showBackButton(media_name);
        webView.setWebViewClient(new MyWebClient());
        webView.getSettings().setJavaScriptEnabled(true);

        if (media_name.equalsIgnoreCase("Facebook"))
            webView.loadUrl(AppConstants.FACEBOOK_URL);
        else if (media_name.equalsIgnoreCase("Instagram"))
            webView.loadUrl(AppConstants.INSTAGRAM_URL);
        else if (media_name.equalsIgnoreCase("Twitter"))
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

        private ProgressHUD progressHUD;
        @Override
        public void onPageStarted(WebView view, String url, Bitmap favicon) {
            // TODO Auto-generated method stub
            super.onPageStarted(view, url, favicon);
        }

        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            // TODO Auto-generated method stub
            progressHUD = Utility.getProgressDialog(mContext);
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
