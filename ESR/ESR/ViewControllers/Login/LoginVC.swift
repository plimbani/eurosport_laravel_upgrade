//
//  LoginVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class LoginVC: SuperViewController {

    @IBOutlet var txtEmail: UITextField!
    @IBOutlet var txtPassword: UITextField!
    @IBOutlet var lblNoInternet: UILabel!
    @IBOutlet var btnLogin: UIButton!
    @IBOutlet var btnRememberMe: UIButton!
    @IBOutlet var btnForgotPass: UIButton!
    @IBOutlet var btnBack: UIButton!
    @IBOutlet var logoImg: UIImageView!
    
    
    var isRememberMe = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
        let tap = UITapGestureRecognizer(target: self, action: #selector(onLogoImageClick(_:)))
        logoImg.isUserInteractionEnabled = true
        logoImg.addGestureRecognizer(tap)
    }
    
    func initialize() {
        
        ApplicationData.setTextFieldAttributes(txtEmail)
        ApplicationData.setTextFieldAttributes(txtPassword)
        
        isRememberMe = USERDEFAULTS.bool(forKey: kUserDefaults.isRememberLogin)
        if isRememberMe {
            if let email = USERDEFAULTS.string(forKey: kUserDefaults.email),
                let password = USERDEFAULTS.string(forKey: kUserDefaults.password) {
                txtEmail.text = email
                txtPassword.text = password
            }
            
            btnRememberMe.setImage(isRememberMe ? UIImage.init(named: "icon_check") : UIImage.init(named: "icon_uncheck"), for: .normal)
        }
        updateLoginBtn()
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnBack.setImageColor(color: UIColor.AppColor(), image: UIImage.init(named: "back_white")!, state: .normal)
            btnLogin.setTitleColor(.white, for: .normal)
            
            ApplicationData.setBorder(view: txtEmail, Color: .gray, CornerRadius: 0.0, Thickness: 1.0)
            ApplicationData.setBorder(view: txtPassword, Color: .gray, CornerRadius: 0.0, Thickness: 1.0)
            
            btnRememberMe.setTitleColor(.AppColor(), for: .normal)
            btnForgotPass.setTitleColor(.AppColor(), for: .normal)
        }
        
        // txtEmail.text = "asoni@aecordigital.com"
        // txtPassword.text = "password"
        
        txtEmail.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        txtPassword.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
            
        // Events when keyboard shows and hides
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillHide), name: NSNotification.Name.UIKeyboardWillHide, object: nil)
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Alert view
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
    
    @objc func keyboardWillShow(notification: NSNotification) {
        if ((notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue) != nil {
            if self.view.frame.origin.y == 0{
                self.view.frame.origin.y -= 50
            }
        }
    }
    
    @objc func keyboardWillHide(notification: NSNotification) {
        if ((notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue) != nil {
            if self.view.frame.origin.y != 0{
                self.view.frame.origin.y += 50
            }
        }
    }
    
    @objc func textFieldDidChange(textField: UITextField){
        updateLoginBtn()
    }
    
    func updateLoginBtn() {
        btnLogin.isEnabled = false
        btnLogin.backgroundColor = UIColor.btnDisable
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnLogin.setBackgroundImage(nil, for: .normal)
        }
        
        if let text = txtEmail.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
            
            if !Utils.isValidEmail(text) {
                return
            }
        }
        
        if let text = txtPassword.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
            
            if text.count < 6 {
                return
            }
        }
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnLogin.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
        }
        
        btnLogin.isEnabled = true
        btnLogin.backgroundColor = UIColor.btnYellow
    }
    
    func sendLoginRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        parameters["email"] = txtEmail.text!
        parameters["password"] = txtPassword.text!
        
        ApiManager().login(parameters, success: { result in
            DispatchQueue.main.async {
                if let token = result.value(forKey: "token") as? String {
                    USERDEFAULTS.set(token, forKey: kUserDefaults.token)
                    USERDEFAULTS.set(self.txtEmail.text!, forKey: kUserDefaults.email)
                    USERDEFAULTS.set(self.txtPassword.text!, forKey: kUserDefaults.password)
                    
                    if self.isRememberMe {
                        USERDEFAULTS.set(true, forKey: kUserDefaults.isRememberLogin)
                    } else {
                        USERDEFAULTS.set(false, forKey: kUserDefaults.isRememberLogin)
                    }
                    
                    USERDEFAULTS.set(true, forKey: kUserDefaults.isLogin)
                    
                    self.sendGetUserDetailsRequest()
                } else {
                    self.view.hideProgressHUD()
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
    
    func sendGetUserDetailsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        
        ApiManager().getUserDetails(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let authenticated = result.value(forKey: "authenticated") as? Bool {
                    if authenticated {
                        ParseManager.parseLogin(result)
                        if let fcmToken = USERDEFAULTS.string(forKey: kUserDefaults.fcmToken) {
                            self.updateFCMToken(fcmToken)
                        }
                        UIApplication.shared.keyWindow?.rootViewController = Storyboards.Main.instantiateMainVC()
                    } else {
                        if let message = result.value(forKey: "message") as? String {
                            self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: message)
                        }
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
    
    
    func updateFCMToken(_ token: String) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        
        if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String {
            parameters["email"] = email
            parameters["fcm_id"] = token
            
            ApiManager().updateFCMTokem(parameters, success: { result in
                DispatchQueue.main.async {
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    if result.allKeys.count == 0 {
                        return
                    }
                }
            })
        }
    }
    
    @IBAction func btnBackPressed(_ sender: UIButton) {
        self.navigationController?.popViewController(animated: true)
    }
    
    @IBAction func rememberMeBtnPressed(_ sender: UIButton) {
        isRememberMe = !isRememberMe
        btnRememberMe.setImage(isRememberMe ? UIImage.init(named: "icon_check") : UIImage.init(named: "icon_uncheck"), for: .normal)
    }
    
    @IBAction func signInBtnPressed(_ sender: UIButton) {
        sendLoginRequest()
    }
    
    @IBAction func forgotPassBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateForgotPasswordVC(), animated: true)
    }
    @objc func onLogoImageClick(_ sender : UITapGestureRecognizer) {
        self.navigationController?.popToRootViewController(animated: true)
    }
}

extension LoginVC: CustomAlertViewDelegate {
    func customAlertViewOkBtnPressed(requestCode: Int) {
        
    }
}

