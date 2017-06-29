package com.aecor.eurosports.util;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.graphics.Bitmap;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.support.annotation.NonNull;
import android.text.TextUtils;
import android.util.Base64;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.Toast;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyErrorHelper;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.ViewDialog;
import com.android.volley.VolleyError;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.UnsupportedEncodingException;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

import static android.content.Context.INPUT_METHOD_SERVICE;


/**
 * Created by system-local on 03-06-2016.
 */
public class Utility {
    private final static String TAG = "Utility";
    private static Dialog progressDialog;
    private static ProgressHUD mProgressHUD;

    public static void showToast(Context mContext, String message) {
        Toast.makeText(mContext, message, Toast.LENGTH_LONG).show();
    }

    public static void startProgress(@NonNull Context context) {
        try {
            mProgressHUD = ProgressHUD.show(context, "Loading", true, new DialogInterface.OnCancelListener() {
                @Override
                public void onCancel(DialogInterface dialog) {
                    mProgressHUD.dismiss();
                }
            });
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void closeKeyPad(@NonNull Activity activity, @NonNull View v) {
        try {
            InputMethodManager inputManager = (InputMethodManager) activity
                    .getSystemService(INPUT_METHOD_SERVICE);
            inputManager.hideSoftInputFromWindow(v.getWindowToken(),
                    InputMethodManager.HIDE_NOT_ALWAYS);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    public static void parseVolleyError(@NonNull Context mContext, @NonNull VolleyError error) {
        try {

            if (error.networkResponse != null && error.networkResponse.data != null) {
                error = new VolleyError(new String(error.networkResponse.data));
            }
            AppLogger.LogE(TAG, "error" + error);
            AppLogger.LogE(TAG, "error " + error.getMessage());
            String responseBody = error.getMessage();

            JSONObject data = new JSONObject(responseBody);
            String message = null;
            if (data.has("message")) {
                message = data.getString("message");
            }

            if (data.has("error")) {
                message = data.getString("error");

            }
            if (!isNullOrEmpty(message)) {

                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.error), message, mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                    @Override
                    public void onPositiveButtonClicked() {

                    }

                    @Override
                    public void onNegativeButtonClicked() {

                    }
                });
            }

        } catch (@NonNull JSONException e) {
            e.printStackTrace();
        }
    }

    public static String encodeTobase64(Bitmap image) {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        image.compress(Bitmap.CompressFormat.PNG, 100, baos);
        byte[] b = baos.toByteArray();
        String imageEncoded = "data:image/png;base64," + Base64.encodeToString(b, Base64.DEFAULT);
        return imageEncoded;
    }

    public static void setupUI(final Context mContext, View view) {

        // Set up touch listener for non-text box views to hide keyboard.
        if (!(view instanceof EditText)) {
            view.setOnTouchListener(new View.OnTouchListener() {
                public boolean onTouch(@NonNull View v, MotionEvent event) {
                    closeKeyPad((Activity) mContext, v);
                    return false;
                }
            });
        }

        // If a layout container, iterate over children and seed recursion.
        if (view instanceof ViewGroup) {
            for (int i = 0; i < ((ViewGroup) view).getChildCount(); i++) {
                View innerView = ((ViewGroup) view).getChildAt(i);
                setupUI(mContext, innerView);
            }
        }
    }

    public static void StopProgress() {
        try {

            if (mProgressHUD != null) {
                mProgressHUD.dismiss();
            }
            assert mProgressHUD != null;
            mProgressHUD.dismiss();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    public static boolean isNullOrEmpty(String string) {
        return string == null || string.equalsIgnoreCase("")
                || string.equalsIgnoreCase("null");
    }

    @SuppressWarnings("deprecation")
    public static boolean isInternetAvailable(@NonNull Context context) {
        ConnectivityManager connectivity = (ConnectivityManager) context
                .getSystemService(Context.CONNECTIVITY_SERVICE);
        if (connectivity != null) {
            NetworkInfo[] info = connectivity.getAllNetworkInfo();
            if (info != null)
                for (NetworkInfo value : info)
                    if (value.getState() == NetworkInfo.State.CONNECTED) {
                        return true;
                    }

        }
        return false;
    }

    public static boolean isValidEmail(CharSequence target) {
        return !TextUtils.isEmpty(target) && android.util.Patterns.EMAIL_ADDRESS.matcher(target).matches();
    }

    public static String getUserId(Context mContext) {
        AppPreference mPreference
                = AppPreference.getInstance(mContext);
        return mPreference.getString(AppConstants.PREF_USER_ID);
    }

    public static String getFormattedTournamentDate(String startDateStr, String endDateStr) {
        String torunamentFormatedDate = "";
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        try {
            int startYear;
            String startMonth;
            int startDate;
            int endYear;
            String endMonth;
            int endDate;

            Date start = sdf.parse(startDateStr);
            Date end = sdf.parse(endDateStr);
            Calendar mCal = Calendar.getInstance();
            mCal.setTime(start);

            startYear = mCal.get(Calendar.YEAR);
            startMonth = mCal.getDisplayName(Calendar.MONTH, Calendar.LONG, Locale.getDefault());
            startDate = mCal.get(Calendar.DAY_OF_MONTH);

            mCal.setTime(end);

            endYear = mCal.get(Calendar.YEAR);
            endMonth = mCal.getDisplayName(Calendar.MONTH, Calendar.LONG, Locale.getDefault());
            endDate = mCal.get(Calendar.DAY_OF_MONTH);

            torunamentFormatedDate = startDate + " - " + endDate;
            if (startMonth.equalsIgnoreCase(endMonth)) {
                torunamentFormatedDate = torunamentFormatedDate + " " + startMonth;
            } else {
                torunamentFormatedDate = startDate + " " + startMonth + " - " + endDate + " " + endMonth;
            }
            if (startYear == endYear) {
                torunamentFormatedDate = torunamentFormatedDate + " " + startYear;
            } else {
                torunamentFormatedDate = startDate + " " + startMonth + " " + startYear + " - " + endDate + " " + endMonth + " " + endYear;

            }
        } catch (ParseException e) {
            e.printStackTrace();
        }

        return torunamentFormatedDate;
    }

}
