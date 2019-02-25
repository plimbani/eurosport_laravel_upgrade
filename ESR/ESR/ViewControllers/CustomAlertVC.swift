//
//  CustomAlertVC.swift
//  ESR
//
//  Created by Pratik Patel on 25/02/19.
//

import UIKit

protocol CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int)
}

class CustomAlertVC: UIViewController {

    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var lblMessage: UILabel!
    @IBOutlet var btnOk: UIButton!
    
    var requestCode = -1
    var delegate: CustomAlertVCDelegate?
    var titleString = NULL_STRING
    var messageString = NULL_STRING
    var btnOkString = NULL_STRING
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        lblTitle.font = UIFont(name: Font.HELVETICA_BOLD, size: Font.Size.customAlertViewLblTitle)
        lblMessage.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
        btnOk.titleLabel?.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
        
        if titleString != NULL_STRING {
            lblTitle.text = titleString
        }
        
        if messageString != NULL_STRING {
            lblMessage.text = messageString
        }
        
        if btnOkString != NULL_STRING {
            btnOk.setTitle(btnOkString, for: .normal)
        }
    }
    
    @IBAction func btnOkPressed(_ sender: UIButton) {
        delegate?.customAlertVCOkBtnPressed(requestCode: requestCode)
        self.dismiss(animated: true, completion: nil)
    }
    
}
