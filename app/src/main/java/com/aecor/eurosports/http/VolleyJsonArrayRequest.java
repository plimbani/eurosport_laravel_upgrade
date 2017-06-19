package com.aecor.eurosports.http;

import com.android.volley.AuthFailureError;
import com.android.volley.Response;
import com.android.volley.RetryPolicy;
import com.android.volley.toolbox.JsonArrayRequest;

import org.json.JSONArray;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by system-local on 09-11-2016.
 */

public class VolleyJsonArrayRequest extends JsonArrayRequest {
    public VolleyJsonArrayRequest(int method, String url, String requestBody, Response.Listener<JSONArray> listener, Response.ErrorListener errorListener) {
        super(method, url, requestBody, listener, errorListener);
    }

    @Override
    public Map<String, String> getHeaders() throws AuthFailureError {
        HashMap<String, String> headers = new HashMap<String, String>();
//        headers.put("Content-Type", "application/json; charset=utf-8");
        return headers;
    }

    @Override
    public RetryPolicy getRetryPolicy() {
        // here you can write a custom retry policy
        return super.getRetryPolicy();
    }

    @Override
    public String getBodyContentType() {
        return "application/json; charset=utf-8";
    }

//     @Override
//    protected VolleyError parseNetworkError(VolleyError volleyError) {
//        if (volleyError.networkResponse != null && volleyError.networkResponse.data != null) {
//            VolleyError error = new VolleyError(new String(volleyError.networkResponse.data));
//            volleyError = error;
//        }
//
//        return volleyError;
//    }


}
