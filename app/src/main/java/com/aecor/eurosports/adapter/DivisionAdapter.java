package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.AgeGroupModel;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.model.DivisionGroupModel;
import com.aecor.eurosports.ui.SimpleDividerItemDecoration;
import com.aecor.eurosports.util.Utility;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class DivisionAdapter extends RecyclerView.Adapter<DivisionAdapter.ViewHolder> implements Filterable {

    private final String TAG = GroupAdapter.class.getSimpleName();
    private Context mContext;
    private List<DivisionGroupModel> division_groups;
    private List<DivisionGroupModel> originalList;
    private AgeGroupModel mAgeGroupData;
    private DivisionFilter mDivisionFilter;

    public DivisionAdapter(Activity context, List<DivisionGroupModel> division_groups, AgeGroupModel mAgeGroupData) {
        mContext = context;
        this.division_groups = division_groups;
        this.originalList = division_groups;
        this.mAgeGroupData = mAgeGroupData;
    }

    @Override
    public DivisionAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_division_list, parent, false);

        return new DivisionAdapter.ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(DivisionAdapter.ViewHolder holder, int position) {
        if (!Utility.isNullOrEmpty(division_groups.get(position).getTitle())) {
            holder.individual_list_item.setText(division_groups.get(position).getTitle());
        }

        if (division_groups.get(position).getData() != null
                && division_groups.get(position).getData().size() > 0
                && division_groups.get(position).isRowExpanded()) {
            holder.rv_divisions_list.setLayoutManager(new LinearLayoutManager(mContext));
            holder.rv_divisions_list.setItemAnimator(new DefaultItemAnimator());
            holder.rv_divisions_list.addItemDecoration(new SimpleDividerItemDecoration(mContext));
            DivisionGroupAdapter mDivisionGroupAdapter = new DivisionGroupAdapter((Activity) mContext, division_groups.get(position).getData(), mAgeGroupData);
            holder.rv_divisions_list.setAdapter(mDivisionGroupAdapter);
            holder.iv_arrow_right.setRotation(90);
            division_groups.get(position).setRowExpanded(true);
            holder.sep_view_1.setVisibility(View.VISIBLE);
        } else {
            holder.iv_arrow_right.setRotation(-90);
            holder.sep_view_1.setVisibility(View.GONE);
        }


    }


    @Override
    public int getItemCount() {
        return division_groups.size();
    }


    protected class ViewHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.individual_list_item)
        protected TextView individual_list_item;
        @BindView(R.id.iv_arrow_right)
        protected ImageView iv_arrow_right;
        @BindView(R.id.rv_divisions_list)
        protected RecyclerView rv_divisions_list;
        @BindView(R.id.sep_view_1)
        protected View sep_view_1;

        public ViewHolder(View rowView) {
            super(rowView);
            ButterKnife.bind(this, rowView);
            rowView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (division_groups.get(getAdapterPosition()).isRowExpanded()) {
                        rv_divisions_list.setVisibility(View.GONE);
                        division_groups.get(getAdapterPosition()).setRowExpanded(false);
                    } else {
                        rv_divisions_list.setVisibility(View.VISIBLE);
                        division_groups.get(getAdapterPosition()).setRowExpanded(true);
                    }
                    notifyDataSetChanged();
                }
            });
        }

    }


    @Override
    public Filter getFilter() {
        if (mDivisionFilter == null)
            mDivisionFilter = new DivisionFilter();

        return mDivisionFilter;
    }

    private class DivisionFilter extends Filter {
        @Override
        protected FilterResults performFiltering(CharSequence constraint) {
            FilterResults results = new FilterResults();
            // We implement here the filter logic
            if (constraint == null || constraint.length() == 0) {
                // No filter implemented we return all the list
                results.values = originalList;
                results.count = division_groups.size();
            } else {
                // We perform filtering operation
                List<DivisionGroupModel> mGroupList = new ArrayList<>();
                for (int i = 0; i < originalList.size(); i++) {
                    for (int j = 0; j < originalList.get(i).getData().size(); j++) {
                        if (originalList.get(i).getTitle().toUpperCase().contains(constraint.toString().toUpperCase())) {
                            mGroupList.add(originalList.get(i));
                        }
                        if (originalList.get(i).getData().get(j).getName().toUpperCase().contains(constraint.toString().toUpperCase())) {
                            mGroupList.add(originalList.get(i));

                        }
                    }
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
            division_groups = (List<DivisionGroupModel>) results.values;
            notifyDataSetChanged();
//            }
        }
    }
}
