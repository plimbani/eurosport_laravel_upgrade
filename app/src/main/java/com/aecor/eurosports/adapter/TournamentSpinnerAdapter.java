package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.HomeActivity;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 19-06-2017.
 */

public class TournamentSpinnerAdapter extends ArrayAdapter<TournamentModel> {
    private final String TAG = TournamentSpinnerAdapter.class.getSimpleName();
    private LayoutInflater inflater;
    private Context mContext;

    public TournamentSpinnerAdapter(Activity context, List<TournamentModel> list) {
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
        AppLogger.LogE(TAG, "pos" + position);
        ViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            rowview = inflater.inflate(R.layout.row_spinner_item, null);
            holder = new ViewHolder(rowview);
            rowview.setTag(holder);
        } else {
            holder = (ViewHolder) rowview.getTag();
        }
        TournamentModel rowItem = getItem(position);
        if (!Utility.isNullOrEmpty(rowItem.getName())) {
            holder.tv_spinner.setText(rowItem.getName());
        }
        if (position == 0 && !(mContext instanceof HomeActivity)) {
            holder.tv_spinner.setTextColor(Color.GRAY);
        } else {
            holder.tv_spinner.setTextColor(Color.BLACK);
        }
        return rowview;
    }

    protected class ViewHolder {
        @BindView(R.id.tv_spinner)
        protected TextView tv_spinner;

        public ViewHolder(View rowView) {
            ButterKnife.bind(this, rowView);
        }
    }
}
