//
//  GroupSummaryMatchesHeader.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit

protocol GroupSummaryMatchesHeaderDelegate {
    func groupSummaryMatchesHeaderOnGroupSelect()
}

class GroupSummaryMatchesHeader: UIView {

    @IBOutlet var groupSelectionView: UIView!
    @IBOutlet var lblGroupName: UILabel!
    
    var delegate: GroupSummaryMatchesHeaderDelegate?
    
    override func awakeFromNib() {
        let gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onGroupBtnPressed))
        self.groupSelectionView.addGestureRecognizer(gesture)
    }
    
    @objc func onGroupBtnPressed() {
        delegate?.groupSummaryMatchesHeaderOnGroupSelect()
    }
    
    func setGroupName(_ name: String) {
        lblGroupName.text = name
    }
}
