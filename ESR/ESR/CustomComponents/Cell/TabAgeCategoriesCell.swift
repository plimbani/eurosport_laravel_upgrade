//
//  TabAgeCategoriesCell.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

protocol TabAgeCategoriesCellDelegate {
    func tabAgeCategoriesCellBtnInfoPressed(_ indexPath: IndexPath)
    func tabAgeCategoriesCellBtnViewSchedulePressed(_ indexPath: IndexPath)
}

class TabAgeCategoriesCell: UITableViewCell {

    @IBOutlet var rightArrowView: UIView!
    @IBOutlet var viewScheduleView: UIView!
    @IBOutlet var infoView: UIView!
    
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var btnInfo: UIButton!
    @IBOutlet var btnViewSchedule: UIButton!
    
    var delegate: TabAgeCategoriesCellDelegate?
    var record: NSDictionary!
    var indexPath: IndexPath!
    
    let btnViewScheduleAttributes : [NSAttributedStringKey: Any] = [
        NSAttributedStringKey.font : UIFont.init(name: Font.HELVETICA_REGULAR, size: 15.0)!,
        NSAttributedStringKey.foregroundColor : UIColor.viewScheduleBlue,
        NSAttributedStringKey.underlineStyle : NSUnderlineStyle.styleSingle.rawValue]
    
    override func awakeFromNib() {
        super.awakeFromNib()
        viewScheduleView.isHidden = true
        infoView.isHidden = true
        
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblTitle.textColor = UIColor.txtDefaultTxt
        btnViewSchedule.setAttributedTitle(NSMutableAttributedString(string: "View schedule",
                                                                         attributes: btnViewScheduleAttributes), for: .normal)
        
        viewScheduleView.addGestureRecognizer(UITapGestureRecognizer(target: self, action: #selector(viewScheduleViewTap)))
        infoView.addGestureRecognizer(UITapGestureRecognizer(target: self, action: #selector(infoViewTap)))
    }
    
    func showInfoButton() {
        // widthConstraintBtnInfo.constant = 25
        // btnInfo.imageEdgeInsets = UIEdgeInsets(top: 5, left: 2, bottom: 5, right: 3)
        
        infoView.isHidden = false
    }
    
    func showViewScheduleButton(hide: Bool = false) {
        // widthConstraintBtnViewSchedule.constant = hide ? 0 : 105
        
        btnViewSchedule.isHidden = hide
    }
    
    @objc func infoViewTap() {
        delegate?.tabAgeCategoriesCellBtnInfoPressed(indexPath)
    }
    
    @objc func viewScheduleViewTap() {
        delegate?.tabAgeCategoriesCellBtnViewSchedulePressed(indexPath)
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
        
        if let _ = record.value(forKey: "graphic_image") as? String {
           showViewScheduleButton()
        } else {
            showViewScheduleButton(hide: true)
        }
        
        lblTitle.text = ageCategory
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
