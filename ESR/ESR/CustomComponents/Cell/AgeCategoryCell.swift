//
//  AgeCategoryCell.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

protocol AgeCategoryCellDelegate {
    func ageCategoriesCellBtnViewSchedulePressed(_ indexPath: IndexPath)
}

class AgeCategoryCell: UITableViewCell {

    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var btnViewSchedule: UIButton!
    
    var delegate: AgeCategoryCellDelegate?
    
    var record: NSDictionary!
    var indexPath: IndexPath!
    var isAgeGroup = false
    
    @IBOutlet var widthConstraintBtnViewSchedule: NSLayoutConstraint!
    
    let btnViewScheduleAttributes : [NSAttributedStringKey: Any] = [
        NSAttributedStringKey.font : UIFont.init(name: Font.HELVETICA_REGULAR, size: 15.0),
        NSAttributedStringKey.foregroundColor : UIColor.viewScheduleBlue,
        NSAttributedStringKey.underlineStyle : NSUnderlineStyle.styleSingle.rawValue]
    
    override func awakeFromNib() {
        super.awakeFromNib()
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblTitle.textColor = UIColor.txtDefaultTxt
        widthConstraintBtnViewSchedule.constant = 0
        btnViewSchedule.setAttributedTitle(NSMutableAttributedString(string: "View schedule",
                                                                     attributes: btnViewScheduleAttributes), for: .normal)
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    @IBAction func btnViewSchedulePressed(_ sender: UIButton) {
        delegate?.ageCategoriesCellBtnViewSchedulePressed(indexPath)
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
    
    func showViewScheduleButton(hide: Bool = false) {
        widthConstraintBtnViewSchedule.constant = hide ? 0 : 105
    }
    
    func reloadCell() {
        var ageCategory = NULL_STRING
        
        if isAgeGroup {
            if let text = record.value(forKey: "name") as? String {
                ageCategory = text
            }
            
            //if let competitionType = record.value(forKey: "actual_competition_type") as? String {
                //if competitionType == "Elimination" {
                    if let displayName = record.value(forKey: "display_name") as? String {
                        ageCategory = displayName
                    }
                //}
           // }
        } else {
            if let text = record.value(forKey: "group_name") as? String {
                ageCategory = text
            }
            
            if let text = record.value(forKey: "category_age") as? String {
                ageCategory = ageCategory + " (\(text))"
            }
        }
        
        if let _ = record.value(forKey: "graphic_image") as? String {
            showViewScheduleButton()
        } else {
            showViewScheduleButton(hide: true)
        }
        
        lblTitle.text = ageCategory
    }
}
