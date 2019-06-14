//
//  CustomAlertTwoBtnVC.swift
//  ESR
//
//  Created by Pratik Patel on 25/02/19.
//

import UIKit

@objc protocol CustomAlertTwoBtnVCDelegate {
    func customAlertTwoBtnVCYesBtnPressed(requestCode: Int)
    @objc optional func customAlertTwoBtnVCNoBtnPressed(requestCode: Int)
}

class CustomAlertTwoBtnVC: UIViewController {

    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var lblMessage: UILabel!
    @IBOutlet var btnNo: UIButton!
    @IBOutlet var btnYes: UIButton!
    
    var delegate: CustomAlertTwoBtnVCDelegate?
    var requestCode = -1
    var titleString = NULL_STRING
    var messageString = NULL_STRING
    var btnYesString = NULL_STRING
    var btnNoString = NULL_STRING
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    func initialize(){
        lblTitle.font = UIFont(name: Font.HELVETICA_BOLD, size: Font.Size.customAlertViewLblTitle)
        lblMessage.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        btnNo.titleLabel?.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
        btnYes.titleLabel?.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
        
        if titleString != NULL_STRING {
            lblTitle.text = titleString
        }
        
        if messageString != NULL_STRING {
            lblMessage.text = messageString
        }
        
        if btnYesString != NULL_STRING {
            btnYes.setTitle(btnYesString, for: .normal)
        }
        
        if btnNoString != NULL_STRING {
            btnNo.setTitle(btnNoString, for: .normal)
        }
    }
    
    @IBAction func yesButtonPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " yesButtonPressed")
        delegate?.customAlertTwoBtnVCYesBtnPressed(requestCode: requestCode)
        self.dismiss(animated: true, completion: nil)
    }
    
    @IBAction func noButtonPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " noButtonPressed")
        delegate?.customAlertTwoBtnVCNoBtnPressed!(requestCode: requestCode)
        self.dismiss(animated: true, completion: nil)
    }
}
