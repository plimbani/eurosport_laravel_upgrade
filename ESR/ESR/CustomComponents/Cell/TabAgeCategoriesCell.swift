//
//  TabAgeCategoriesCell.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

protocol TabAgeCategoriesCellDelegate {
    func tabAgeCategoriesCellBtnInfoPressed(_ indexPath: IndexPath)
}

class TabAgeCategoriesCell: UITableViewCell {

    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var btnInfo: UIButton!
    @IBOutlet var widthConstraintBtnInfo: NSLayoutConstraint!
    
    var delegate: TabAgeCategoriesCellDelegate?
    
    var record: NSDictionary!
    var indexPath: IndexPath!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        widthConstraintBtnInfo.constant = 0
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblTitle.textColor = UIColor.txtDefaultTxt
    }
    
    func showInfoButton() {
        widthConstraintBtnInfo.constant = 20
    }
    
    @IBAction func btnInfoPressed(_ sender: Any) {
        delegate?.tabAgeCategoriesCellBtnInfoPressed(indexPath)
    }
    
    func reloadCell() {
        var ageCategory = NULL_STRING
        
        if let text = record.value(forKey: "group_name") as? String {
            ageCategory = text
        }
        
        if let text = record.value(forKey: "category_age") as? String {
            ageCategory = ageCategory + " (\(text))"
        }
        
        if let text = record.value(forKey: "comments") as? String {
            if !text.isEmpty {
                showInfoButton()
            }
        }
        
        lblTitle.text = ageCategory
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
