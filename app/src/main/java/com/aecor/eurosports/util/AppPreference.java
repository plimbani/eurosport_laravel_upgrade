package com.aecor.eurosports.util;

import android.content.Context;
import android.content.SharedPreferences;

/**
 * Created by asoni on 02-06-2016.
 */
public class AppPreference {

    private static final String PREF_NAME = " com.aecor.gcmcustomnotification.PREF_NAME";
    public static final String KEY_SETTING_IS_SOUND_ENABLE = "key_setting_is_sound_enable";
    public static final String KEY_SETTING_IS_VIBRATION_ENABLE = "key_setting_is_vibration_enable";

    private static AppPreference sInstance;
    private final SharedPreferences mPref;

    private AppPreference(Context context) {
        mPref = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
    }

    public static synchronized void initializeInstance(Context context) {
        if (sInstance == null) {
            sInstance = new AppPreference(context);
        }
    }

    public static synchronized AppPreference getInstance() {
        if (sInstance == null) {
            throw new IllegalStateException(AppPreference.class.getSimpleName() +
                    " is not initialized, call initializeInstance(..) method first.");
        }
        return sInstance;
    }

    public long getLong(String key) {
        return mPref.getLong(key, 0);
    }

    public void setLong(String key, long value) {
        mPref.edit()
                .putLong(key, value)
                .commit();
    }

    public boolean getBoolean(String key) {
        return mPref.getBoolean(key, false);
    }

    public void setBoolean(String key, boolean value) {
        mPref.edit()
                .putBoolean(key, value)
                .commit();
    }

    public String getString(String key) {
        return mPref.getString(key, "");
    }

    public void setString(String key, String value) {
        mPref.edit()
                .putString(key, value)
                .commit();
    }


    public void remove(String key) {
        mPref.edit()
                .remove(key)
                .commit();
    }

    public boolean clear() {
        return mPref.edit()
                .clear()
                .commit();
    }
}
