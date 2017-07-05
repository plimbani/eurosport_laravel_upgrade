package com.aecor.eurosports.http;

import android.content.Context;

import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NetworkResponse;
import com.android.volley.ParseError;
import com.android.volley.Response;
import com.android.volley.RetryPolicy;
import com.android.volley.toolbox.HttpHeaderParser;
import com.android.volley.toolbox.JsonObjectRequest;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by system-local on 06-06-2016.
 */
public class VolleyJsonObjectRequest extends JsonObjectRequest {
    private final String TAG = "VolleyJsonObjectRequest";
    private String token;
    private AppPreference mAppPreference;

    public VolleyJsonObjectRequest(Context mContext, int method, String url, JSONObject jsonRequest,
                                   Response.Listener<JSONObject> listener,
                                   Response.ErrorListener errorListener) {
        super(method, url, jsonRequest, listener, errorListener);
        setRetryPolicy(new DefaultRetryPolicy(30000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        mAppPreference = AppPreference.getInstance(mContext);
        token = mAppPreference.getString(AppConstants.PREF_TOKEN);
        AppLogger.LogE(TAG, "***method***" + method);
        AppLogger.LogE(TAG, "***url***" + url);
        AppLogger.LogE(TAG, "***jsonRequest***" + jsonRequest);
        AppLogger.LogE(TAG, "***authtoken***" + token);


    }

    @Override
    protected Response<JSONObject> parseNetworkResponse(NetworkResponse response) {
        try {
            String jsonString = new String(response.data,
                    HttpHeaderParser.parseCharset(response.headers, PROTOCOL_CHARSET));

            JSONObject result = null;

            if (jsonString != null && jsonString.length() > 0)
                result = new JSONObject(jsonString);

            return Response.success(result,
                    HttpHeaderParser.parseCacheHeaders(response));
        } catch (UnsupportedEncodingException e) {
            return Response.error(new ParseError(e));
        } catch (JSONException je) {
            return Response.error(new ParseError(je));
        }
    }

    @Override
    public Map<String, String> getHeaders() throws AuthFailureError {
        HashMap<String, String> headers = new HashMap<String, String>();
        headers.put("Content-Type", "application/json; charset=utf-8");
        headers.put("IsMobileUser", "true");
        if (!Utility.isNullOrEmpty(token)) {
            headers.put("Authorization", "Bearer " + token);
        }
        String locale = mAppPreference.getString(AppConstants.PREF_USER_LOCALE);
        if (!Utility.isNullOrEmpty(locale)) {
            headers.put("locale", locale);
        }

        return headers;
    }

    @Override
    public String getBodyContentType() {
        return "application/json; charset=utf-8";
    }

}
