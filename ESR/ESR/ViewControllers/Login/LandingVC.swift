//
//  LandingVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class LandingVC: SuperViewController {

    @IBOutlet var lblAppVersion: UILabel!
    
    @IBOutlet var btnSignIn: UIButton!
    @IBOutlet var btnCreateAccount: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        self.navigationController?.isNavigationBarHidden = true
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSignIn.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnCreateAccount.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnSignIn.setTitleColor(.white, for: .normal)
            btnCreateAccount.setTitleColor(.white, for: .normal)
        }
        
        // Localization
        btnSignIn.setTitle(String.localize(key: "Sign in"), for: .normal)
        btnCreateAccount.setTitle(String.localize(key: "Create account"), for: .normal)
        
        // Sets app version
        if let version = Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String {
            lblAppVersion.text = String.init(format: String.localize(key: "string_app_version"), arguments: [version])
        }
        
        // initInfoAlertViewTwoButton(self.view, self)
        
        if USERDEFAULTS.string(forKey: kUserDefaults.token) != nil {
            updateToken()
        } else {
            // Checks if new app version is available or not
            sendAppversionRequest()
        }
    }
    
    func updateToken() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        if !USERDEFAULTS.bool(forKey: kUserDefaults.isLogin) {
            return
        }
        
        var parameters: [String: Any] = [:]
        
        if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String,  let password = USERDEFAULTS.value(forKey: kUserDefaults.password) as? String {
            parameters["email"] = email
            parameters["password"] = password
            
            self.view.showProgressHUD()
            
            ApiManager().login(parameters, success: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    if let token = result.value(forKey: "token") as? String {
                        USERDEFAULTS.set(token, forKey: kUserDefaults.token)
                    }
                    
                    let viewController = Storyboards.Main.instantiateMainVC()
                    UIApplication.shared.keyWindow?.rootViewController = viewController
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    if result.allKeys.count == 0 {
                        return
                    }
                    
                    // if let error = result.value(forKey: "error") as? String {
                    // self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
                    //}
                }
            })
        }
    }
    
    func sendAppversionRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        let parameters: [String: Any] = [:]
        ApiManager().getAppVersion(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                if let serverVersion = result.value(forKey: "ios_app_version") as? String {
                    let appVersion = Bundle.main.object(forInfoDictionaryKey: "CFBundleShortVersionString") as! String
                    
                    // 1 - left version is greater than right version
                    if Utils.compareVersion(serverVersion, appVersion) == 1 {
                        self.showCustomAlertTwoBtnVC(title: String.localize(key: "alert_title_app_update"), message: String.localize(key: "alert_msg_app_update"), buttonYesTitle: String.localize(key: "btn_update"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.appUpgrade.rawValue, delegate: self)
                        
                        ApplicationData.isAppUpdateDispalyed = true
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
    
    @IBAction func createAccountBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateCreateAccountVC(), animated: true)
    }
    
    @IBAction func signInBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateLoginVC(), animated: true)
    }
}

extension LandingVC: CustomAlertTwoBtnVCDelegate {
    func customAlertTwoBtnVCNoBtnPressed(requestCode: Int) {}
    
    func customAlertTwoBtnVCYesBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.appUpgrade.rawValue {
            if let url = URL(string: APPSTORE_APP_URL),
                UIApplication.shared.canOpenURL(url){
                if #available(iOS 10.0, *) {
                    UIApplication.shared.open(url, options: [:], completionHandler: nil)
                } else {
                    UIApplication.shared.openURL(url)
                }
            }
        }
    }
}

/*extension LandingVC: CustomAlertViewTwoButtonDelegate {
    func customAlertViewTwoButtonNoBtnPressed(requestCode: Int) {}
    
    func customAlertViewTwoButtonYesBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.appUpgrade.rawValue {
            if let url = URL(string: APPSTORE_APP_URL),
                UIApplication.shared.canOpenURL(url){
                if #available(iOS 10.0, *) {
                    UIApplication.shared.open(url, options: [:], completionHandler: nil)
                } else {
                    UIApplication.shared.openURL(url)
                }
            }
        }
    }
}*/
