//
//  ButtonCell.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit

class ButtonCell: UITableViewCell {

    @IBOutlet var btn: UIButton!
    var record = NSDictionary()
    
    override func awakeFromNib() {
        super.awakeFromNib()
        btn.titleLabel?.font = UIFont.init(name: Font.HELVETICA_BOLD, size: Font.Size.commonBtnSize)
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
    func reloadCell() {
        if let title = record.value(forKey: "title") as? String {
            btn.setTitle(title, for: .normal)
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
