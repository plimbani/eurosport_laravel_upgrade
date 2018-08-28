//
//  NotificationAndSoundVC.swift
//  ESR
//
//  Created by Pratik Patel on 21/08/18.
//

import UIKit

class NotificationAndSoundVC: SuperViewController {

    @IBOutlet var switchSound: UISwitch!
    @IBOutlet var switchVibration: UISwitch!
    @IBOutlet var switchNotifications: UISwitch!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_notification_sound")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        switchSound.setOn(USERDEFAULTS.bool(forKey: kUserDefaults.isSound), animated: false)
        switchVibration.setOn(USERDEFAULTS.bool(forKey: kUserDefaults.isVibration), animated: false)
        switchNotifications.setOn(USERDEFAULTS.bool(forKey: kUserDefaults.isNotification), animated: false)
    }
    
    @IBAction func onSoundSwitchValueChanged(_ sender: UISwitch) {
        sendUpdateSettingsRequest()
    }
    
    @IBAction func onVibrationSwitchValueChanged(_ sender: UISwitch) {
        sendUpdateSettingsRequest()
    }
    
    @IBAction func onNotificationsSwitchValueChanged(_ sender: UISwitch) {
       sendUpdateSettingsRequest()
    }
    
    func sendUpdateSettingsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        
        parameters["is_sound"] = switchSound.isOn
        parameters["is_vibration"] = switchVibration.isOn
        parameters["is_notification"] = switchNotifications.isOn
        
        var serverUserSettings: [String: Any] = [:]
        serverUserSettings["userSettings"] = parameters
        if let userData = ApplicationData.sharedInstance().getUserData() {
            serverUserSettings["userId"] = userData.id
        }
        var serverUserData: [String: Any] = [:]
        serverUserData["userData"] = serverUserSettings
        
        print("Json \n \(ApplicationData.convertJsonStringFromJsonObject(serverUserData))")
        
        ApiManager().updateUserSettings(serverUserData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let userData = ApplicationData.sharedInstance().getUserData() {
                    USERDEFAULTS.set(self.switchSound.isOn, forKey: kUserDefaults.isSound)
                    USERDEFAULTS.set(self.switchVibration.isOn, forKey: kUserDefaults.isVibration)
                    USERDEFAULTS.set(self.switchNotifications.isOn, forKey: kUserDefaults.isNotification)
                    
                    ApplicationData.sharedInstance().saveUserData(userData)
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
}

extension NotificationAndSoundVC: TitleNavigationBarDelegate {
    
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
