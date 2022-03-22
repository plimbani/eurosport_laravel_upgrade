package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.HomeActivity;
import com.aecor.eurosports.model.CountriesModel;
import com.aecor.eurosports.util.Utility;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class CountrySpinnerAdapter extends ArrayAdapter<CountriesModel> {
    private final String TAG = TournamentSpinnerAdapter.class.getSimpleName();
    private LayoutInflater inflater;
    private Context mContext;

    public CountrySpinnerAdapter(Activity context, List<CountriesModel> list) {
        super(context, R.layout.row_spinner_item, R.id.title, list);
        this.mContext = context;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);

    }

    @Override
    public boolean isEnabled(int position) {
        return !(position == 0 && !(mContext instanceof HomeActivity));
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return rowview(convertView, position);
    }

    @Override
    public View getDropDownView(int position, View convertView, ViewGroup parent) {
        return rowview(convertView, position);
    }

    private View rowview(View convertView, int position) {
        ViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            rowview = inflater.inflate(R.layout.row_spinner_item, null);
            holder = new ViewHolder(rowview);
            rowview.setTag(holder);
        } else {
            holder = (ViewHolder) rowview.getTag();
        }
        CountriesModel rowItem = getItem(position);
        if (!Utility.isNullOrEmpty(rowItem.getName())) {
            if (position == 0 && !(mContext instanceof HomeActivity)) {
                holder.tv_spinner.setText(rowItem.getName());
            } else {
                holder.tv_spinner.setText(capitalize(rowItem.getName().toLowerCase()));
            }

        }
        if (position == 0 && !(mContext instanceof HomeActivity)) {
            holder.tv_spinner.setTextColor(Color.GRAY);
        } else {
            holder.tv_spinner.setTextColor(Color.BLACK);
        }
        return rowview;
    }

    public static String capitalize(@NonNull String input) {

        String[] words = input.toLowerCase().split(" ");
        StringBuilder builder = new StringBuilder();
        for (int i = 0; i < words.length; i++) {
            String word = words[i];

            if (i > 0 && word.length() > 0) {
                builder.append(" ");
            }

            String cap = word.substring(0, 1).toUpperCase() + word.substring(1);
            builder.append(cap);
        }
        return builder.toString();
    }

    protected class ViewHolder {
        @BindView(R.id.tv_spinner)
        protected TextView tv_spinner;

        public ViewHolder(View rowView) {
            ButterKnife.bind(this, rowView);
        }
    }
}
