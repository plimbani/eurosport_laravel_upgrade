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
    @IBOutlet var lblMessage: UILabel!
    @IBOutlet var btnBack: UIButton!
    @IBOutlet var logoImg: UIImageView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
        let tap = UITapGestureRecognizer(target: self, action: #selector(onLogoImageClick(_:)))
        logoImg.isUserInteractionEnabled = true
        logoImg.addGestureRecognizer(tap)
    }
    
    func initialize() {
        
        ApplicationData.setTextFieldAttributes(txtEmail)
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnBack.setImageColor(color: UIColor.AppColor(), image: UIImage.init(named: "back_white")!, state: .normal)
        }
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSubmit.setTitleColor(.white, for: .normal)
            ApplicationData.setBorder(view: txtEmail, Color: .gray, CornerRadius: 0.0, Thickness: 1.0)
            lblMessage.textColor = .AppColor()
        }
        
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
    
    @IBAction func btnBackPressed(_ sender: UIButton) {
        self.navigationController?.popViewController(animated: true)
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
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSubmit.setBackgroundImage(nil, for: .normal)
        }
        
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
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSubmit.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
        }
    }
    
    func sendForgotPasswordRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["email"] = txtEmail.text!
        self.view.showProgressHUD()
        ApiManager().forgotPassword(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                if let message = result.value(forKey: "message") as? String{
                    if message == "Success"{
                        self.showInfoAlertView(title: String.localize(key: "alert_title_success"), message: String.localize(key: "alert_msg_forgot_password"), requestCode: AlertRequestCode.forgotPass.rawValue)
                    }else{
                        self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: message, requestCode: AlertRequestCode.forgotPass.rawValue)
                    }
                }
                
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
        sendForgotPasswordRequest()
    }
    @objc func onLogoImageClick(_ sender : UITapGestureRecognizer) {
        self.navigationController?.popToRootViewController(animated: true)
    }
}

extension ForgotPasswordVC : CustomAlertViewDelegate {
    func customAlertViewOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.forgotPass.rawValue {
            self.navigationController?.popViewController(animated: true)
        }
    }
}

