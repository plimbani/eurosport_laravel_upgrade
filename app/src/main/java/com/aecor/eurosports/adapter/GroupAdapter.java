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
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.GroupSummaryActivity;
import com.aecor.eurosports.activity.TeamListingActivity;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 29-06-2017.
 */

public class GroupAdapter extends RecyclerView.Adapter<GroupAdapter.ViewHolder> implements Filterable {

    private final String TAG = GroupAdapter.class.getSimpleName();
    private LayoutInflater inflater;
    private Context mContext;
    private List<ClubGroupModel> mGroupList;
    private List<ClubGroupModel> mOriginalList;
    private AppPreference mPreference;
    private ClubGroupFilter mClubGroupFilter;

    public GroupAdapter(Activity context, List<ClubGroupModel> list) {
        mContext = context;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mGroupList = list;
        this.mOriginalList = list;
        mPreference = AppPreference.getInstance(mContext);
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.layout_listview_textview, parent, false);

        return new ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        final ClubGroupModel mGroupModel = mGroupList.get(position);
        if (!Utility.isNullOrEmpty(mGroupModel.getName())) {
            holder.individual_list_item.setText(mGroupModel.getName());
        }
        holder.ll_list_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent mGroupSummary = new Intent(mContext, GroupSummaryActivity.class);
                mGroupSummary.putExtra(AppConstants.ARG_GROUP_DETAIL, mGroupModel);
                mContext.startActivity(mGroupSummary);
            }
        });
    }


    @Override
    public int getItemCount() {
        return mGroupList.size();
    }

    @Override
    public Filter getFilter() {
        if (mClubGroupFilter == null)
            mClubGroupFilter = new ClubGroupFilter();

        return mClubGroupFilter;
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

    private class ClubGroupFilter extends Filter {
        @Override
        protected FilterResults performFiltering(CharSequence constraint) {
            FilterResults results = new FilterResults();
            // We implement here the filter logic
            if (constraint == null || constraint.length() == 0) {
                // No filter implemented we return all the list
                results.values = mOriginalList;
                results.count = mGroupList.size();
            } else {
                // We perform filtering operation
                List<ClubGroupModel> mGroupList = new ArrayList<ClubGroupModel>();
                for (ClubGroupModel p : mOriginalList) {
                    if (p.getGroup_name().toUpperCase().contains(constraint.toString().toUpperCase()))
                        mGroupList.add(p);
                }
                results.values = mGroupList;
                results.count = mGroupList.size();
            }
            return results;
        }

        @Override
        protected void publishResults(CharSequence constraint, FilterResults results) {
            // Now we have to inform the adapter about the new list filtered
//            if (results.count == 0) {
//                notifyDataSetInvalidated();
//            } else {
            mGroupList = (List<ClubGroupModel>) results.values;
            notifyDataSetChanged();
//            }
        }
    }
}
