package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;

import java.lang.reflect.Type;
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
        holder.tv_spinner.setTypeface(null, Typeface.NORMAL);

        if (rowItem.isShowDivisionOnly()) {
            if (!Utility.isNullOrEmpty(rowItem.getDivisionName())) {
                holder.tv_spinner.setText(rowItem.getDivisionName());
                holder.tv_spinner.setTypeface(null, Typeface.BOLD);
            }
        } else {
            if (!Utility.isNullOrEmpty(rowItem.getDisplay_name())) {
                holder.tv_spinner.setText(rowItem.getDisplay_name());
            }
            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);

            if (!Utility.isNullOrEmpty(rowItem.getDivisionName())) {
                params.setMargins(40, 0, 0, 0);
                holder.tv_spinner.setLayoutParams(params);
            } else {
                params.setMargins(0, 0, 0, 0);
                holder.tv_spinner.setLayoutParams(params);
            }
        }
        holder.tv_spinner.setTextColor(Color.BLACK);

        return rowview;
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    protected class ViewHolder {
        @BindView(R.id.tv_spinner)
        protected TextView tv_spinner;

        public ViewHolder(View rowView) {
            ButterKnife.bind(this, rowView);
        }
    }
}
