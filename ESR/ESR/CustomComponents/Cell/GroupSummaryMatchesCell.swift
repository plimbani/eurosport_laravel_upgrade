//
//  GroupSummaryMatchesCell.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit

class GroupSummaryMatchesCell: UITableViewCell {

    var record: TeamFixture!
    
    @IBOutlet var lblHomeTeam: UILabel!
    @IBOutlet var lblAwayTeam: UILabel!
    @IBOutlet var lblVenue: UILabel!
    @IBOutlet var lblDate: UILabel!
    @IBOutlet var lblMatchId: UILabel!
    @IBOutlet var lblGroupName: UILabel!
    @IBOutlet var lblRound: UILabel!
    
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
        
        // Sets date
        if record.matchDatetime != NULL_STRING {
            let dateFormatter = DateFormatter()
            dateFormatter.dateFormat = kDateFormat.MMM
            let nameOfMonth = dateFormatter.string(from: record.matchDatetimeObj)
            dateFormatter.dateFormat = kDateFormat.dd
            let dateOfMonth = dateFormatter.string(from: record.matchDatetimeObj)
            
            lblDate.text = nameOfMonth + " " + dateOfMonth
        } else {
            lblDate.text = NULL_STRING
        }
        
        // Venue name
        if record.venueName != NULL_STRING {
            lblVenue.text = record.venueName
            
            if record.pitchNumber != NULL_STRING {
                lblVenue.text = lblVenue.text! + " - " + record.pitchNumber
            }
        } else {
            lblVenue.text = NULL_STRING
        }
        
        if record.displayMatchNumber != NULL_STRING {
            var displayNumberStr = record.displayMatchNumber.replacingOccurrences(of: "@HOME", with: record.displayHomeTeamPlaceholderName)
            displayNumberStr = displayNumberStr.replacingOccurrences(of: "@AWAY", with: record.displayAwayTeamPlaceholderName)
            
            lblMatchId.text = displayNumberStr
        }
        
        if record.groupName != NULL_STRING {
            lblGroupName.text = record.groupName
        } else {
            lblGroupName.text = NULL_STRING
        }
        
        if record.round != NULL_STRING {
            lblRound.text = record.round
        } else {
            lblRound.text = NULL_STRING
        }
        
        
    }
}
