//
//  LabelCell.swift
//  ESR
//
//  Created by Pratik Patel on 12/10/18.
//

import UIKit

class LabelCell: UITableViewCell {

    @IBOutlet var lblTitle: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
        // Configure the view for the selected state
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
