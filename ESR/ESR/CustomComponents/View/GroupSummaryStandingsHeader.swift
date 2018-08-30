//
//  GroupSummaryStandingsHeader.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

protocol GroupSummaryStandingsHeaderDelegate {
    func groupSummaryStandingsHeaderOnGroupSelect()
}

class GroupSummaryStandingsHeader: UIView {
    
    @IBOutlet var groupSelectionView: UIView!
    @IBOutlet var lblGroupName: UILabel!
    
    var delegate: GroupSummaryStandingsHeaderDelegate?
    
    override func awakeFromNib() {
        let gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onGroupBtnPressed))
        self.groupSelectionView.addGestureRecognizer(gesture)
    }

    @objc func onGroupBtnPressed() {
        delegate?.groupSummaryStandingsHeaderOnGroupSelect()
    }
    
    func setGroupName(_ name: String) {
        lblGroupName.text = name
    }
}
