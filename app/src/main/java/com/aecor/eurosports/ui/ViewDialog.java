package com.aecor.eurosports.ui;

import android.app.Activity;
import android.app.Dialog;
import android.graphics.drawable.ColorDrawable;
import android.support.annotation.NonNull;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.TextView;
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
        dialog.show();

    }

    public static void showInputDialog(@NonNull Activity activity, String title, String msg, String buttonText, @NonNull final InputDialogInterface dialogCallback) {
        final Dialog dialog = new Dialog(activity);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.input_dialog);

        TextView tv_title = (TextView) dialog.findViewById(R.id.tv_title);
        tv_title.setText(title);
        final EditText tv_message = (EditText) dialog.findViewById(R.id.et_input);
        tv_message.setText(msg);

        TextView tv_positive_button = (TextView) dialog.findViewById(R.id.tv_positive_button);
        tv_positive_button.setText(buttonText);

        tv_positive_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
                dialogCallback.onDoneButtonClicked(tv_message.getText().toString().trim());
            }
        });
        int width = (int) (activity.getResources().getDisplayMetrics().widthPixels * 0.90);

        dialog.getWindow().setLayout(width, WindowManager.LayoutParams.WRAP_CONTENT);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        dialog.show();

    }

    public static void showSingleButtonDialog(@NonNull Activity activity, String title, String msg, String buttonText, int drawableRight, @NonNull final CustomDialogInterface dialogCallback) {
        final Dialog dialog = new Dialog(activity);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.single_button_dialog);

        TextView tv_title = (TextView) dialog.findViewById(R.id.tv_title);
        tv_title.setText(title);
        tv_title.setCompoundDrawablesWithIntrinsicBounds(drawableRight, 0, 0, 0);
        tv_title.setCompoundDrawablePadding(5);
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
        dialog.show();

    }

    public static void showTwoButtonDialog(@NonNull Activity activity, String title, String msg, String positiveButtonText, @NonNull final CustomDialogInterface dialogCallback) {
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


    public interface CustomDialogInterface {
        void onPositiveButtonClicked();

        void onNegativeButtonClicked();
    }

    public interface InputDialogInterface {
        void onDoneButtonClicked(String inputText);

    }
}