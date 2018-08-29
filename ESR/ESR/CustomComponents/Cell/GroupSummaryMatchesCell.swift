//
//  GroupSummaryMatchesCell.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit

class GroupSummaryMatchesCell: UITableViewCell {

    var record: NSDictionary!
    
    @IBOutlet var lblHomeTeam: UILabel!
    @IBOutlet var lblAwayTeam: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
    
    func reloadCell() {
        if let text = record.value(forKey: "match_number") as? String {
            
        }
        
        if let text = record.value(forKey: "HomeTeam") as? String {
            
        }
        
        if let text = record.value(forKey: "AwayTeam") as? String {
            
        }
        
        if let text = record.value(forKey: "homeScore") as? String {
            
        }
        
        if let text = record.value(forKey: "AwayScore") as? String {
            
        }
    }
}
