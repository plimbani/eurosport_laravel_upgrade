package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.support.v4.content.ContextCompat;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.ortiz.touchview.TouchImageView;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by system-local on 25-01-2017.
 */

public class FullScreenImageActivity extends Activity {

    private final String TAG = FullScreenImageActivity.class.getName();
    private Context mContext;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mContext = this;
        Intent intent = getIntent();
        if (intent.hasExtra(AppConstants.KEY_AGE_CATEGORIES_ID)) {
            String ageCategoryId = intent.getStringExtra(AppConstants.KEY_AGE_CATEGORIES_ID);
            getGraphicImageUrl(ageCategoryId);

        } else if (intent.hasExtra(AppConstants.KEY_IMAGE_URL)) {
            String imageUrl = intent.getStringExtra(AppConstants.KEY_IMAGE_URL);
            addImageView(imageUrl);
        }

    }

    private void getGraphicImageUrl(String mId) {
        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_GRAPHIC_IMAGE_URL;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put("age_category", mId);

            } catch (JSONException e) {
                e.printStackTrace();
            }
            AppLogger.LogE(TAG, "url" + url);
            AppLogger.LogE(TAG, "requestJson" + requestJson.toString());

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressHUD);
                    try {
                        AppLogger.LogE(TAG, "getGroupStanding Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {

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
                        Utility.StopProgress(mProgressHUD);
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }

    private void addImageView(final String url) {
        LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT);
        layoutParams.gravity = Gravity.CENTER;
        final FrameLayout mImageLayout = new FrameLayout(mContext);
        mImageLayout.setLayoutParams(layoutParams);
        mImageLayout.setForegroundGravity(Gravity.CENTER);
        mImageLayout.setBackgroundColor(Color.WHITE);
        final TouchImageView iv = new TouchImageView(mContext);
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
        iv.setLayoutParams(params);


        Glide.with(mContext)
                .load(url)
                .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                .skipMemoryCache(true)
                .into(new SimpleTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                        iv.setImageBitmap(Utility.scaleBitmap(resource, Utility.getScreenHeight(), Utility.getScreenWidth()));
                    }


                    @Override
                    public void onLoadFailed(Exception e, Drawable errorDrawable) {
                        super.onLoadFailed(e, errorDrawable);
                    }
                });
        mImageLayout.addView(iv);
        ImageView mCloseImage = new ImageView(mContext);
        mCloseImage.setImageResource(R.drawable.icon_close);
        mCloseImage.setPadding(15, 15, 15, 15);
        mCloseImage.setColorFilter(ContextCompat.getColor(mContext, R.color.appColorPrimary), PorterDuff.Mode.SRC_IN);

        final FrameLayout.LayoutParams closeImageParams = new FrameLayout.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        closeImageParams.gravity = Gravity.RIGHT | Gravity.TOP;
        mCloseImage.setLayoutParams(closeImageParams);
        mCloseImage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        mImageLayout.addView(mCloseImage);
        setContentView(mImageLayout);
    }
}
