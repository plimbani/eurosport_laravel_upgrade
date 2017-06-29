package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;

import android.widget.Filter;
import android.widget.Filterable;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.AgeCategoriesModel;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;

import org.w3c.dom.Text;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by karan on 6/28/2017.
 */

public class AgeCategoriesAdapter extends BaseAdapter implements Filterable {

    private final String TAG = AgeCategoriesAdapter.class.getSimpleName();
    private LayoutInflater inflater;
    private Context mContext;
    private List<AgeCategoriesModel> mAgeCategoriesList;
    private List<AgeCategoriesModel> mOriginalList;
    private AppPreference mPreference;
    private AgeFilter ageFilter;

    public AgeCategoriesAdapter(Activity context, List<AgeCategoriesModel> list) {
        mContext = context;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mAgeCategoriesList = list;
        this.mOriginalList = list;
        mPreference = AppPreference.getInstance(mContext);
    }

    @Override
    public int getCount() {
        return mAgeCategoriesList.size();
    }

    @Override
    public AgeCategoriesModel getItem(int position) {
        return mAgeCategoriesList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return rowView(convertView, position);
    }

    private View rowView(View convertView, final int position) {
        final ViewHolder holder;
        View rowview = convertView;
        if (rowview == null) {
            rowview = inflater.inflate(R.layout.layout_listview_textview, null);
            holder = new ViewHolder(rowview);
            rowview.setTag(holder);
        } else {
            holder = (ViewHolder) rowview.getTag();
        }
        AgeCategoriesModel rowItem = getItem(position);
        if (!Utility.isNullOrEmpty(rowItem.getCategory_age())) {
            holder.individual_list_item.setText(rowItem.getCategory_age());
        }

        return rowview;
    }

    @Override
    public Filter getFilter() {
        if (ageFilter == null)
            ageFilter = new AgeFilter();

        return ageFilter;
    }

    protected class ViewHolder {
        @BindView(R.id.individual_list_item)
        protected TextView individual_list_item;

        public ViewHolder(View rowView) {
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
                List<AgeCategoriesModel> nAgeList = new ArrayList<AgeCategoriesModel>();
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
            // Now we have to inform the adapter about the new list filtered
//            if (results.count == 0) {
//                notifyDataSetInvalidated();
//            } else {
                mAgeCategoriesList = (List<AgeCategoriesModel>) results.values;
                notifyDataSetChanged();
//            }
        }
    }
}
