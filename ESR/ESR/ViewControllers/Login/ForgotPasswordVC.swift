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
        
        // Alerview
        initInfoAlertView(self.view, self)
        
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
    
    func sendForgotPasswordRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["email"] = txtEmail.text!
        ApiManager().forgotPassword(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                self.showInfoAlertView(title: String.localize(key: "alert_title_success"), message: String.localize(key: "alert_msg_forgot_password"), requestCode: AlertRequestCode.forgotPass.rawValue)
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    @IBAction func onSubmitBtnPressed(_ sender: UIButton) {
        
    }
}

extension ForgotPasswordVC : CustomAlertViewDelegate {
    func customAlertViewOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.forgotPass.rawValue {
            self.navigationController?.popViewController(animated: true)
        }
    }
}

extension ForgotPasswordVC : TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
