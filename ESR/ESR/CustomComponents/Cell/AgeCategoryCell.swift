//
//  AgeCategoryCell.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

class AgeCategoryCell: UITableViewCell {

    @IBOutlet var lblTitle: UILabel!
    var record: NSDictionary!
    var isAgeGroup = false
    
    override func awakeFromNib() {
        super.awakeFromNib()
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblTitle.textColor = UIColor.txtDefaultTxt
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
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
        
        lblTitle.text = ageCategory
    }
}
