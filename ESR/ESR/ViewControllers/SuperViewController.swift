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
    
    // AlertView
    var infoAlertView: CustomAlertView!
    // AlertView two button
    var infoAlertViewTwoButton: CustomAlertViewTwoButton!
    
    let cellOwner = TableCellOwner()
    @IBOutlet var heightConstraintLblNoInternet: NSLayoutConstraint!
    
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    func setConstraintLblNoInternet(_ isShow: Bool) {
        if heightConstraintLblNoInternet != nil {
            heightConstraintLblNoInternet.constant = isShow ? 20 : 0
        }
    }
    
    func initInfoAlertView(_ view: UIView, _ delegate: CustomAlertViewDelegate? = nil) {
        _ = cellOwner.loadMyNibFile(nibName: "CustomAlertView")
        infoAlertView = cellOwner.view as! CustomAlertView
        infoAlertView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: DEVICE_HEIGHT)
        if delegate != nil {
            infoAlertView.delegate = delegate
        }
        infoAlertView.hide()
        view.addSubview(infoAlertView)
    }
    
    func showInfoAlertView(title: String, message: String, buttonTitle: String = String.localize(key: "btn_ok"), requestCode: Int = -1) {
        if infoAlertView != nil {
            infoAlertView.setTitle(title, message: message, buttonTitle: buttonTitle, requestCode: requestCode)
            infoAlertView.show()
        }
    }
    
    func initInfoAlertViewTwoButton(_ view: UIView, _ delegate: CustomAlertViewTwoButtonDelegate) {
        _ = cellOwner.loadMyNibFile(nibName: "CustomAlertViewTwoButton")
        infoAlertViewTwoButton = cellOwner.view as! CustomAlertViewTwoButton
        infoAlertViewTwoButton.delegate = delegate
        infoAlertViewTwoButton.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: DEVICE_HEIGHT)
        infoAlertViewTwoButton.hide()
        view.addSubview(infoAlertViewTwoButton)
    }
    
    func showInfoAlertViewTwoButton(title: String = NULL_STRING, message: String = NULL_STRING, buttonYesTitle: String = String.localize(key: "btn_yes"), buttonNoTitle: String = String.localize(key: "btn_no"), requestCode: Int = -1) {
        if infoAlertViewTwoButton != nil {
            infoAlertViewTwoButton.setTitle(title: title, message: message, buttonYesTitle: buttonYesTitle, buttonNoTitle: buttonNoTitle, requestCode: requestCode)
            infoAlertViewTwoButton.show()
        }
    }
}

