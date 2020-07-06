package com.aecor.eurosports.activity;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.media.AudioManager;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Vibrator;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.view.ContextThemeWrapper;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;

/**
 * Created by system-local on 27-07-2017.
 */

public class NewMessagePopupActivity extends Activity {
    private Context mContext;
    private AppPreference mAppSharedPref;
    private Vibrator v;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mContext = this;
        String message = getIntent().getExtras().getString(AppConstants.ARG_NEW_MESSAGE);
        String title = getIntent().getExtras().getString(AppConstants.ARG_NEW_MESSAGE_TITLE);
        mAppSharedPref = AppPreference.getInstance(mContext);

        if (!mAppSharedPref.getBoolean(AppConstants.KEY_IS_NOTIFICATION)) {
            finish();
        } else {
            v = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
            if (mAppSharedPref.getBoolean(AppConstants.KEY_IS_VIBRATION)) {
                vibratePhone();
            }
            if (mAppSharedPref.getBoolean(AppConstants.KEY_IS_SOUND)) {
                playDefaultNotificationSound();
            }
            showNewMessagePopup(message, title);
        }
    }

    private void awakeScreen() {
        try {
            Window window = getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_TURN_SCREEN_ON
                    | WindowManager.LayoutParams.FLAG_SHOW_WHEN_LOCKED
                    | WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON
                    | WindowManager.LayoutParams.FLAG_DISMISS_KEYGUARD);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void showNewMessagePopup(String messageContent, String title) {
        awakeScreen();
        AlertDialog.Builder builder = new AlertDialog.Builder(new ContextThemeWrapper(this, R.style.myDialog));
        LayoutInflater inflater = LayoutInflater.from(mContext);
        View dialogView = inflater.inflate(R.layout.single_button_dialog, null);
        TextView tv_title = (TextView) dialogView.findViewById(R.id.tv_title);
        TextView tv_message = (TextView) dialogView.findViewById(R.id.tv_message);
        Button tv_positive_button = (Button) dialogView.findViewById(R.id.tv_positive_button);

        tv_title.setText(title);
        tv_message.setText(messageContent);
        tv_positive_button.setText(getString(R.string.close));
        builder.setView(dialogView);
        final AlertDialog alert = builder.create();
        alert.getWindow().requestFeature(Window.FEATURE_NO_TITLE);
//        alert.getWindow().setType(WindowManager.LayoutParams.TYPE_SYSTEM_ALERT);
        alert.show();

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alert.dismiss();
                finish();
            }
        });
        WindowManager.LayoutParams lp = new WindowManager.LayoutParams();
        Window window = alert.getWindow();
        lp.copyFrom(window.getAttributes());
        int width = (int) (mContext.getResources().getDisplayMetrics().widthPixels * 0.90);

        //This makes the dialog take up the full width
        lp.width = width;
        lp.height = WindowManager.LayoutParams.WRAP_CONTENT;
        window.setAttributes(lp);
    }

    @SuppressLint("MissingPermission")
    private void vibratePhone() {
        // Vibrate for 500 milliseconds
        v.vibrate(500);
    }

    private void playDefaultNotificationSound() {
        try {
            Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
            Ringtone r = RingtoneManager.getRingtone(getApplicationContext(), notification);
            r.setStreamType(AudioManager.STREAM_ALARM);
            r.play();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}
