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
    
    @IBOutlet var btnDropdown: UIButton!
    var record = NSDictionary()
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
        lblTitle.textColor = UIColor.txtPlaceholderTxt
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnDropdown.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnDropdown.setImageColor(color: .white, image: UIImage(named: "down_arrow_black")!, state: .normal)
        }
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
