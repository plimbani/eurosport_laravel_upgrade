//
//  SuperViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class SuperViewController: UIViewController {

    @IBOutlet var titleNavigationBar: TitleNavigationBar!
    @IBOutlet var imageNavigationBar: ImageNavigationBar!
    @IBOutlet var heightConstraintLblNoInternet: NSLayoutConstraint!
    
    var button: UIButton?
    
    let cellOwner = TableCellOwner()
    
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    func setConstraintLblNoInternet(_ isShow: Bool) {
        if heightConstraintLblNoInternet != nil {
            heightConstraintLblNoInternet.constant = isShow ? 20 : 0
        }
    }
    
    func showCustomAlertVC(title: String, message: String, buttonTitle: String = String.localize(key: "btn_close"), requestCode: Int = -1, delegate: CustomAlertVCDelegate? = nil) {
        let customAlert = Storyboards.Main.instantiateCustomAlertVC()
        customAlert.providesPresentationContextTransitionStyle = true
        customAlert.definesPresentationContext = true
        customAlert.modalPresentationStyle = UIModalPresentationStyle.overFullScreen
        customAlert.modalTransitionStyle = UIModalTransitionStyle.crossDissolve
        if delegate != nil {
            customAlert.delegate = delegate
        }
        customAlert.requestCode = requestCode
        customAlert.titleString = title
        customAlert.messageString = message
        customAlert.btnOkString  = buttonTitle
        self.present(customAlert, animated: true, completion: nil)
    }
    
    func showCustomAlertTwoBtnVC(title: String, message: String, buttonYesTitle: String = String.localize(key: "btn_yes"), buttonNoTitle: String = String.localize(key: "btn_no"), requestCode: Int = -1, delegate: CustomAlertTwoBtnVCDelegate? = nil) {
        let customAlert = Storyboards.Main.instantiateCustomAlertTwoBtnVC()
        customAlert.providesPresentationContextTransitionStyle = true
        customAlert.definesPresentationContext = true
        customAlert.modalPresentationStyle = UIModalPresentationStyle.overFullScreen
        customAlert.modalTransitionStyle = UIModalTransitionStyle.crossDissolve
        if delegate != nil {
            customAlert.delegate = delegate
        }
        customAlert.requestCode = requestCode
        customAlert.titleString = title
        customAlert.messageString = message
        customAlert.btnYesString  = buttonYesTitle
        customAlert.btnNoString  = buttonNoTitle
        self.present(customAlert, animated: true, completion: nil)
    }
}

