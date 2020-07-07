//
//  TextFieldCell.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit

class TextFieldCell: UITableViewCell {

    @IBOutlet var txtField: UITextField!
    var record = NSDictionary()
    
    override func awakeFromNib() {
        super.awakeFromNib()
        txtField.autocorrectionType = .no
        txtField.setLeftPaddingPoints(10)
        txtField.setRightPaddingPoints(10)
        txtField.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
    }
    
    func setBorder() {
        ApplicationData.setBorder(view: txtField, Color: UIColor.txtPlaceholderBorder, CornerRadius: 0.0, Thickness: 1.0)
    }
    
    func reloadCell() {
        if let placeholder = record.value(forKey: "placeholder") as? String {
            txtField.placeholder = placeholder
            txtField.attributedPlaceholder = NSAttributedString(string: placeholder, attributes: [
                NSAttributedString.Key.foregroundColor: UIColor.txtPlaceholderTxt,
                NSAttributedString.Key.font : UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldPlaceholder)!
                ])
        }
        
        if let secured = record.value(forKey: "secured") as? Bool {
            txtField.isSecureTextEntry = secured
        }
        
        if let rawValue = record.value(forKey: "keyboardType") as? Int {
            if let keyboardType = CustomKeyboardType(rawValue: rawValue) {
                switch keyboardType {
                case .general:
                    txtField.keyboardType = .default
                case .number:
                    txtField.keyboardType = .numbersAndPunctuation
                case .email:
                    txtField.keyboardType = .emailAddress
                case .URL:
                    txtField.keyboardType = .URL
                }
            }
        }
        
        if let rawValue = record.value(forKey: "keyboardReturn") as? Int {
            if let returnType = CustomKeyboardReturn(rawValue: rawValue) {
                switch returnType {
                case .kReturn:
                    txtField.returnKeyType = .default
                case .next:
                    txtField.returnKeyType = .next
                case .search:
                    txtField.returnKeyType = .search
                case .send:
                    txtField.returnKeyType = .send
                case .done:
                    txtField.returnKeyType = .done
                }
            }
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
}
