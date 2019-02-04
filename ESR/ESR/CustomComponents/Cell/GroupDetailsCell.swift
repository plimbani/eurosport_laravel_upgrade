//
//  GroupDetailsCell.swift
//  ESR
//
//  Created by Pratik Patel on 31/08/18.
//

import UIKit

class GroupDetailsCell: UITableViewCell {
    
    var record: GroupStanding!
    
    @IBOutlet var imgView: UIImageView!
    @IBOutlet var lblName: UILabel!
    @IBOutlet var lblG: UILabel!
    @IBOutlet var lblW: UILabel!
    @IBOutlet var lblD: UILabel!
    @IBOutlet var lblL: UILabel!
    @IBOutlet var lblF: UILabel!
    @IBOutlet var lblA: UILabel!
    @IBOutlet var lblGoalDifference: UILabel!
    @IBOutlet var lblP: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        lblName.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblG.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblW.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblD.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblL.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblF.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblA.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblGoalDifference.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblP.font = UIFont(name: Font.HELVETICA_BOLD, size: Font.Size.commonLblSize)
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func reloadCell() {
        
        if record.name != NULL_STRING {
            lblName.text = record.name
        }
        
        if record.teamFlag != NULL_STRING {
            imgView.sd_setImage(with: URL(string: record.teamFlag), placeholderImage:nil)
        }
        
        if record.played != NULL_ID {
            lblG.text = "\(record.played)"
        }
        
        if record.points != NULL_ID {
            lblP.text = "\(record.points)"
        }
        
        if record.won != NULL_ID {
            lblW.text = "\(record.won)"
        }
        
        if record.draws != NULL_ID {
            lblD.text = "\(record.draws)"
        }
        
        if record.lost != NULL_ID {
            lblL.text = "\(record.lost)"
        }
        
        if record.goalFor != NULL_ID {
            lblF.text = "\(record.goalFor)"
        }
        
        if record.goalAgainst != NULL_ID {
            lblA.text = "\(record.goalAgainst)"
        }
        
        if record.goalFor != NULL_ID && record.goalAgainst != NULL_ID {
            let goalDiff = record.goalFor - record.goalAgainst
            lblGoalDifference.text = goalDiff > 0 ? "+\(goalDiff)" : "\(goalDiff)"
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
