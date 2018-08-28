//
//  LabelSelectionCell.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit

class LabelSelectionCell: UITableViewCell {

    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var containerView: UIView!
    
    var record = NSDictionary()
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
        lblTitle.textColor = UIColor.txtPlaceholderTxt
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
    }

    func reloadCell() {
        if let title = record.value(forKey: "title") as? String {
            lblTitle.text = title
        }
    }
    
    func setTitle(_ title: String) {
        lblTitle.textColor = .black
        lblTitle.text = title
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
