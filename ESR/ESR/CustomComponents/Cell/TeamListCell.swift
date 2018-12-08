//
//  TeamListCell.swift
//  ESR
//
//  Created by Pratik Patel on 08/12/18.
//

import UIKit

class TeamListCell: UITableViewCell {

    @IBOutlet var lblTitle: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
