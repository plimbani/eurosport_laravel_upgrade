//
//  AgeCategoryDivisionView.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/19.
//

import UIKit

class AgeCategoryDivisionView: UIView {

    @IBOutlet var lblTitle: UILabel!
    
    override func awakeFromNib() {
        lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        lblTitle.textColor = UIColor.txtDefaultTxt
    }
}
