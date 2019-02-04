//
//  GroupSummaryStandingsCell.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit
import SDWebImage

class GroupSummaryStandingsCell: UITableViewCell {

    var record: GroupStanding!
    
    @IBOutlet var imgViewFlag: UIImageView!
    @IBOutlet var lblGroupname: UILabel!
    @IBOutlet var lblPoints: UILabel!
    @IBOutlet var lblGames: UILabel!
    @IBOutlet var lblGoalDifference: UILabel!
    
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
        lblPoints.text = NULL_STRING
        lblGames.text = NULL_STRING
        lblGoalDifference.text = NULL_STRING
        
        if record.name != NULL_STRING {
           lblGroupname.text = record.name
        }
        
        if record.teamFlag != NULL_STRING {
            imgViewFlag.sd_setImage(with: URL(string: record.teamFlag), placeholderImage:nil)
        }
        
        if record.points != NULL_ID {
            lblPoints.text = "\(record.points)"
        }
        
        if record.played != NULL_ID {
            lblGames.text = "\(record.played)"
        }

        if record.goalFor != NULL_ID && record.goalAgainst != NULL_ID {
            let goalDiff = record.goalFor - record.goalAgainst
            lblGoalDifference.text = goalDiff > 0 ? "+\(goalDiff)" : "\(goalDiff)"
        }
    }
}
