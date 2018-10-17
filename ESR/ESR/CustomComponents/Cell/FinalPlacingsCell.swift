//
//  FinalPlacingsCell.swift
//  ESR
//
//  Created by Pratik Patel on 15/10/18.
//

import UIKit

class FinalPlacingsCell: UITableViewCell {

    @IBOutlet var lblPlacings: UILabel!
    @IBOutlet var lblTeamName: UILabel!
    @IBOutlet var imgView: UIImageView!
    
    var record: NSDictionary!
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func reloadCell() {
        if let pos = record.value(forKey: "pos") as? Int {
            lblPlacings.text = String.init(format: String.localize(key: "string_placings"), arguments: ["\(pos)"])
        }
        
        if let name = record.value(forKey: "team_name") as? String {
            lblTeamName.text = name
        }
        
        if let logo = record.value(forKey: "team_logo") as? String {
            imgView.sd_setImage(with: URL(string: logo), completed: nil)
        } else {
            imgView.image = UIImage()
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
