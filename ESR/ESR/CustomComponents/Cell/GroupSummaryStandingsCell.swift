//
//  GroupSummaryStandingsCell.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit
import SDWebImage

class GroupSummaryStandingsCell: UITableViewCell {

    var record: NSDictionary!
    
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
        var goalsFor = 0
        var goalsAgainst = 0
        
        if let name = record.value(forKey: "name") as? String {
            lblGroupname.text = name
        }
        
        if let flagImageURL = record.value(forKey: "teamFlag") as? String {
            imgViewFlag.sd_setImage(with: URL(string: flagImageURL), placeholderImage:nil)
        }
        
        if let points = record.value(forKey: "points") as? Int {
            lblPoints.text = "\(points)"
        }
        
        if let played = record.value(forKey: "played") as? Int {
            lblGames.text = "\(played)"
        }
        
        if let goalsForValue = record.value(forKey: "goal_for") as? Int {
            goalsFor = goalsForValue
        }
        
        if let goalsAgainstValue = record.value(forKey: "goal_against") as? Int {
            goalsAgainst = goalsAgainstValue
        }
        
        let goalDiff = goalsFor - goalsAgainst
        lblGoalDifference.text = goalDiff > 0 ? "+\(goalDiff)" : "\(goalDiff)"
    }
}
