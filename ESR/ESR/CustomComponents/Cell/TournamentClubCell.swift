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
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func reloadCell() {
        
        if let text = record.value(forKey: "clubName") as? String {
            lblTitle.text = text
        }
    
        if let text = record.value(forKey: "CountryLogo") as? String {
            imgView.sd_setImage(with: URL(string: text), placeholderImage:nil)
        }
        
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
