//
//  ForgotPasswordVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class ForgotPasswordVC: SuperViewController {

    @IBOutlet var txtEmail: UITextField!
    @IBOutlet var btnSubmit: UIButton!
    @IBOutlet var lblNoInternet: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.delegate = self
        ApplicationData.setTextFieldAttributes(txtEmail)
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        btnSubmit.isEnabled = false
        txtEmail.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Hides keyboard if tap outside of view
        hideKeyboardWhenTappedAround()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
               setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    @objc func textFieldDidChange(textField: UITextField){
        btnSubmit.isEnabled = false
        btnSubmit.backgroundColor = UIColor.btnDisable
        
        if let text = txtEmail.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
            
            if !Utils.isValidEmail(text) {
                return
            }
        }
        
        btnSubmit.isEnabled = true
        btnSubmit.backgroundColor = UIColor.btnYellow
    }
    
    @IBAction func onSubmitBtnPressed(_ sender: UIButton) {
        
    }
}

extension ForgotPasswordVC : TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
