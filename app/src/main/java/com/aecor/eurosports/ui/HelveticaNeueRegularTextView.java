package com.aecor.eurosports.ui;

import android.content.Context;
import android.graphics.Typeface;
import android.util.AttributeSet;

import androidx.appcompat.widget.AppCompatTextView;

/**
 * Created by system-local on 11-07-2017.
 */

public class HelveticaNeueRegularTextView extends AppCompatTextView {

    public HelveticaNeueRegularTextView(Context context) {
        super(context);

        applyCustomFont(context);
    }

    public HelveticaNeueRegularTextView(Context context, AttributeSet attrs) {
        super(context, attrs);

        applyCustomFont(context);
    }

    public HelveticaNeueRegularTextView(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);

        applyCustomFont(context);
    }

    private void applyCustomFont(Context context) {
        Typeface customFont = FontCache.getTypeface("fonts/HelveticaNeue.ttf", context);
        setTypeface(customFont);
    }
}
