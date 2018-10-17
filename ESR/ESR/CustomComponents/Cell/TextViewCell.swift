//
//  TextViewCell.swift
//  ESR
//
//  Created by Pratik Patel on 15/10/18.
//

import UIKit

class TextViewCell: UITableViewCell {

    @IBOutlet var textView: UITextView!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
