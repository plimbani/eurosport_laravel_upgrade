//
//  CustomAlertViewTwoButton.swift
//  Marketti
//
//  Created by Pratik Patel on 17/05/18.
//  Copyright © 2018 Aecor. All rights reserved.
//

import UIKit

@objc protocol CustomAlertViewTwoButtonDelegate {
    func customAlertViewTwoButtonYesBtnPressed(requestCode: Int)
    @objc optional func customAlertViewTwoButtonNoBtnPressed(requestCode: Int)
}

class CustomAlertViewTwoButton: UIView {

    var delegate: CustomAlertViewTwoButtonDelegate?
    
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var lblMessage: UILabel!
    
    // Two buttons
    @IBOutlet var btnNo: UIButton!
    @IBOutlet var btnYes: UIButton!
    
    var requestCode = -1

    override func awakeFromNib() {
        lblTitle.font = UIFont(name: Font.HELVETICA_BOLD, size: Font.Size.customAlertViewLblTitle)
        lblMessage.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        btnNo.titleLabel?.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
        btnYes.titleLabel?.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
    }
    
    func setTitle(title: String = NULL_STRING, message: String = NULL_STRING, buttonYesTitle: String, buttonNoTitle: String, requestCode: Int = -1) {
        lblTitle.text = title
        lblMessage.text = message
        btnYes.setTitle(buttonYesTitle, for: .normal)
        btnYes.setTitle(buttonYesTitle, for: .highlighted)
        btnNo.setTitle(buttonNoTitle, for: .normal)
        btnNo.setTitle(buttonNoTitle, for: .highlighted)
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
                //
            })
        } else {
            self.alpha = 0.0
        }
    }
    
    @IBAction func yesButtonPressed(_ sender: UIButton) {
        delegate?.customAlertViewTwoButtonYesBtnPressed(requestCode: requestCode)
        hide(true)
    }
    
    @IBAction func noButtonPressed(_ sender: UIButton) {
        delegate?.customAlertViewTwoButtonNoBtnPressed!(requestCode: requestCode)
        hide(true)
    }
}
