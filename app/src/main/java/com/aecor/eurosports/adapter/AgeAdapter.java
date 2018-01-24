package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
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
import com.aecor.eurosports.activity.AgeCategoriesActivity;
import com.aecor.eurosports.activity.AgeGroupActivity;
import com.aecor.eurosports.activity.TeamListingActivity;
import com.aecor.eurosports.model.AgeCategoriesModel;
import com.aecor.eurosports.ui.ViewDialog;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 29-06-2017.
 */

public class AgeAdapter extends RecyclerView.Adapter<AgeAdapter.ViewHolder> implements Filterable {
    private Context mContext;
    private List<AgeCategoriesModel> mAgeCategoriesList;
    private List<AgeCategoriesModel> mOriginalList;
    private AgeFilter ageFilter;

    public AgeAdapter(Activity context, List<AgeCategoriesModel> list) {
        mContext = context;
        this.mAgeCategoriesList = list;
        this.mOriginalList = list;
    }


    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.layout_listview_textview, parent, false);
        return new ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        final AgeCategoriesModel ageModel = mAgeCategoriesList.get(position);
        String listTextTitle = "";
        if (!Utility.isNullOrEmpty(ageModel.getGroup_name())) {
            listTextTitle = ageModel.getGroup_name();
        }
        if (!Utility.isNullOrEmpty(ageModel.getCategory_age())) {
            listTextTitle = listTextTitle + " (" + ageModel.getCategory_age() + ")";
        }
        holder.individual_list_item.setText(listTextTitle);

        holder.ll_list_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mContext instanceof AgeCategoriesActivity) {
                    Intent mAgeGroupIntent = new Intent(mContext, AgeGroupActivity.class);
                    mAgeGroupIntent.putExtra(AppConstants.ARG_AGE_CATEGORY_ID, ageModel.getId() + "");
                    mContext.startActivity(mAgeGroupIntent);
                } else {
                    Intent mTeamListIntent = new Intent(mContext, TeamListingActivity.class);
                    mTeamListIntent.putExtra(AppConstants.ARG_AGE_GROUP_ID, ageModel.getId() + "");
                    mContext.startActivity(mTeamListIntent);
                }
            }
        });

        if (!Utility.isNullOrEmpty(ageModel.getComments())) {
            holder.iv_info.setVisibility(View.VISIBLE);
        } else {
            holder.iv_info.setVisibility(View.GONE);
        }

        holder.iv_info.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.comments), ageModel.getComments(), mContext.getString(R.string.close), new ViewDialog.CustomDialogInterface() {
                    @Override
                    public void onPositiveButtonClicked() {

                    }
                });
            }
        });
    }

    @Override
    public int getItemCount() {
        return mAgeCategoriesList.size();
    }

    @Override
    public Filter getFilter() {
        if (ageFilter == null)
            ageFilter = new AgeFilter();

        return ageFilter;
    }

    public class ViewHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.individual_list_item)
        protected TextView individual_list_item;
        @BindView(R.id.ll_list_parent)
        protected LinearLayout ll_list_parent;
        @BindView(R.id.iv_info)
        protected ImageView iv_info;

        public ViewHolder(View rowView) {
            super(rowView);
            ButterKnife.bind(this, rowView);

        }
    }

    private class AgeFilter extends Filter {
        @Override
        protected FilterResults performFiltering(CharSequence constraint) {
            FilterResults results = new FilterResults();
            // We implement here the filter logic
            if (constraint == null || constraint.length() == 0) {
                // No filter implemented we return all the list
                results.values = mOriginalList;
                results.count = mAgeCategoriesList.size();
            } else {
                // We perform filtering operation
                List<AgeCategoriesModel> nAgeList = new ArrayList<>();
                for (AgeCategoriesModel p : mOriginalList) {
                    if (p.getCategory_age().toUpperCase().contains(constraint.toString().toUpperCase()) || p.getGroup_name().toUpperCase().contains(constraint.toString().toUpperCase()))
                        nAgeList.add(p);
                }
                results.values = nAgeList;
                results.count = nAgeList.size();
            }
            return results;
        }

        @Override
        protected void publishResults(CharSequence constraint, FilterResults results) {
            mAgeCategoriesList = (List<AgeCategoriesModel>) results.values;
            notifyDataSetChanged();

        }
    }
}
