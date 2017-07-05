package com.aecor.eurosports.util;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
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
import com.aecor.eurosports.activity.LandingActivity;
import com.aecor.eurosports.activity.SplashActivity;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.ViewDialog;
import com.android.volley.VolleyError;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.text.DateFormat;
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

    public static ProgressHUD getProgressDialog(@NonNull Context context) {
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
        return mProgressHUD;
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


    public static void parseVolleyError(@NonNull final Context mContext, @NonNull VolleyError error) {
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
                        if (mContext instanceof SplashActivity) {
                            Intent mLandingPageIntent = new Intent(mContext, LandingActivity.class);
                            ((Activity) mContext).startActivity(mLandingPageIntent);
                        }
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

    public static Bitmap scaleBitmap(Bitmap bm, int maxWidth, int maxHeight) {
        int width = bm.getWidth();
        int height = bm.getHeight();


        if (width > height) {
            // landscape
            float ratio = (float) width / maxWidth;
            width = maxWidth;
            height = (int) (height / ratio);
        } else if (height > width) {
            // portrait
            float ratio = (float) height / maxHeight;
            height = maxHeight;
            width = (int) (width / ratio);
        } else {
            // square
            height = maxHeight;
            width = maxWidth;
        }


        bm = Bitmap.createScaledBitmap(bm, width, height, true);
        return bm;
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

    public static void StopProgress(ProgressHUD mProgressHUD) {
        try {

            if (mProgressHUD != null) {
                mProgressHUD.dismiss();
            }

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

    public static String getDateFromDateTime(String dateTime) throws ParseException {

        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Date d = df.parse(dateTime);
        df = new SimpleDateFormat("dd\nMMM");
        return df.format(d);
    }

    public static String getDateTimeFromServerDate(String dateTime) throws ParseException {

        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Date d = df.parse(dateTime);
        df = new SimpleDateFormat("dd MMMM | HH:mm");
        return df.format(d);
    }

    public static Context setLocale(Context context, String language) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.N) {
            return updateResources(context, language);
        }

        return updateResourcesLegacy(context, language);
    }

    @TargetApi(Build.VERSION_CODES.N)
    private static Context updateResources(Context context, String language) {
        Locale locale = new Locale(language);
        Locale.setDefault(locale);

        Configuration configuration = context.getResources().getConfiguration();
        configuration.setLocale(locale);
        configuration.setLayoutDirection(locale);

        return context.createConfigurationContext(configuration);
    }

    @SuppressWarnings("deprecation")
    private static Context updateResourcesLegacy(Context context, String language) {
        Locale locale = new Locale(language);
        Locale.setDefault(locale);

        Resources resources = context.getResources();

        Configuration configuration = resources.getConfiguration();
        configuration.locale = locale;
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
            configuration.setLayoutDirection(locale);
        }

        resources.updateConfiguration(configuration, resources.getDisplayMetrics());

        return context;
    }

}
