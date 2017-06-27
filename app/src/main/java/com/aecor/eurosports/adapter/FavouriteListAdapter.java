package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;
import com.squareup.picasso.Picasso;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by karan on 6/22/2017.
 */

public class FavouriteListAdapter extends ArrayAdapter<TournamentModel> {
    private final String TAG = FavouriteListAdapter.class.getSimpleName();
    private LayoutInflater inflater;


    public FavouriteListAdapter(Activity context, int resouceId, int textviewId, List<TournamentModel> list) {
        super(context, resouceId, textviewId,
                list);
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);

    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return rowView(convertView, position);
    }

    private View rowView(View convertView, int position) {
        AppLogger.LogE(TAG, "pos" + position);
        final ViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            rowview = inflater.inflate(R.layout.layout_favourite_textview, null);
            holder = new ViewHolder(rowview);
            rowview.setTag(holder);
        } else {
            holder = (ViewHolder) rowview.getTag();
        }
        TournamentModel rowItem = getItem(position);
        if (!Utility.isNullOrEmpty(rowItem.getName())) {
            holder.favourite_tournament.setText(rowItem.getName());
        }

        if (!Utility.isNullOrEmpty(rowItem.getStart_date()) && !Utility.isNullOrEmpty(rowItem.getEnd_date())) {
            holder.favourite_date.setText(rowItem.getStart_date() + " - " + rowItem.getEnd_date());
        }

        holder.favourite_imageview.setImageDrawable(getContext().getResources().getDrawable(R.drawable.heart_gray));

        if (!Utility.isNullOrEmpty(rowItem.getLogo())) {
            Picasso.with(getContext()).load(rowItem.getLogo()).fit().centerCrop()
                    .into(holder.favourite_logo);
        }

        holder.favourite_imageview.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                holder.favourite_imageview.setImageDrawable(getContext().getResources().getDrawable(R.drawable.heart_red));

            }
        });
        return rowview;
    }

    protected class ViewHolder {
        @BindView(R.id.favourite_logo)
        protected ImageView favourite_logo;
        @BindView(R.id.favourite_imageview)
        protected ImageView favourite_imageview;
        @BindView(R.id.favourite_tournament)
        protected TextView favourite_tournament;
        @BindView(R.id.favourite_date)
        protected TextView favourite_date;

        public ViewHolder(View rowView) {
            ButterKnife.bind(this, rowView);
        }
    }
}
