package com.aecor.eurosports.util;

import android.Manifest;
import android.accounts.Account;
import android.accounts.AccountManager;
import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.pm.PackageManager;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Rect;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.StrictMode;
import android.support.v4.app.ActivityCompat;
import android.text.format.DateFormat;
import android.util.DisplayMetrics;
import android.util.Log;
import android.util.TypedValue;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.Toast;

import java.net.URL;
import java.net.URLConnection;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.regex.Matcher;
import java.util.regex.Pattern;


/**
 * Created by system-local on 03-06-2016.
 */
public class Utility {
    private final static String TAG = "Utility";
    private static Dialog progressDialog;

    public static void showToast(Context mContext, String message) {
        Toast.makeText(mContext, message, Toast.LENGTH_LONG).show();
    }

/*

    public static void startProgress(Context context) {
        try {
            progressDialog = new Dialog(context, R.style.AppCompatDialogStyle);
            progressDialog.setContentView(R.layout.custom_progress_dialog);
            progressDialog.setCancelable(false);
            StopProgress();
            progressDialog.show();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void startSmallProgress(Context context) {
        try {
            progressDialog = new Dialog(context, R.style.AppCompatDialogStyle);
            progressDialog.setContentView(R.layout.small_progress_bar);
            progressDialog.setCancelable(false);
            StopProgress();
            progressDialog.show();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void StopProgress() {
        try {
            if (progressDialog != null) {
                progressDialog.dismiss();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    } */

    public static String getMailId(Context context) {

//        return "chris.gartside@lanesgroup.com";
        String strGmail = "";
        if (ActivityCompat.checkSelfPermission(context, Manifest.permission.GET_ACCOUNTS) != PackageManager.PERMISSION_GRANTED) {
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return null;
        }
        Account[] accounts = AccountManager.get(context).getAccounts();

        for (Account account : accounts) {

            String possibleEmail = account.name;
            String type = account.type;

            if (type.equals("com.google")
                    && possibleEmail.contains("lanesgroup.com")) {
                strGmail = possibleEmail;
                break;
            }
        }

        /*return strGmail;
        return "asoni@aecordigital.com";
        return "ndeopura@aecordigital.com";
        return "richard.stenson@lanesgroup.com";
        return "richard.stenson@lanesgroup.com";
        return "mdilokani@lanesgroup.com";
        return "chris.gartside@lanesgroup.com";
        return "jjangir@lanesgroup.com";
        return "asoni@aecordigital.com";*/

        return "rstenson@aecordigital.com";
    }

    public static String getFormattedDate(Context context, String dateTime) throws ParseException {

        Date date = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.ENGLISH).parse(dateTime);
        long smsTimeInMilis = date.getTime();

        Calendar smsTime = Calendar.getInstance();
        smsTime.setTimeInMillis(smsTimeInMilis);

        Calendar now = Calendar.getInstance();

        if (now.get(Calendar.DATE) == smsTime.get(Calendar.DATE)) {
            // For today
            return DateFormat.format("HH:mm", smsTime).toString();
        } else {
            return DateFormat.format("dd MMM yy", smsTime).toString();
        }
    }

    public static boolean isNullOrEmpty(String string) {
        return string == null || string.equalsIgnoreCase("")
                || string.equalsIgnoreCase("null");
    }

    @SuppressWarnings("deprecation")
    public static boolean isInternetAvailable(Context context) {

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


    public static String getCurrentDateAndTime() {
        Calendar c = Calendar.getInstance();
        SimpleDateFormat df = null;
        System.out.println("Current time => " + c.getTime());
        df = new SimpleDateFormat("HH:mm d MMM yyyy");
        String formattedDate = df.format(c.getTime());
        return formattedDate;
    }

    public static String getCurrentDateAndTimeForAlert() {
        Calendar c = Calendar.getInstance();
        SimpleDateFormat df = null;
        System.out.println("Current time => " + c.getTime());
        df = new SimpleDateFormat("HH:mm d MMMM yyyy");
        String formattedDate = df.format(c.getTime());
        return formattedDate;
    }

    public static String getUrlFromImgTag(String imgTag) {
        String url = null;

        Pattern p = Pattern.compile("src='[^']*'", Pattern.CASE_INSENSITIVE);
        Matcher m = p.matcher(imgTag);
        if (m.find()) {
            url = imgTag.substring(m.start() + 5, m.end() - 1);
        }

        return url;
    }

    public static Bitmap drawTextToBitmap(Context gContext,
                                          int gResId,
                                          String gText) {
        Resources resources = gContext.getResources();
        float scale = resources.getDisplayMetrics().density;
        Bitmap bitmap =
                BitmapFactory.decodeResource(resources, gResId);

        Bitmap.Config bitmapConfig =
                bitmap.getConfig();
        // set default bitmap config if none
        if (bitmapConfig == null) {
            bitmapConfig = Bitmap.Config.ARGB_8888;
        }
        // resource bitmaps are imutable,
        // so we need to convert it to mutable one
        bitmap = bitmap.copy(bitmapConfig, true);

        Canvas canvas = new Canvas(bitmap);
        // new antialised Paint
        Paint paint = new Paint();


        // text color - #3D3D3D
        paint.setColor(Color.WHITE);
        // text size in pixels
        paint.setTextSize((int) (14 * scale));
        // text shadow
        paint.setShadowLayer(1f, 0f, 1f, Color.WHITE);

        // draw text to the Canvas center
        Rect bounds = new Rect();
        paint.getTextBounds(gText, 0, gText.length(), bounds);
        int x = (bitmap.getWidth() - bounds.width()) / 2;
        int y = (bitmap.getHeight() + bounds.height()) / 2;
        paint.setAntiAlias(true);
//        paint.setTypeface(FontCache.getTypeface("fonts/Lato-Regular.ttf", gContext));


        canvas.drawText(gText, x, y, paint);

        return bitmap;
    }

    /*public static Bitmap getDepartmentBitmap(Context gContext, String department) {
        Bitmap bitmap = null;
        if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_HUMAN_RESOURCES)) {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_human_resources, gContext.getResources().getString(R.string.hr));
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_GENERAL_MANAGEMENT)) {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_general_management, gContext.getResources().getString(R.string.gm));
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_TRANSPORT_MANAGEMENT)) {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_transport_management, gContext.getResources().getString(R.string.tm));
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_HEALTH_AND_SAFETY)) {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_health_and_safety, gContext.getResources().getString(R.string.has));
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_COMPLIANCE)) {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_compliance, gContext.getResources().getString(c));
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_FINANCE)) {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_finance, gContext.getResources().getString(R.string.f));
        } else {
            bitmap = Utility.drawTextToBitmap(gContext, R.drawable.icon_no_department, gContext.getResources().getString(R.string.na));
        }
        return bitmap;
    }

    public static String getDepartmentText(Context gContext, String department) {
        String departmentShortForm = "";
        if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_HUMAN_RESOURCES)) {
            departmentShortForm = gContext.getResources().getString(R.string.hr);
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_GENERAL_MANAGEMENT)) {
            departmentShortForm = gContext.getResources().getString(R.string.gm);
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_TRANSPORT_MANAGEMENT)) {
            departmentShortForm = gContext.getResources().getString(R.string.tm);
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_HEALTH_AND_SAFETY)) {
            departmentShortForm = gContext.getResources().getString(R.string.has);
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_COMPLIANCE)) {
            departmentShortForm = gContext.getResources().getString(c);
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_FINANCE)) {
            departmentShortForm = gContext.getResources().getString(R.string.f);
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_LANES_GROUP)) {
            departmentShortForm = gContext.getResources().getString(R.string.na);
        }
        return departmentShortForm;
    }

    public static int getDepartmentResourceId(Context gContext, String department) {
        int departmentResourceId = R.drawable.icon_human_resources;
        if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_HUMAN_RESOURCES)) {
            departmentResourceId = R.drawable.icon_human_resources;
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_GENERAL_MANAGEMENT)) {
            departmentResourceId = R.drawable.icon_general_management;
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_TRANSPORT_MANAGEMENT)) {
            departmentResourceId = R.drawable.icon_transport_management;
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_HEALTH_AND_SAFETY)) {
            departmentResourceId = R.drawable.icon_health_and_safety;
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_COMPLIANCE)) {
            departmentResourceId = R.drawable.icon_compliance;
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_FINANCE)) {
            departmentResourceId = R.drawable.icon_finance;
        } else if (department.equalsIgnoreCase(AppConstants.DEPARTMENT_TYPE_LANES_GROUP)) {
            departmentResourceId = R.drawable.icon_no_department;
        }
        return departmentResourceId;
    }*/

    public static float pixelsToSp(Context context, float px) {
        float scaledDensity = context.getResources().getDisplayMetrics().scaledDensity;
        return px / scaledDensity;
    }

    public static void hideKeyboard(Context ctx) {
        InputMethodManager inputManager = (InputMethodManager) ctx
                .getSystemService(Context.INPUT_METHOD_SERVICE);

        // check if no view has focus:
        View v = ((Activity) ctx).getCurrentFocus();
        if (v == null)
            return;

        inputManager.hideSoftInputFromWindow(v.getWindowToken(), 0);
    }

    public static void updateLauncherCout(int count, Context mContext) {
        try {
           /* boolean successRemoveBadge = ShortcutBadger.removeCount(mContext);
            if (successRemoveBadge) {
                boolean successUpdateBadge = ShortcutBadger.applyCount(mContext, count);
            } */
        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    public static String getMimeType(String url) {
        String mimeType = null;

        // this is to handle call from main thread
        StrictMode.ThreadPolicy prviousThreadPolicy = StrictMode.getThreadPolicy();

        // temporary allow network access main thread
        // in order to get mime type from content-type

        StrictMode.ThreadPolicy permitAllPolicy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(permitAllPolicy);

        try {
            URLConnection connection = new URL(url).openConnection();
            connection.setConnectTimeout(150);
            connection.setReadTimeout(150);
            mimeType = connection.getContentType();
            Log.i(TAG, "mimeType from content-type " + mimeType);
        } catch (Exception ignored) {
        } finally {
            // restore main thread's default network access policy
            StrictMode.setThreadPolicy(prviousThreadPolicy);
        }

        if (mimeType == null) {
            // Our B plan: guessing from from url
            try {
                mimeType = URLConnection.guessContentTypeFromName(url);
            } catch (Exception ignored) {
            }
            Log.i(TAG, "mimeType guessed from url " + mimeType);
        }
        return mimeType;
    }

    public static void showSoftKeyboard(Context mContext, EditText et) {
        InputMethodManager imm = (InputMethodManager) mContext.getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.showSoftInput(et, InputMethodManager.SHOW_IMPLICIT);
    }

    public static float dpToPx(Context context, float valueInDp) {
        DisplayMetrics metrics = context.getResources().getDisplayMetrics();
        return TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, valueInDp, metrics);
    }
}
