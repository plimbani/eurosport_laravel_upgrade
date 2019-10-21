package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.TeamListingActivity;
import com.aecor.eurosports.model.ClubModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubAdapter extends RecyclerView.Adapter<ClubAdapter.ViewHolder> implements Filterable {
    private final String TAG = ClubAdapter.class.getSimpleName();
    private Context mContext;
    private List<ClubModel> mClubList;
    private List<ClubModel> mOriginalList;
    private ClubFilter clubFilter;

    public ClubAdapter(Activity context, List<ClubModel> list) {
        mContext = context;
        this.mClubList = list;
        this.mOriginalList = list;
    }


    @Override
    public ClubAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.layout_listview_textview, parent, false);

        return new ClubAdapter.ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(final ClubAdapter.ViewHolder holder, int position) {
        final ClubModel clubModel = mClubList.get(position);
        String mClubTitle = "";
        if (!Utility.isNullOrEmpty(clubModel.getClubName())) {
            mClubTitle = clubModel.getClubName();
        }
//        if (!Utility.isNullOrEmpty(clubModel.getCountryName())) {
//            mClubTitle = mClubTitle + " (" + clubModel.getCountryName() + ")";
//        }

        holder.individual_list_item.setText(mClubTitle);

        holder.ll_list_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent mTeamListIntent = new Intent(mContext, TeamListingActivity.class);
                mTeamListIntent.putExtra(AppConstants.ARG_CLUB_ID, clubModel.getClubId());
                mContext.startActivity(mTeamListIntent);
            }
        });
        holder.iv_flag.setVisibility(View.VISIBLE);
        if (!Utility.isNullOrEmpty(clubModel.getCountryLogo())) {
            Glide.with(mContext)
                    .load(clubModel.getCountryLogo())
                    .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .into(new SimpleTarget<Bitmap>() {
                        @Override
                        public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                            holder.iv_flag.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                        }
                    });
        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            holder.iv_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
        }
    }

    @Override
    public int getItemCount() {
        return mClubList.size();
    }

    @Override
    public Filter getFilter() {
        if (clubFilter == null)
            clubFilter = new ClubFilter();

        return clubFilter;
    }

    public class ViewHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.individual_list_item)
        protected TextView individual_list_item;
        @BindView(R.id.ll_list_parent)
        protected LinearLayout ll_list_parent;
        @BindView(R.id.iv_flag)
        protected ImageView iv_flag;

        public ViewHolder(View rowView) {
            super(rowView);
            ButterKnife.bind(this, rowView);
        }
    }

    private class ClubFilter extends Filter {
        @Override
        protected FilterResults performFiltering(CharSequence constraint) {
            FilterResults results = new FilterResults();
            // We implement here the filter logic
            if (constraint == null || constraint.length() == 0) {
                // No filter implemented we return all the list
                results.values = mOriginalList;
                results.count = mClubList.size();
            } else {
                // We perform filtering operation
                List<ClubModel> mClubList = new ArrayList<>();
                for (ClubModel p : mOriginalList) {
                    if (p.getClubName().toUpperCase().contains(constraint.toString().toUpperCase()))
                        mClubList.add(p);
                }
                results.values = mClubList;
                results.count = mClubList.size();
            }
            return results;
        }

        @Override
        protected void publishResults(CharSequence constraint, FilterResults results) {
            // Now we have to inform the adapter about the new list filtered
//            if (results.count == 0) {
//                notifyDataSetInvalidated();
//            } else {
            mClubList = (List<ClubModel>) results.values;
            notifyDataSetChanged();
//            }
        }
    }
}