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
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        let navigationBar = self.navigationController?.navigationBar
        
        navigationBar?.setBackgroundImage(UIImage(), for: .default)
        navigationBar?.shadowImage = UIImage()
        navigationBar?.isTranslucent = true
    }
    
    func initialize() {
        
        ApplicationData.setTextFieldAttributes(txtEmail)
        ApplicationData.setTextFieldAttributes(txtPassword)
        
        let tap = UITapGestureRecognizer(target: self, action: #selector(onLogoImageClick(_:)))
        logoImg.isUserInteractionEnabled = true
        logoImg.addGestureRecognizer(tap)
        
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
        
        txtEmail.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        txtPassword.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
            
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
        updateLoginBtn()
    }
    
    func updateLoginBtn() {
        btnLogin.isEnabled = false
        btnLogin.backgroundColor = UIColor.btnDisable
        btnLogin.setTitleColor(.black, for: .normal)
        
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
            
            if text.count < 5 {
                return
            }
        }
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnLogin.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnLogin.setTitleColor(.white, for: .normal)
        }
        
        btnLogin.isEnabled = true
        btnLogin.backgroundColor = UIColor.btnYellow
    }
    
    func loginAPI() {
        if APPDELEGATE.reachability.connection == .none {
            self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: String.localize(key: "string_no_internet"))
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
                    
                    self.getUserDetailsAPI()
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
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    func getUserDetailsAPI() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        ApiManager().getUserDetails([:], success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let authenticated = result.value(forKey: "authenticated") as? Bool {
                    if authenticated {
                        ParseManager.parseLogin(result)
                        self.updateFCMTokenAPI()
                        
                        ApplicationData.temLoginFlag = true

                        if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                            keyWindow.rootViewController = Storyboards.Main.instantiateMainVC()
                        }
                    } else {
                        if let message = result.value(forKey: "message") as? String {
                            USERDEFAULTS.set(nil, forKey: kUserDefaults.token)
                            self.showCustomAlertVC(title: String.localize(key: "alert_title_email_verification"), message: message, buttonTitle: String.localize(key: "btn_resend"),  requestCode: AlertRequestCode.resendEmail.rawValue, delegate: self)
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
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    func resendEmailAPI(email: String) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String {
            parameters["email"] = email
            
            ApiManager().resendEmail(parameters, success: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    if let message = result.value(forKey: "message") as? String {
                        self.showCustomAlertVC(title: String.localize(key: "alert_title_success"), message: message)
                    }
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    if result.allKeys.count == 0 {
                        return
                    }
                }
            })
        }
    }
    
    @IBAction func btnBackPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " btnBackPressed")
        self.navigationController?.popViewController(animated: true)
    }
    
    @IBAction func rememberMeBtnPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " rememberMeBtnPressed")
        isRememberMe = !isRememberMe
        btnRememberMe.setImage(isRememberMe ? UIImage.init(named: "icon_check") : UIImage.init(named: "icon_uncheck"), for: .normal)
    }
    
    @IBAction func signInBtnPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " signInBtnPressed")
        loginAPI()
    }
    
    @IBAction func forgotPassBtnPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " forgotPassBtnPressed")
        self.navigationController?.pushViewController(Storyboards.Main.instantiateForgotPasswordVC(), animated: true)
    }
    @objc func onLogoImageClick(_ sender : UITapGestureRecognizer) {
        TestFairy.log(String(describing: self) + " onLogoImageClick")
        self.navigationController?.popToRootViewController(animated: true)
    }
}

extension LoginVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.resendEmail.rawValue {
            resendEmailAPI(email: txtEmail.text!)
        }
    }
}

extension LoginVC {
    func updateFCMTokenAPI() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        if let fcmToken = USERDEFAULTS.string(forKey: kUserDefaults.fcmToken) {
            TestFairy.log("FCM token \(fcmToken)")
            print("FCM token\n")
            print("\(fcmToken)")
            print("\n")
            var parameters: [String: Any] = [:]
            
            if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String {
                parameters["email"] = email
                parameters["fcm_id"] = fcmToken
                
                ApiManager().updateFCMTokem(parameters, success: { result in
                    print("FCM token has updated")
                    TestFairy.log("FCM token has updated")
                }, failure: { result in })
            }
        }
    }
}


