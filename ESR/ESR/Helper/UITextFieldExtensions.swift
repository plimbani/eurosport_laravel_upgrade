//
//  TextFieldExtensions.swift
//  Marketti
//
//  Created by My on 08/03/18.
//  Copyright © 2018 My. All rights reserved.
//

import UIKit

extension UITextField {
    
    func setLeftPaddingPoints(_ amount:CGFloat){
        let paddingView = UIView(frame: CGRect(x: 0, y: 0, width: amount, height: self.frame.size.height))
        self.leftView = paddingView
        self.leftViewMode = .always
    }
  
    func setRightPaddingPoints(_ amount:CGFloat) {
        let paddingView = UIView(frame: CGRect(x: 0, y: 0, width: amount, height: self.frame.size.height))
        self.rightView = paddingView
        self.rightViewMode = .always
    }
    
    func setPlaceHolderFontColor(_ txtPlaceholder: String, _ color: UIColor = UIColor.txtPlaceholderTxt, _ font: String = Font.HELVETICA_REGULAR, _ fontSize: CGFloat = Font.Size.commonTextFieldPlaceholder){
        self.attributedPlaceholder = NSAttributedString(string: txtPlaceholder, attributes: [
            NSAttributedString.Key.foregroundColor: color,
            NSAttributedString.Key.font : UIFont(name: font, size: fontSize)!
            ])
    }
    
    
}
