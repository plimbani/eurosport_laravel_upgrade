package com.aecor.eurosports.http;

import com.aecor.eurosports.util.Utility;
import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Response;
import com.android.volley.RetryPolicy;
import com.android.volley.toolbox.JsonObjectRequest;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by system-local on 06-06-2016.
 */
public class VolleyJsonObjectRequest extends JsonObjectRequest {
    private String token;
    public VolleyJsonObjectRequest(int method, String url, JSONObject jsonRequest,
                                   Response.Listener<JSONObject> listener,
                                   Response.ErrorListener errorListener, String authtoken) {
        super(method, url, jsonRequest, listener, errorListener);
        token = authtoken;
        setRetryPolicy(new DefaultRetryPolicy( 30000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
    }

    public VolleyJsonObjectRequest(int method, String url, JSONObject jsonRequest,
                                   Response.Listener<JSONObject> listener,
                                   Response.ErrorListener errorListener) {
        super(method, url, jsonRequest, listener, errorListener);
        setRetryPolicy(new DefaultRetryPolicy( 30000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        token="";
    }
    @Override
    public Map<String, String> getHeaders() throws AuthFailureError {
        HashMap<String, String> headers = new HashMap<String, String>();
        headers.put("Content-Type", "application/json; charset=utf-8");
        headers.put("IsMobileUser", "true");
        if(!Utility.isNullOrEmpty(token))
            headers.put("Authorization","Bearer " + token);
        return headers;
    }

    @Override
    public String getBodyContentType() {
        return "application/json; charset=utf-8";
    }

    @Override
    public RetryPolicy getRetryPolicy() {
        // here you can write a custom retry policy
        return super.getRetryPolicy();
    }

//    @Override
//    protected VolleyError parseNetworkError(VolleyError volleyError) {
//        if (volleyError.networkResponse != null && volleyError.networkResponse.data != null) {
//            VolleyError error = new VolleyError(new String(volleyError.networkResponse.data));
//            volleyError = error;
//        }
//
//        return volleyError;
//    }

}
