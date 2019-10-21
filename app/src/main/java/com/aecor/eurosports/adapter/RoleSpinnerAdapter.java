package com.aecor.eurosports.adapter;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.HomeActivity;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;

import butterknife.BindView;
import butterknife.ButterKnife;

public class RoleSpinnerAdapter extends ArrayAdapter<String> {
    private final String TAG = RoleSpinnerAdapter.class.getSimpleName();

    private Context mContext;
    private String[] data = null;
    private LayoutInflater inflater;

    public RoleSpinnerAdapter(Context context, String[] _data) {
        super(context, R.layout.row_spinner_item, R.id.title, _data);
        this.mContext = context;
        this.data = _data;
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

        if (!Utility.isNullOrEmpty(data[position])) {
            holder.tv_spinner.setText(data[position]);
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
