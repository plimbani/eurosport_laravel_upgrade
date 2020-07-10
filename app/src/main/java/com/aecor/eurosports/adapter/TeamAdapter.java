package com.aecor.eurosports.adapter;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamDetailModel;
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
 * Created by system-local on 29-06-2017.
 */

public class TeamAdapter extends BaseAdapter {
    private LayoutInflater inflater;
    private Context mContext;
    private List<TeamDetailModel> list;

    public TeamAdapter(Context context, List<TeamDetailModel> list) {
        mContext = context;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.list = list;
    }

    @Override
    public int getCount() {
        return list.size();
    }

    @Override
    public TeamDetailModel getItem(int position) {
        return list.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        final ViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            rowview = inflater.inflate(R.layout.layout_listview_textview, null);
            holder = new ViewHolder(rowview);
            rowview.setTag(holder);
        } else {
            holder = (ViewHolder) rowview.getTag();
        }
        TeamDetailModel rowItem = getItem(position);
        String mTeamNameWithGroupName = "";
        if (!Utility.isNullOrEmpty(rowItem.getName())) {
            mTeamNameWithGroupName = rowItem.getName();
        }

        if (!Utility.isNullOrEmpty(rowItem.getCategoryAge())) {
            if (!Utility.isNullOrEmpty(mTeamNameWithGroupName)) {
                mTeamNameWithGroupName = mTeamNameWithGroupName + " ";
            }
            mTeamNameWithGroupName = mTeamNameWithGroupName + "(" + rowItem.getCategoryAge() + ")";
        }
        holder.individual_list_item.setText(mTeamNameWithGroupName);

        holder.iv_flag.setVisibility(View.VISIBLE);
        if (!Utility.isNullOrEmpty(rowItem.getCountryLogo())) {

            Glide.with(mContext)
                    .load(rowItem.getCountryLogo())
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .dontAnimate()
                    .error(R.drawable.globe)
                    .override(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT)
                    .into(holder.iv_flag);

        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            holder.iv_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
        }


        return rowview;
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

}
