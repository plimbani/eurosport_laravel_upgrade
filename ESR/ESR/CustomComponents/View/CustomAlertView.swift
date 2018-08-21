//
//  CustomAlertView.swift
//  Marketti
//
//  Created by My on 05/04/18.
//  Copyright © 2018 Aecor. All rights reserved.
//

import UIKit

protocol CustomAlertViewDelegate {
    func customAlertViewOkBtnPressed(requestCode: Int)
}

class CustomAlertView: UIView {
    
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var lblMessage: UILabel!
    @IBOutlet var btnOk: UIButton!
    
    var delegate: CustomAlertViewDelegate?
    
    var requestCode = -1
    
    override func awakeFromNib() {
        lblTitle.font = UIFont(name: Font.HELVETICA_BOLD, size: Font.Size.customAlertViewLblTitle)
        lblMessage.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        btnOk.titleLabel?.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
    }
    
    func setTitle(_ title: String = NULL_STRING, message: String = NULL_STRING, buttonTitle: String, requestCode: Int = -1) {
        lblTitle.text = title
        lblMessage.text = message
        btnOk.setTitle(buttonTitle, for: .normal)
        btnOk.setTitle(buttonTitle, for: .highlighted)
        self.requestCode = requestCode
    }
    
    internal func show(_ animated: Bool = false) {
        if animated {
            UIView.beginAnimations(nil, context: nil)
            UIView.setAnimationDuration(0.55)
            self.alpha = 1.0
            UIView.commitAnimations()
        } else {
            self.alpha = 1.0
        }
    }
    
    internal func hide(_ animated: Bool = false) {
        if animated {
            UIView.animate(withDuration: 0.5, delay: 0.1, options: .curveEaseOut, animations: {
                UIView.beginAnimations(nil, context: nil)
                UIView.setAnimationDuration(0.55)
                self.alpha = 0.0
                UIView.commitAnimations()
            }, completion: { (finished: Bool) in
            })
        } else {
            self.alpha = 0.0
        }
    }
    
    @IBAction func okButtonPressed(_ sender: UIButton) {
        delegate?.customAlertViewOkBtnPressed(requestCode: requestCode)
        hide(true)
    }
}
