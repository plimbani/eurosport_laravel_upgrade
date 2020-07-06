package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.FinalPlacingModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.DataSource;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.load.engine.GlideException;
import com.bumptech.glide.request.RequestListener;
import com.bumptech.glide.request.target.Target;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by asoni on 05-02-2018.
 * asoni@aecordigital.com
 * Aecor Digital
 */

public class FinalPlacingMatchesAdapter extends RecyclerView.Adapter<FinalPlacingMatchesAdapter.ViewHolder> {

    private final String TAG = GroupAdapter.class.getSimpleName();
    private Context mContext;
    private List<FinalPlacingModel> mFinalPlacingList;

    public FinalPlacingMatchesAdapter(Activity context, List<FinalPlacingModel> list) {
        mContext = context;
        this.mFinalPlacingList = list;
    }

    @Override
    public FinalPlacingMatchesAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_final_placing_matches, parent, false);

        return new FinalPlacingMatchesAdapter.ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(FinalPlacingMatchesAdapter.ViewHolder mHolder, final int position) {
        final FinalPlacingModel mGroupModel = mFinalPlacingList.get(position);
        final ViewHolder viewHolder = (ViewHolder) mHolder;

        String mPlacingName = "";
        if (!Utility.isNullOrEmpty(mGroupModel.getPos())) {
            mPlacingName = mContext.getString(R.string.placing_text_holder) + " " + mGroupModel.getPos().trim();
        }
        viewHolder.tv_team_pos.setText(mPlacingName);
        if (!Utility.isNullOrEmpty(mGroupModel.getTeam_name())) {
            viewHolder.tv_tem_name.setText(mGroupModel.getTeam_name());
            if (!Utility.isNullOrEmpty(mGroupModel.getTeam_logo())) {
                Glide.with(mContext)
                        .asBitmap().load(mGroupModel.getTeam_logo())
                        .diskCacheStrategy(DiskCacheStrategy.NONE)
                        .skipMemoryCache(true)
                        .listener(new RequestListener<Bitmap>() {
                            @Override
                            public boolean onLoadFailed(@Nullable GlideException e, Object model, Target<Bitmap> target, boolean isFirstResource) {
                                return false;
                            }

                            @Override
                            public boolean onResourceReady(Bitmap resource, Object model, Target<Bitmap> target, DataSource dataSource, boolean isFirstResource) {
                                // resource is your loaded Bitmap
                                viewHolder.team_flag.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                                return true;
                            }
                        });
            } else {
                Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                        R.drawable.globe);
                viewHolder.team_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
            }
        }


    }

    @Override
    public int getItemViewType(int position) {
        return position;
    }

    @Override
    public int getItemCount() {
        return mFinalPlacingList.size();
    }

    protected class ViewHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.tv_team_pos)
        protected TextView tv_team_pos;
        @BindView(R.id.tv_tem_name)
        protected TextView tv_tem_name;
        @BindView(R.id.team_flag)
        protected ImageView team_flag;

        public ViewHolder(View rowView) {
            super(rowView);
            ButterKnife.bind(this, rowView);
        }

    }


}