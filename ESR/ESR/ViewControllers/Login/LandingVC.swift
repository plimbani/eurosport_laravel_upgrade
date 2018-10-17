//
//  LandingVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class LandingVC: SuperViewController {

    @IBOutlet var lblAppVersion: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        self.navigationController?.isNavigationBarHidden = true
        
        // Sets app version
        if let version = Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String {
            lblAppVersion.text = String.init(format: String.localize(key: "string_app_version"), arguments: [version])
        }
        
        initInfoAlertViewTwoButton(self.view, self)
        
        if USERDEFAULTS.string(forKey: kUserDefaults.token) != nil {
            let viewController = Storyboards.Main.instantiateMainVC()
            viewController.isFromLanding = true
            UIApplication.shared.keyWindow?.rootViewController = viewController
        } else {
            // Checks if new app version is available or not
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
                    let appVersion = Bundle.main.object(forInfoDictionaryKey: "CFBundleVersion") as! String
                    
                    // 1 - left version is greater than right version
                    if Utils.compareVersion(serverVersion, appVersion) == 1 {
                        self.showInfoAlertViewTwoButton(title: String.localize(key: "alert_title_app_update"), message: String.localize(key: "alert_msg_app_update"), buttonYesTitle: String.localize(key: "btn_update"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.appUpgrade.rawValue)
                        
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
}
