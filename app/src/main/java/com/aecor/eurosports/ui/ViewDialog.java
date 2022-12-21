package com.aecor.eurosports.ui;

import android.app.Activity;
import android.app.Dialog;
import android.graphics.drawable.ColorDrawable;
import android.text.Html;
import android.text.method.LinkMovementMethod;
import android.text.util.Linkify;
import android.view.Gravity;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.TextView;

import androidx.annotation.NonNull;

import com.aecor.eurosports.R;


/**
 * Created by system-local on 29-03-2017.
 */

public class ViewDialog {
    public static void showSingleButtonDialog(@NonNull Activity activity, String title, String msg, String buttonText, @NonNull final CustomDialogInterface dialogCallback) {
        final Dialog dialog = new Dialog(activity);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.single_button_dialog);

        TextView tv_title = (TextView) dialog.findViewById(R.id.tv_title);
        tv_title.setText(title);
        TextView tv_message = (TextView) dialog.findViewById(R.id.tv_message);
        tv_message.setText(msg);

        TextView tv_positive_button = (TextView) dialog.findViewById(R.id.tv_positive_button);
        tv_positive_button.setText(buttonText);

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
                dialogCallback.onPositiveButtonClicked();
            }
        });
        int width = (int) (activity.getResources().getDisplayMetrics().widthPixels * 0.90);

        dialog.getWindow().setLayout(width, WindowManager.LayoutParams.WRAP_CONTENT);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        try {
            if (dialog != null) {
                dialog.show();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    public static void showTwoButtonDialog(@NonNull Activity activity, String title, String msg, String positiveButtonText, String negativeButtonText, @NonNull final CustomDialogInterface dialogCallback) {
        final Dialog dialog = new Dialog(activity);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.double_button_dialog);

        TextView tv_title = (TextView) dialog.findViewById(R.id.tv_title);
        tv_title.setText(title);
        TextView tv_message = (TextView) dialog.findViewById(R.id.tv_message);
        tv_message.setText(msg);

        TextView tv_positive_button = (TextView) dialog.findViewById(R.id.tv_positive_button);
        tv_positive_button.setText(positiveButtonText);
        TextView tv_negativeButton = (TextView) dialog.findViewById(R.id.tv_negativeButton);
        tv_negativeButton.setText(negativeButtonText);

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
                dialogCallback.onPositiveButtonClicked();
            }
        });
        tv_negativeButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });

        int width = (int) (activity.getResources().getDisplayMetrics().widthPixels * 0.90);

        dialog.getWindow().setLayout(width, WindowManager.LayoutParams.WRAP_CONTENT);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));

        dialog.show();

    }

    public static void showTwoButtonDialog(@NonNull Activity activity, String title, String msg, String positiveButtonText, String negativeButtonText, @NonNull final UpdateDialogInterface dialogCallback) {
        final Dialog dialog = new Dialog(activity);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.double_button_dialog);

        TextView tv_title = (TextView) dialog.findViewById(R.id.tv_title);
        tv_title.setText(title);
        TextView tv_message = (TextView) dialog.findViewById(R.id.tv_message);
        tv_message.setText(msg);

        TextView tv_positive_button = (TextView) dialog.findViewById(R.id.tv_positive_button);
        tv_positive_button.setText(positiveButtonText);
        TextView tv_negativeButton = (TextView) dialog.findViewById(R.id.tv_negativeButton);
        tv_negativeButton.setText(negativeButtonText);

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
                dialogCallback.onPositiveButtonClicked();
            }
        });
        tv_negativeButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
                dialogCallback.onNegativeButtonClicked();
            }
        });

        int width = (int) (activity.getResources().getDisplayMetrics().widthPixels * 0.90);

        dialog.getWindow().setLayout(width, WindowManager.LayoutParams.WRAP_CONTENT);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));

        dialog.show();

    }

    public static void showContactDialog(@NonNull Activity activity, String title, String msg, String positiveButtonText, String negativeButtonText, @NonNull final CustomDialogInterface dialogCallback) {
        final Dialog dialog = new Dialog(activity);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.single_button_dialog);

        TextView tv_title = (TextView) dialog.findViewById(R.id.tv_title);
        tv_title.setText(title);
        TextView tv_message = (TextView) dialog.findViewById(R.id.tv_message);
        tv_message.setText(Html.fromHtml(msg));
        tv_message.setGravity(Gravity.LEFT);
        Linkify.addLinks(tv_message, Linkify.PHONE_NUMBERS);
        tv_message.setMovementMethod(LinkMovementMethod.getInstance());
        TextView tv_positive_button = (TextView) dialog.findViewById(R.id.tv_positive_button);
        tv_positive_button.setText(positiveButtonText);

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
                dialogCallback.onPositiveButtonClicked();
            }
        });

        int width = (int) (activity.getResources().getDisplayMetrics().widthPixels * 0.90);

        dialog.getWindow().setLayout(width, WindowManager.LayoutParams.WRAP_CONTENT);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));

        dialog.show();

    }


    public interface CustomDialogInterface {
        void onPositiveButtonClicked();

    }

    public interface UpdateDialogInterface {
        void onPositiveButtonClicked();

        void onNegativeButtonClicked();

    }

    public interface InputDialogInterface {
        void onDoneButtonClicked(String inputText);

    }
}