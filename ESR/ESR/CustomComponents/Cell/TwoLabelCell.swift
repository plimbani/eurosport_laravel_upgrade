//
//  TwoLabelCell.swift
//  ESR
//
//  Created by Pratik Patel on 04/09/18.
//

import UIKit

class TwoLabelCell: UITableViewCell {

    @IBOutlet var lblStaticTitle: UILabel!
    @IBOutlet var lblTitle: UILabel!
    var record = NSDictionary()
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
    func reloadCell() {
        if let title = record.value(forKey: "title") as? String {
            lblStaticTitle.text = title
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
