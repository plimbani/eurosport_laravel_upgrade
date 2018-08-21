//
//  LandingVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class LandingVC: SuperViewController {

    override func viewDidLoad() {
        super.viewDidLoad()
        self.navigationController?.isNavigationBarHidden = true
        
        initInfoAlertViewTwoButton(self.view, self)
        
        if USERDEFAULTS.string(forKey: kUserDefaults.token) != nil {
            sendAppversionRequest()
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
                        self.showInfoAlertViewTwoButton(title: String.localize(key: "btn_update"), message: String.localize(key: "alert_msg_app_update"), buttonYesTitle: String.localize(key: "btn_update"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.appUpgrade.rawValue)
                    } else {
                        UIApplication.shared.keyWindow?.rootViewController = Storyboards.Main.instantiateMainVC()
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
    
    @IBAction func createAccountBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateCreateAccountVC(), animated: true)
    }
    
    @IBAction func signInBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateLoginVC(), animated: true)
    }
}

extension LandingVC: CustomAlertViewTwoButtonDelegate {
    func customAlertViewTwoButtonNoBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.appUpgrade.rawValue {
            self.navigationController?.popViewController(animated: true)
        }
    }
    
    func customAlertViewTwoButtonYesBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.appUpgrade.rawValue {
            // redirect to app store
        }
    }
}
