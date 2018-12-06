//
//  TournamentClubCell.swift
//  ESR
//
//  Created by Pratik Patel on 13/09/18.
//

import UIKit

class TournamentClubCell: UITableViewCell {

    @IBOutlet var imgView: UIImageView!
    @IBOutlet var lblTitle: UILabel!
    var record: NSDictionary!
    
    var isClubsTeam = false
    var isClubsCategoryTeam = false
    var isClubsGroupTeam = false
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func setClubCategoryTeam() {
        var name = NULL_STRING
        
        if let text = record.value(forKey: "name") as? String {
            name = text + " "
        }
        
        if let text = record.value(forKey: "CategoryAge") as? String {
            name += "(\(text))"
        }
        
        if let text = record.value(forKey: "countryLogo") as? String {
            imgView.sd_setImage(with: URL(string: text), placeholderImage:nil)
        }
        
        lblTitle.text = name
    }
    
   func reloadCell() {
        if isClubsTeam || isClubsCategoryTeam || isClubsGroupTeam {
            setClubCategoryTeam()
        } else {
            if let text = record.value(forKey: "clubName") as? String {
                lblTitle.text = text
            }
            
            if let text = record.value(forKey: "CountryLogo") as? String {
                imgView.sd_setImage(with: URL(string: text), placeholderImage:nil)
            }
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
