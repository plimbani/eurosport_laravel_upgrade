package com.aecor.eurosports.util;

import android.content.Context;
import android.content.SharedPreferences;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.model.TournamentModel;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.Set;

/**
 * Created by asoni on 02-06-2016.
 */
public class AppPreference {

    private static final String PREF_NAME = "com.aecor.eurosports.PREF_NAME";
    private static AppPreference sInstance;
    private final SharedPreferences mPref;

    private AppPreference(@NonNull Context context) {
        mPref = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
    }

    public static synchronized void initializeInstance(@NonNull Context context) {
        if (sInstance == null) {
            sInstance = new AppPreference(context);
        }
    }

    public static synchronized AppPreference getInstance(@NonNull Context context) {
        if (sInstance == null) {
            sInstance = new AppPreference(context);
        }
        return sInstance;
    }

    public long getLong(String key) {
        return mPref.getLong(key, 0);
    }

    public void setLong(String key, long value) {
        mPref.edit()
                .putLong(key, value)
                .apply();
    }

    public long getInt(String key) {
        return mPref.getInt(key, 0);
    }

    public void setInt(String key, int value) {
        mPref.edit()
                .putInt(key, value)
                .apply();
    }

    public boolean getBoolean(String key) {
        return mPref.getBoolean(key, false);
    }

    public void setBoolean(String key, boolean value) {
        mPref.edit()
                .putBoolean(key, value)
                .apply();
    }

    @Nullable
    public String getString(String key) {
        return mPref.getString(key, "");
    }

    @Nullable
    public Set<String> getStringSet(String key) {
        return mPref.getStringSet(key, new HashSet<String>());
    }

    public void setString(String key, String value) {
        mPref.edit()
                .putString(key, value)
                .apply();
    }

    public void setStringSet(String key, Set<String> value) {
        mPref.edit()
                .putStringSet(key, value)
                .apply();
    }


    public void remove(String key) {
        mPref.edit()
                .remove(key)
                .apply();
    }

    public void clear() {
        String fcmDeviceToken = getString(AppConstants.FIREBASE_TOKEN);
        String mRememberEmail = getString(AppConstants.KEY_REMEMBER_EMAIL);
        String mRememberPassword = getString(AppConstants.KEY_REMEMBER_PASSWORD);

        mPref.edit()
                .clear()
                .apply();
        setString(AppConstants.FIREBASE_TOKEN, fcmDeviceToken);
        if (!Utility.isNullOrEmpty(mRememberEmail) && !Utility.isNullOrEmpty(mRememberPassword)) {
            setString(AppConstants.KEY_REMEMBER_EMAIL, mRememberEmail);
            setString(AppConstants.KEY_REMEMBER_PASSWORD, mRememberPassword);
        }
    }

    public TournamentModel[] getTournamentList(Context context) {
        String tournamentList = mPref.getString(AppConstants.TOURNAMENT_LIST, "");
        if (tournamentList == null || tournamentList.trim().length() == 0) {
            return null;
        } else {
            return GsonConverter.getInstance().decodeFromJsonString(tournamentList, TournamentModel[].class);
        }
    }
}
