package com.aecor.eurosports.http;

import android.app.ProgressDialog;
import android.content.Context;

import com.aecor.eurosports.util.AppLogger;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;
import org.json.JSONObject;

/**
 * Created by system-local on 07-06-2016.
 */
public class VolleyRequest {
    private final String TAG = VolleyRequest.class.getSimpleName();
    private ProgressDialog progressDialog;
    private Context mContext;
    private boolean isShowDialog;

    public VolleyRequest(Context mContext, boolean isShowDialog) {
        this.mContext = mContext;
    }

    public void volleyStringRequst(String url, String tag) {
        progressDialog.show();
        StringRequest strReq = new StringRequest(url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                AppLogger.LogD(TAG, response.toString());
                progressDialog.hide();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                AppLogger.LogD(TAG, "Error: " + error.getMessage());
                progressDialog.hide();
            }
        });
        // Adding String request to request queue
        VolleySingeltone.getInstance(mContext).addToRequestQueue(strReq, tag);
    }

    public void volleyJsonObjectRequest(String url, String tag) {
        progressDialog.show();
        JsonObjectRequest jsonObjectReq = new JsonObjectRequest(url, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        AppLogger.LogD(TAG, response.toString());


                        progressDialog.hide();

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                AppLogger.LogD(TAG, "Error: " + error.getMessage());
                progressDialog.hide();
            }
        });

        // Adding JsonObject request to request queue
        VolleySingeltone.getInstance(mContext).addToRequestQueue(jsonObjectReq, tag);
    }

    public void volleyJsonArrayRequest(String url, String tag) {
        progressDialog.show();
        JsonArrayRequest jsonArrayReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        AppLogger.LogD(TAG, response.toString());

                        progressDialog.hide();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                AppLogger.LogD(TAG, "Error: " + error.getMessage());
                progressDialog.hide();
            }
        });

        // Adding JsonObject request to request queue
        VolleySingeltone.getInstance(mContext).addToRequestQueue(jsonArrayReq, tag);
    }

    public void volleyImageLoader(String url) {
        ImageLoader imageLoader = VolleySingeltone.getInstance(mContext).getImageLoader();
        imageLoader.get(url, new ImageLoader.ImageListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                AppLogger.LogD(TAG, "Image Load Error: " + error.getMessage());
            }

            @Override
            public void onResponse(ImageLoader.ImageContainer response, boolean arg1) {
                if (response.getBitmap() != null) {

                }
            }
        });
    }
}
