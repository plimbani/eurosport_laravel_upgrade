package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Parcelable;
 import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.AgeGroupActivity;
import com.aecor.eurosports.activity.GroupSummaryActivity;
import com.aecor.eurosports.activity.TeamListingActivity;
import com.aecor.eurosports.model.AgeGroupModel;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class DivisionGroupAdapter extends RecyclerView.Adapter<DivisionGroupAdapter.ViewHolder> {


    private final String TAG = GroupAdapter.class.getSimpleName();
    private Context mContext;
    private List<ClubGroupModel> mGroupList;
    private AgeGroupModel mAgeGroupData;


    public DivisionGroupAdapter(Activity context, List<ClubGroupModel> list, AgeGroupModel mAgeGroupData) {
        mContext = context;
        this.mGroupList = list;
        this.mAgeGroupData = mAgeGroupData;
    }

    @Override
    public DivisionGroupAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.layout_listview_textview, parent, false);

        return new DivisionGroupAdapter.ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(DivisionGroupAdapter.ViewHolder holder, int position) {
        final ClubGroupModel mGroupModel = mGroupList.get(position);
        if (!Utility.isNullOrEmpty(mGroupModel.getDisplay_name())) {
            if (!Utility.isNullOrEmpty(mGroupModel.getActual_competition_type()) && mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION)) {
                holder.individual_list_item.setText(mGroupModel.getDisplay_name().replace("Group-", ""));
            } else {
                holder.individual_list_item.setText(mGroupModel.getDisplay_name());
            }
        }

        LinearLayout.LayoutParams params = (LinearLayout.LayoutParams)  holder.individual_list_item.getLayoutParams();
        params.setMargins(40, 0, 0, 0);
        holder.individual_list_item.setLayoutParams(params);

        holder.ll_list_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mContext instanceof AgeGroupActivity) {
                    Intent mGroupSummary = new Intent(mContext, GroupSummaryActivity.class);
                    mGroupSummary.putExtra(AppConstants.ARG_GROUP_DETAIL, mGroupModel);
                    Bundle bundle = new Bundle();
                    bundle.putParcelableArrayList(AppConstants.ARG_ALL_GROUP_LIST, (ArrayList<? extends Parcelable>) mGroupList);
                    bundle.putParcelable(AppConstants.ARG_GROUP_DETAIL_WITH_DIVISION, (Parcelable) mAgeGroupData);
                    mGroupSummary.putExtras(bundle);
                    mContext.startActivity(mGroupSummary);
                } else {
                    Intent mTeamListIntent = new Intent(mContext, TeamListingActivity.class);
                    mTeamListIntent.putExtra(AppConstants.ARG_GROUP_ID, mGroupModel.getId());
                    mContext.startActivity(mTeamListIntent);
                }
            }
        });
    }


    @Override
    public int getItemCount() {
        return mGroupList.size();
    }


    protected class ViewHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.individual_list_item)
        protected TextView individual_list_item;
        @BindView(R.id.ll_list_parent)
        protected LinearLayout ll_list_parent;

        public ViewHolder(View rowView) {
            super(rowView);
            ButterKnife.bind(this, rowView);
        }

    }
}

