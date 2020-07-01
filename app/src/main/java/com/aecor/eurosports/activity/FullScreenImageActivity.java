package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
 import android.util.Base64;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;

import androidx.annotation.Nullable;
import androidx.core.content.ContextCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.DataSource;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import com.bumptech.glide.load.engine.GlideException;
import com.bumptech.glide.request.RequestListener;
import com.bumptech.glide.request.target.SimpleTarget;
import com.bumptech.glide.request.target.Target;
import com.ortiz.touchview.TouchImageView;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

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

    private void getGraphicImageUrl(final String mId) {
        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_GRAPHIC_IMAGE_URL;
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            AppLogger.LogE(TAG, "url" + url);

            StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    Utility.StopProgress(mProgressHUD);
                    AppLogger.LogE(TAG, "getGroupStanding Response" + response);
                    if (!Utility.isNullOrEmpty(response)) {
                        byte[] decodedString = Base64.decode(response.replace("data:image/png;base64,", "").replace("data:image/jpeg;base64,", "").replace("data:image/jpg;base64,", "")
                                , Base64.DEFAULT);
                        Bitmap decodedByte = BitmapFactory.decodeByteArray(decodedString, 0, decodedString.length);
                        addImageView(decodedByte);
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
            }) {
                @Override
                public byte[] getBody() throws AuthFailureError {
                    HashMap<String, String> params2 = new HashMap<String, String>();
                    params2.put("age_category", mId);
                    return new JSONObject(params2).toString().getBytes();
                }

                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    HashMap<String, String> headers = new HashMap<>();
                    headers.put("Content-Type", "application/json; charset=utf-8");
                    headers.put("IsMobileUser", "true");
                    AppPreference appPreference = AppPreference.getInstance(mContext);
                    if (!Utility.isNullOrEmpty(appPreference.getString(AppConstants.PREF_TOKEN))) {
                        headers.put("Authorization", "Bearer " + appPreference.getString(AppConstants.PREF_TOKEN));
                    }
                    String locale = appPreference.getString(AppConstants.PREF_USER_LOCALE);
                    if (!Utility.isNullOrEmpty(locale)) {
                        headers.put("locale", locale);
                    }

                    return headers;
                }

                @Override
                public String getBodyContentType() {
                    return "application/json; charset=utf-8";
                }
            };
            mQueue.add(stringRequest);
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
                .asBitmap().load(url)
                .diskCacheStrategy(DiskCacheStrategy.NONE)
                .skipMemoryCache(true)
                .listener(new RequestListener<Bitmap>() {
                    @Override
                    public boolean onLoadFailed(@Nullable GlideException e, Object model, Target<Bitmap> target, boolean isFirstResource) {
                        return false;
                    }

                    @Override
                    public boolean onResourceReady(Bitmap resource, Object model, Target<Bitmap> target, DataSource dataSource, boolean isFirstResource) {
                        // resource is your loaded Bitmap
                        iv.setImageBitmap(Utility.scaleBitmap(resource, Utility.getScreenHeight(), Utility.getScreenWidth()));
                        return true;
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

    private void addImageView(final Bitmap bitmap) {
        LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT);
        layoutParams.gravity = Gravity.CENTER;
        final FrameLayout mImageLayout = new FrameLayout(mContext);
        mImageLayout.setLayoutParams(layoutParams);
        mImageLayout.setForegroundGravity(Gravity.CENTER);
        mImageLayout.setBackgroundColor(Color.WHITE);
        final TouchImageView iv = new TouchImageView(mContext);
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
        iv.setLayoutParams(params);

        iv.setImageBitmap(bitmap);

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
