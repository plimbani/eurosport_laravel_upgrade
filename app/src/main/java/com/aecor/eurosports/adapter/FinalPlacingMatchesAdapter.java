package com.aecor.eurosports.adapter;

import android.app.Activity;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.FinalPlacingModel;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;

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
    private LayoutInflater inflater;
    private Context mContext;
    private List<FinalPlacingModel> mFinalPlacingList;
    private AppPreference mPreference;

    public FinalPlacingMatchesAdapter(Activity context, List<FinalPlacingModel> list) {
        mContext = context;
        inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mFinalPlacingList = list;
        mPreference = AppPreference.getInstance(mContext);
    }

    @Override
    public FinalPlacingMatchesAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_final_placing_matches, parent, false);

        return new FinalPlacingMatchesAdapter.ViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(FinalPlacingMatchesAdapter.ViewHolder holder, int position) {
        final FinalPlacingModel mGroupModel = mFinalPlacingList.get(position);
        String mPlacingName = "";
        if (!Utility.isNullOrEmpty(mGroupModel.getPos())) {
            mPlacingName = mContext.getString(R.string.placing_text_holder) + " " + mGroupModel.getPos();
        }
        holder.tv_team_pos.setText(mPlacingName);
        if (!Utility.isNullOrEmpty(mGroupModel.getTeam_name())) {
            holder.tv_tem_name.setText(mGroupModel.getTeam_name());
        }


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

        public ViewHolder(View rowView) {
            super(rowView);
            ButterKnife.bind(this, rowView);
        }

    }


}