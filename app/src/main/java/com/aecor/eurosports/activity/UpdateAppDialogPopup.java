package com.aecor.eurosports.activity;


import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;

import com.aecor.eurosports.R;

public class UpdateAppDialogPopup extends Activity {
    private final String TAG = "UpdateAppDialogPopup";
    private Context mContext;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        mContext = this;
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        this.setFinishOnTouchOutside(false);

        this.getWindow().setBackgroundDrawable(
                new ColorDrawable(android.graphics.Color.TRANSPARENT));
        super.onCreate(savedInstanceState);
        setContentView(R.layout.update_dialog);
        int width = (int) (getResources().getDisplayMetrics().widthPixels * 0.90);
        getWindow().setLayout(width, ViewGroup.LayoutParams.WRAP_CONTENT);


        initView();

    }


    private static void updatePlayStoreApp(Context mContext) {
        final String my_package_name = mContext
                .getPackageName(); // <- HERE YOUR PACKAGE NAME!!
        String url = "";

        try {
            // Check whether Google Play store is installed or not:
            mContext.getPackageManager().getPackageInfo(
                    "com.android.vending", 0);

            url = "market://details?id=" + my_package_name;
        } catch (final Exception e) {
            url = "https://play.google.com/store/apps/details?id="
                    + my_package_name;
        }

        // Open the app page in Google Play store:
        final Intent intent = new Intent(Intent.ACTION_VIEW,
                Uri.parse(url));
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        mContext.startActivity(intent);
    }

    private void initView() {
        TextView tv_title = (TextView) findViewById(R.id.tv_title);
        TextView tv_message = (TextView) findViewById(R.id.tv_message);
        tv_message.setText(getString(R.string.update));
        tv_title.setText(mContext.getString(R.string.update));
        tv_message.setText(getString(R.string.update_message_new));
        Button tv_positive_button = (Button) findViewById(R.id.tv_positive_button);
        tv_positive_button.setText(mContext.getString(R.string.update));
        Button tv_negativeButton = (Button) findViewById(R.id.tv_negativeButton);
        tv_negativeButton.setText(mContext.getString(R.string.cancel));
        tv_negativeButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent j = new Intent(Intent.ACTION_MAIN);
                j.addCategory(Intent.CATEGORY_HOME);
                j.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                mContext.startActivity(j);
                ((Activity) mContext).finish();

                System.exit(0);

                clearFlags();

            }
        });

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                updatePlayStoreApp(mContext);

            }
        });
    }

    @Override
    public void onBackPressed() {
//        super.onBackPressed();
    }

    @Override
    public void onAttachedToWindow() {
        super.onAttachedToWindow();
        //Screen On
//        setFinishOnTouchOutside(false);
        getWindow().addFlags(
                WindowManager.LayoutParams.FLAG_SHOW_WHEN_LOCKED
                        | WindowManager.LayoutParams.FLAG_DISMISS_KEYGUARD
                        | WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON
                        | WindowManager.LayoutParams.TYPE_SYSTEM_ALERT
                        | WindowManager.LayoutParams.FLAG_TURN_SCREEN_ON);


    }

    private void clearFlags() {
        //Don't forget to clear the flags at some point in time.
        getWindow().clearFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN |
                WindowManager.LayoutParams.FLAG_SHOW_WHEN_LOCKED
                | WindowManager.LayoutParams.FLAG_DISMISS_KEYGUARD
                | WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON
                | WindowManager.LayoutParams.FLAG_TURN_SCREEN_ON);
    }

}
