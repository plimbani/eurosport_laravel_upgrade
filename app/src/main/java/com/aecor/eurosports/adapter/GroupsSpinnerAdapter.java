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
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class GroupsSpinnerAdapter extends ArrayAdapter<ClubGroupModel> {
    private final String TAG = TournamentSpinnerAdapter.class.getSimpleName();
    private LayoutInflater inflater;
    private Context mContext;

    public GroupsSpinnerAdapter(Activity context, List<ClubGroupModel> list) {
        super(context, R.layout.row_spinner_item, R.id.title, list);
        this.mContext = context;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);

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
        ClubGroupModel rowItem = getItem(position);
        if (!Utility.isNullOrEmpty(rowItem.getDisplay_name())) {
            holder.tv_spinner.setText(rowItem.getDisplay_name());
        }
        holder.tv_spinner.setTextColor(Color.BLACK);

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
