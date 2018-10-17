//
//  TabSettingsVC.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabSettingsVC: SuperViewController {

    @IBOutlet var table: UITableView!
    
    var fieldList = NSMutableArray()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.navigationController?.isNavigationBarHidden = true
        initialize()
    }
    
    func initialize() {
        if let url = Bundle.main.url(forResource: "SettingsList", withExtension: "plist") {
            fieldList = NSMutableArray(contentsOf: url)!
        }
        
        table.separatorColor = .black
        table.tableFooterView = UIView()
        
        // Alertview
        initInfoAlertViewTwoButton(self.view, self)
    }
}

extension TabSettingsVC: CustomAlertViewTwoButtonDelegate {
    
    func customAlertViewTwoButtonNoBtnPressed(requestCode: Int) {}
    
    func customAlertViewTwoButtonYesBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.logOut.rawValue {
            USERDEFAULTS.set(nil, forKey: kUserDefaults.token)
            UIApplication.shared.keyWindow?.rootViewController = UINavigationController(rootViewController: Storyboards.Main.instantiateLandingVC())
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
                        self.showInfoAlertViewTwoButton(title: "Confirm", message: String.localize(key: "alert_msg_logout"), buttonYesTitle: String.localize(key: "btn_logout"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.logOut.rawValue)
                    }
                }
            }
        }
    }
}
