package com.aecor.eurosports.util;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ActivityManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Rect;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
import android.support.annotation.NonNull;
import android.text.TextUtils;
import android.util.DisplayMetrics;
import android.util.Log;
import android.util.TypedValue;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.Toast;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.FavouritesActivity;
import com.aecor.eurosports.activity.LandingActivity;
import com.aecor.eurosports.activity.SplashActivity;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.ViewDialog;
import com.android.volley.VolleyError;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.List;
import java.util.Locale;
import java.util.regex.Pattern;

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

    public static boolean isSoftKeyBoardOpen(View mParentLayout) {
        Rect r = new Rect();
        mParentLayout.getWindowVisibleDisplayFrame(r);
        int screenHeight = mParentLayout.getRootView().getHeight();

        // r.bottom is the position above soft keypad or device button.
        // if keypad is shown, the r.bottom is smaller than that before.
        int keypadHeight = screenHeight - r.bottom;


        if (keypadHeight > screenHeight * 0.15) { // 0.15 ratio is perhaps enough to determine keypad height.
            // keyboard is opened
            return true;
        } else {
            // keyboard is closed
            return false;
        }

    }

    public static void startProgress(@NonNull Context context) {
        try {
            mProgressHUD = ProgressHUD.show(context, "Loading", new DialogInterface.OnCancelListener() {
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
            mProgressHUD = ProgressHUD.show(context, "Loading", new DialogInterface.OnCancelListener() {
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
            if (data.has("tournament_expired")) {
                message = data.getString("tournament_expired");
                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.error), message, mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                    @Override
                    public void onPositiveButtonClicked() {
                        Intent mFavourites = new Intent(mContext, GetStartedActivity.class);
                        mFavourites.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                        mContext.startActivity(mFavourites);
                    }

                });


            } else {
                if (!isNullOrEmpty(message)) {

                    ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.error), message, mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                        @Override
                        public void onPositiveButtonClicked() {
                            if (mContext instanceof SplashActivity) {
                                Intent mLandingPageIntent = new Intent(mContext, LandingActivity.class);
                                mContext.startActivity(mLandingPageIntent);
                                ((Activity) mContext).finish();
                            }
                        }

                    });
                }
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

    public static float dpToPx(Context context, float valueInDp) {
        DisplayMetrics metrics = context.getResources().getDisplayMetrics();
        return TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, valueInDp, metrics);
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

    public static String getDeviceInformation(Context mContext) {
        Log.i("TAG", "SERIAL: " + Build.SERIAL);
        Log.i("TAG", "MODEL: " + Build.MODEL);
        Log.i("TAG", "ID: " + Build.ID);
        Log.i("TAG", "Manufacture: " + Build.MANUFACTURER);
        Log.i("TAG", "brand: " + Build.BRAND);
        Log.i("TAG", "type: " + Build.TYPE);
        Log.i("TAG", "user: " + Build.USER);
        Log.i("TAG", "BASE: " + Build.VERSION_CODES.BASE);
        Log.i("TAG", "INCREMENTAL " + Build.VERSION.INCREMENTAL);
        Log.i("TAG", "SDK  " + Build.VERSION.SDK);
        Log.i("TAG", "BOARD: " + Build.BOARD);
        Log.i("TAG", "BRAND " + Build.BRAND);
        Log.i("TAG", "HOST " + Build.HOST);
        Log.i("TAG", "FINGERPRINT: " + Build.FINGERPRINT);
        Log.i("TAG", "Version Code: " + Build.VERSION.RELEASE);
        return Build.MODEL + Build.MANUFACTURER + Build.BRAND;

    }

    public static String getOsVersion(Context mContext) {

        return android.os.Build.VERSION.RELEASE + "";

    }

    public static boolean isValidEmail(CharSequence target) {
        return !TextUtils.isEmpty(target) && android.util.Patterns.EMAIL_ADDRESS.matcher(target).matches();
    }

    public static String getUserId(Context mContext) {
        AppPreference mPreference
                = AppPreference.getInstance(mContext);
        return mPreference.getString(AppConstants.PREF_USER_ID);
    }


    public static String getFormattedTournamentDate(String startDateStr, String endDateStr, String language, Context mContext) {
        String torunamentFormatedDate = "";
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", new Locale(language));
        try {
            int startYear;
            int startMonth;
            int startDate;
            int endYear;
            int endMonth;
            int endDate;

            Date start = sdf.parse(startDateStr);
            Date end = sdf.parse(endDateStr);
            Calendar mCal = Calendar.getInstance();
            mCal.setTime(start);

            startYear = mCal.get(Calendar.YEAR);
            startMonth = mCal.get(Calendar.MONTH);
            startDate = mCal.get(Calendar.DAY_OF_MONTH);

            mCal.setTime(end);

            endYear = mCal.get(Calendar.YEAR);
            endMonth = mCal.get(Calendar.MONTH);
            endDate = mCal.get(Calendar.DAY_OF_MONTH);

            torunamentFormatedDate = startDate + " - " + endDate;
            String startMonthLocaleString;
            String endMonthLocaleString;
            startMonthLocaleString = mContext.getResources().getStringArray(R.array.month_names)[startMonth];
            endMonthLocaleString = mContext.getResources().getStringArray(R.array.month_names)[endMonth];
            if (startMonth == endMonth) {
                torunamentFormatedDate = torunamentFormatedDate + " " + startMonthLocaleString;
            } else {
                torunamentFormatedDate = startDate + " " + startMonthLocaleString + " - " + endDate + " " + endMonthLocaleString;
            }

            if (startYear == endYear) {
                torunamentFormatedDate = torunamentFormatedDate + " " + startYear;
            } else {
                torunamentFormatedDate = startDate + " " + startMonthLocaleString + " " + startYear + " - " + endDate + " " + endMonthLocaleString + " " + endYear;

            }
        } catch (ParseException e) {
            e.printStackTrace();
        }

        return torunamentFormatedDate;
    }

    public static String getDateFromDateTime(String dateTime, String language, Context mContext) throws ParseException {

        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", new Locale(language));
        Date d = df.parse(dateTime);
        Calendar myCal = new GregorianCalendar();
        myCal.setTime(d);
        String formattedDate = myCal.get(Calendar.DAY_OF_MONTH) + "\n" + mContext.getResources().getStringArray(R.array.month_names_short)[myCal.get(Calendar.MONTH)];
        return formattedDate;
    }

    public static String getDateTimeFromServerDate(String dateTime, String language, Context mContext) throws ParseException {

        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", new Locale(language));
        Date d = df.parse(dateTime);
        Calendar myCal = new GregorianCalendar();
        myCal.setTime(d);
        String curTime = String.format("%02d:%02d", myCal.get(Calendar.HOUR_OF_DAY), myCal.get(Calendar.MINUTE));

        String formattedDate = curTime + " " + (myCal.get(Calendar.DAY_OF_MONTH) < 10 ? ("0" + myCal.get(Calendar.DAY_OF_MONTH)) : (myCal.get(Calendar.DAY_OF_MONTH))) + " " + mContext.getResources().getStringArray(R.array.month_names)[myCal.get(Calendar.MONTH)] + " " + myCal.get(Calendar.YEAR);

        AppLogger.LogE(TAG, "df.format(d)" + df.format(d));
        return formattedDate;
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

    public static boolean isPackageInstalled(Context context, String packageName) {
        final PackageManager packageManager = context.getPackageManager();
        Intent intent = packageManager.getLaunchIntentForPackage(packageName);
        if (intent == null) {
            return false;
        }
        List<ResolveInfo> list = packageManager.queryIntentActivities(intent, PackageManager.MATCH_DEFAULT_ONLY);
        return list.size() > 0;
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


    public static void updateRecentTaskHeaderColor(Context mContext) {
        try {
            ActivityManager.TaskDescription taskDescription = null;
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                Bitmap icon = BitmapFactory.decodeResource(
                        mContext.getResources(), R.mipmap.ic_launcher);

                taskDescription = new ActivityManager.TaskDescription(mContext
                        .getResources().getString(R.string.app_name), icon,
                        mContext.getResources().getColor(R.color.black));
                ((Activity) mContext).setTaskDescription(taskDescription);
            }
        } catch (Exception e) {
            // TODO: handle exception
            e.printStackTrace();
        }
    }


    public static String normalisedVersion(String version) {
        return normalisedVersion(version, ".", 4);
    }

    public static String normalisedVersion(String version, String sep, int maxWidth) {
        String[] split = Pattern.compile(sep, Pattern.LITERAL).split(version);
        StringBuilder sb = new StringBuilder();
        for (String s : split) {
            sb.append(String.format("%" + maxWidth + 's', s));
        }
        return sb.toString();
    }

    public static int getScreenWidth() {
        return Resources.getSystem().getDisplayMetrics().widthPixels;
    }

    public static int getScreenHeight() {
        return Resources.getSystem().getDisplayMetrics().heightPixels;
    }

    public static boolean compare(String v1, String v2) {
        boolean returnFlag = false;
        String s1 = normalisedVersion(v1);
        String s2 = normalisedVersion(v2);
        int cmp = s1.compareTo(s2);
        String cmpStr = cmp < 0 ? "<" : cmp > 0 ? ">" : "==";
        System.out.printf("result: " + "'%s' %s '%s'%n", v1, cmpStr, v2);
        if (cmpStr.contains("<")) {
            returnFlag = true;

        }
        if (cmpStr.contains(">") || cmpStr.contains("==")) {
            returnFlag = false;
        }
        return returnFlag;
    }
}
