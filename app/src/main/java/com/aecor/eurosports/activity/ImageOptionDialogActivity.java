package com.aecor.eurosports.activity;

import android.Manifest;
import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.annotation.Nullable;
import android.support.annotation.RequiresApi;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.view.Gravity;
import android.view.MotionEvent;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;

import com.aecor.eurosports.R;

import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 28-04-2017.
 */

public class ImageOptionDialogActivity extends Activity {
    private static final int RESULT_LOAD_IMAGE_FROM_GALLERY = 12;
    private static final int RESULT_LOAD_IMAGE_FROM_CAMERA = 15;
    private static final int CAMERA_PERMISSION = 15;
    // Storage Permissions
    private static final int REQUEST_EXTERNAL_STORAGE = 123;
    public static onImageSelectedInterface mCallback;
    private static String[] PERMISSIONS_STORAGE = {
            Manifest.permission.READ_EXTERNAL_STORAGE,
            Manifest.permission.WRITE_EXTERNAL_STORAGE
    };
    private final String TAG = "ImageOptionDialogFragment";
    private Context mContext;
    private Bitmap selectedBitmap = null;
    private Dialog dialog;


    @Override
    public boolean onTouchEvent(MotionEvent event) {
        // If we've received a touch notification that the user has touched
        // outside the app, finish the activity.
        if (MotionEvent.ACTION_OUTSIDE == event.getAction()) {
            finish();
            return true;
        }

        // Delegate everything else to Activity.
        return super.onTouchEvent(event);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setBackgroundDrawable(new ColorDrawable(0));
        // Make us non-modal, so that others can receive touch events.
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_NOT_TOUCH_MODAL, WindowManager.LayoutParams.FLAG_NOT_TOUCH_MODAL);

        // ...but notify us that it happened.
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_WATCH_OUTSIDE_TOUCH, WindowManager.LayoutParams.FLAG_WATCH_OUTSIDE_TOUCH);

        super.onCreate(savedInstanceState);
        mContext = this;
        getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        onCreateDialog(savedInstanceState);
        setFinishOnTouchOutside(true);
    }

    private void launchCameraImageCaptureRequest() {
        Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        startActivityForResult(cameraIntent, RESULT_LOAD_IMAGE_FROM_CAMERA);
    }

    @RequiresApi(api = Build.VERSION_CODES.M)
    @OnClick(R.id.tv_select_gallery)
    protected void onSelectGalleryClicked() {
        verifyStoragePermissions();
    }

    private void selectGalleryImage() {
        Intent i = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
        startActivityForResult(i, RESULT_LOAD_IMAGE_FROM_GALLERY);

    }

    @Override
    public void onRequestPermissionsResult(int requestCode,
                                           String permissions[], int[] grantResults) {
        switch (requestCode) {
            case CAMERA_PERMISSION:
                // If request is cancelled, the result arrays are empty.
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED) {

                    // permission was granted, yay! Do the
                    // contacts-related task you need to do.
                    launchCameraImageCaptureRequest();
                }
                break;
            case REQUEST_EXTERNAL_STORAGE:
                selectGalleryImage();
                break;
            // other 'case' lines to check for other
            // permissions this app might request
        }
    }

    @RequiresApi(api = Build.VERSION_CODES.M)
    @OnClick(R.id.tv_select_camera)
    protected void onSelectCameraClicked() {

        // Here, thisActivity is the current activity
        if (ContextCompat.checkSelfPermission(mContext,
                Manifest.permission.CAMERA)
                != PackageManager.PERMISSION_GRANTED) {

            // Should we show an explanation?
            if (!ActivityCompat.shouldShowRequestPermissionRationale((Activity) mContext,
                    Manifest.permission.CAMERA)) {


                // No explanation needed, we can request the permission.

                requestPermissions(
                        new String[]{Manifest.permission.CAMERA},
                        CAMERA_PERMISSION);

                // MY_PERMISSIONS_REQUEST_READ_CONTACTS is an
                // app-defined int constant. The callback method gets the
                // result of the request.
            }
        } else {
            launchCameraImageCaptureRequest();
        }


    }

    @OnClick(R.id.tv_delete)
    protected void onDeleteImageClicked() {
        mCallback.selectedImageBitmap(null);
        selectedBitmap = null;
        finish();
    }

    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (resultCode == RESULT_OK) {
            if (requestCode == RESULT_LOAD_IMAGE_FROM_CAMERA) {
                Bitmap photo = (Bitmap) data.getExtras().get("data");
                if (photo != null) {
                    //reduce to 70% size; bitmaps produce larger than actual image size
                    selectedBitmap = Bitmap.createScaledBitmap(
                            photo,
                            photo.getWidth() / 10 * 5,
                            photo.getHeight() / 10 * 5,
                            false);

//                    iv_profileImage.setImageBitmap(photo);
                    mCallback.selectedImageBitmap(photo);
                    finish();
                }
                dialog.dismiss();
            } else if (requestCode == RESULT_LOAD_IMAGE_FROM_GALLERY) {
                Uri selectedImage = data.getData();
                String[] filePathColumn = {MediaStore.Images.Media.DATA};

                Cursor cursor = mContext.getContentResolver().query(selectedImage,
                        filePathColumn, null, null, null);
                assert cursor != null;
                cursor.moveToFirst();

                int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
                String picturePath = cursor.getString(columnIndex);
                cursor.close();
                selectedBitmap = BitmapFactory.decodeFile(picturePath);

                if (selectedBitmap != null) {
                    //reduce to 70% size; bitmaps produce larger than actual image size
                    selectedBitmap = Bitmap.createScaledBitmap(
                            selectedBitmap,
                            selectedBitmap.getWidth() / 10 * 5,
                            selectedBitmap.getHeight() / 10 * 5,
                            false);
                    mCallback.selectedImageBitmap(selectedBitmap);
                    finish();
                }
                dialog.dismiss();
            }
        }

    }


    @RequiresApi(api = Build.VERSION_CODES.M)
    private void verifyStoragePermissions() {

        // Here, thisActivity is the current activity
        if (ContextCompat.checkSelfPermission(mContext,
                Manifest.permission.WRITE_EXTERNAL_STORAGE)
                != PackageManager.PERMISSION_GRANTED) {

            // Should we show an explanation?
            if (!ActivityCompat.shouldShowRequestPermissionRationale((Activity) mContext,
                    Manifest.permission.WRITE_EXTERNAL_STORAGE)) {
                // No explanation needed, we can request the permission.

                requestPermissions(
                        PERMISSIONS_STORAGE,
                        REQUEST_EXTERNAL_STORAGE);

                // MY_PERMISSIONS_REQUEST_READ_CONTACTS is an
                // app-defined int constant. The callback method gets the
                // result of the request.


            }
        } else {
            selectGalleryImage();
        }


    }


    public void onCreateDialog(Bundle savedInstanceState) {
        // Set a theme on the dialog builder constructor!
        dialog = new Dialog(mContext, R.style.DialogAnimation);

        if (dialog.getWindow() != null) {
            dialog.getWindow().requestFeature(Window.FEATURE_NO_TITLE);

            dialog.getWindow().setGravity(Gravity.BOTTOM);
            dialog.getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                    WindowManager.LayoutParams.FLAG_FULLSCREEN);
            dialog.getWindow().setBackgroundDrawable(
                    new ColorDrawable(Color.TRANSPARENT));
        }
        dialog.setContentView(R.layout.dialog_image_opration);

        dialog.setCancelable(true);

        dialog.show();
        ButterKnife.bind(this, dialog);


        WindowManager.LayoutParams params = dialog.getWindow()
                .getAttributes();
        params.gravity = Gravity.BOTTOM | Gravity.CENTER_HORIZONTAL;
        params.height = ViewGroup.LayoutParams.WRAP_CONTENT;
        dialog.getWindow().setAttributes(params);
     }

    public interface onImageSelectedInterface {
        void selectedImageBitmap(Bitmap btm);
    }

}