//
//  TabSettingsVC.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit
import FirebaseInstanceID

class TabSettingsVC: SuperViewController {

    @IBOutlet var table: UITableView!
    
    var fieldList = NSMutableArray()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        self.navigationController?.isNavigationBarHidden = true
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        if ApplicationData.facebookDetailsPending {
            self.navigationController?.pushViewController(Storyboards.Settings.instantiateProfileVC(), animated: true)
            return
        }
        
        table.reloadData()
    }
    
    func initialize() {
        if let url = Bundle.main.url(forResource: "SettingsList", withExtension: "plist") {
            fieldList = NSMutableArray(contentsOf: url)!
        }
        
        table.separatorColor = .black
        table.tableFooterView = UIView()
        
        // Register to receive notification
        NotificationCenter.default.addObserver(self, selector: #selector(goToSelectCountry), name: .selectCountry, object: nil)
    }
    
    @objc func goToSelectCountry() {
        self.navigationController?.pushViewController(Storyboards.Settings.instantiateProfileVC(), animated: true)
    }
}

extension TabSettingsVC: CustomAlertTwoBtnVCDelegate {
    func customAlertTwoBtnVCNoBtnPressed(requestCode: Int) {}
    
    func customAlertTwoBtnVCYesBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.logOut.rawValue {
            USERDEFAULTS.set(nil, forKey: kUserDefaults.token)
            USERDEFAULTS.set(nil, forKey: kUserDefaults.selectedTournament)
            USERDEFAULTS.set(nil, forKey: kUserDefaults.userData)
            USERDEFAULTS.set(false, forKey: kUserDefaults.isLogin)
            USERDEFAULTS.set(false, forKey: kUserDefaults.isFacebookLogin)
            let instance = InstanceID.instanceID()
            instance.deleteID { (error) in
                print(error.debugDescription)
            }

            if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                keyWindow.rootViewController = Storyboards.Main.instantiateLandingVC()
            }
        }
    }
}

extension TabSettingsVC: UITableViewDataSource, UITableViewDelegate {
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return fieldList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 50
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "cellIdentifier")
        if cell == nil {
            cell = UITableViewCell(style: .default, reuseIdentifier: "cellIdentifier")
            cell!.separatorInset = .zero
            cell!.selectionStyle = .none
            let imgView = UIImageView(frame: CGRect(x: tableView.frame.size.width - 40, y: 13, width: 22, height: 22))
            
            imgView.image = UIImage(named: "arrow_right_green")!
            imgView.image = imgView.image!.withRenderingMode(.alwaysTemplate)
            imgView.contentMode = .scaleAspectFit
            imgView.tintColor = UIColor.settingsGreenArrow
            cell!.contentView.addSubview(imgView)
            cell!.textLabel!.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
            cell!.textLabel!.textColor = UIColor.black
        }
        if let record = fieldList[indexPath.row] as? NSDictionary {
            
            if let title = record.value(forKey: "title") as? String {
                if !title.isEmpty {
                    cell!.textLabel!.text = title
                    
                    if indexPath.row == 0 {
                        cell!.textLabel!.text = String.localize(key: "Profile")
                    } else if indexPath.row == 1 {
                        cell!.textLabel!.text = String.localize(key: "Notifications & sounds")
                    } else if indexPath.row == 2 {
                        cell!.textLabel!.text = String.localize(key: "Privacy & terms")
                    } else if indexPath.row == 3 {
                        cell!.textLabel!.text = String.localize(key: "Log out")
                    }
                }
            }
            
            if let imgName = record.value(forKey: "image") as? String {
                if !imgName.isEmpty {
                    if let image = UIImage(named: imgName) {
                        cell!.imageView!.image = image
                    }
                }
            }
        }
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        if let record = fieldList[indexPath.row] as? NSDictionary {
            if let identifier = record.value(forKey: "identifier") as? String {
                if !identifier.isEmpty {
                    
                    if identifier == "ProfileVC" {
                        self.navigationController?.pushViewController(Storyboards.Settings.instantiateProfileVC(), animated: true)
                    } else if identifier == "NotificationAndSoundVC" {
                        self.navigationController?.pushViewController(Storyboards.Settings.instantiateNotificationAndSoundVC(), animated: true)
                    } else if identifier == "PrivacyAndTermsVC" {
                        self.navigationController?.pushViewController(Storyboards.Settings.instantiatePrivacyAndTermsVC(), animated: true)
                    } else if identifier == "Logout" {
                        self.showCustomAlertTwoBtnVC(title: "Confirm", message: String.localize(key: "alert_msg_logout"), buttonYesTitle: String.localize(key: "btn_logout"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.logOut.rawValue, delegate: self)
                    }
                }
            }
        }
    }
}
