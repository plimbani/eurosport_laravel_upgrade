//
//  GroupSummaryStandingsCell.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit
import SDWebImage

protocol GroupSummaryStandingsCellDelegate {
    func groupSummaryStandingsCellBtnTeamNamePressed(indexPath: IndexPath)
}

class GroupSummaryStandingsCell: UITableViewCell {

    var record: GroupStanding!
    
    @IBOutlet var imgViewFlag: UIImageView!
    @IBOutlet var btnGroupname: UIButton!
    @IBOutlet var lblPoints: UILabel!
    @IBOutlet var lblGames: UILabel!
    @IBOutlet var lblGoalDifference: UILabel!
    
    var delegate: GroupSummaryStandingsCellDelegate?
    
    var indexPath: IndexPath!
    
    var isFromAgecategory = false
    
    let btnAttributes : [NSAttributedStringKey: Any] = [
        NSAttributedStringKey.font : UIFont.init(name: Font.HELVETICA_REGULAR, size: 17.0)!,
        NSAttributedStringKey.foregroundColor : UIColor.black,
        NSAttributedStringKey.underlineStyle : NSUnderlineStyle.styleSingle.rawValue]
    
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
           
            if isFromAgecategory {
                btnGroupname.setTitle(record.name, for: .normal)
                btnGroupname.isEnabled = false
            } else {
                btnGroupname.setAttributedTitle(NSMutableAttributedString(string: record.name,
                                                                          attributes: btnAttributes), for: .normal)
            }
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
    
    @IBAction func btnGroupNamePressed(_ sender: UIButton) {
        delegate?.groupSummaryStandingsCellBtnTeamNamePressed(indexPath: indexPath)
    }
}
