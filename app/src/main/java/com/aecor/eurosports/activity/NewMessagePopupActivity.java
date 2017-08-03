package com.aecor.eurosports.activity;

import android.app.Activity;
import android.app.NotificationManager;
import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AlertDialog;
import android.support.v7.view.ContextThemeWrapper;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;

/**
 * Created by system-local on 27-07-2017.
 */

public class NewMessagePopupActivity extends Activity {
    private Context mContext;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mContext = this;
        String message = getIntent().getExtras().getString(AppConstants.ARG_NEW_MESSAGE);
        String title = getIntent().getExtras().getString(AppConstants.ARG_NEW_MESSAGE_TITLE);
        NotificationManager nManager = ((NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE));
        nManager.cancelAll();

        showNewMessagePopup(message,title);
    }

    private void showNewMessagePopup(String messageContent, String title) {
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

}
