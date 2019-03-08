package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;

public class LinkRecognizerActivity extends BaseActivity {

    private final UriToIntentMapper mMapper = new UriToIntentMapper(this);

    @Override
    protected void initView() {

    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        try {
            mMapper.dispatchIntent(getIntent());
        } catch (IllegalArgumentException iae) {
            if (BuildConfig.DEBUG) {
                Log.e("Deep links", "Invalid URI", iae);
            }
            Utility.showToast(LinkRecognizerActivity.this, "Invalid Url");
            openHomeScreen();

        } finally {
            // Always finish the Activity so that it doesn't stay in our history
            finish();
        }
    }

    public class UriToIntentMapper {
        private Context mContext;

        private UriToIntentMapper(Context context) {
            mContext = context;
        }

        private void dispatchIntent(Intent intent) {
            final Uri uri = intent.getData();
            Intent dispatchIntent = null;

            if (uri == null) throw new IllegalArgumentException("Uri cannot be null");
//api/tournament/openApp
            final String scheme = uri.getScheme().toLowerCase();
            final String host = uri.getHost().toLowerCase();
            Log.e(scheme, host);
            if (("http".equals(scheme) || "https".equals(scheme)) &&
                    ("rishab-eurosport.dev.aecortech.com".equals(host) || "www.rishab-eurosport.dev.aecortech.com".equals(host))) {
                dispatchIntent = mapWebLink(uri);
            }

//            if (appPreference.getBoolean(AppConstants.KEY_IS_LOGGED) && appPreference.getBoolean(AppConstants.KEY_CLUB_SELECT)) {
            if (dispatchIntent != null) {
                mContext.startActivity(dispatchIntent);
            } else {
                openHomeScreen();
            }
//            } else {
//                openLoginScreen();
//            }
        }

        private Intent mapWebLink(Uri uri) {
            final String path = uri.getPath();

            if (path != null) {
//                String idStr = path.substring(path.lastIndexOf('/') + 1);
//                int id = Integer.parseInt(idStr);
                return newCActivityIntent(mContext, 1);
            }
            return null;
        }

        private Intent newCActivityIntent(Context context, int id) {
            Intent i = new Intent(context, HomeActivity.class);

            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            i.putExtra("id", id);
            return i;
        }
    }

    private void openHomeScreen() {
        startActivity(new Intent(LinkRecognizerActivity.this, HomeActivity.class));
        finish();
    }
}
