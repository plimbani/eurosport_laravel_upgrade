//
//  ApplicationData.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit
import Foundation
import CoreLocation

class ApplicationData: NSObject {
    
    static var applicationData:ApplicationData!
    let reachability = Reachability()!
    
    static let languageList: [String] = ["English", "French", "Italian", "German", "Dutch", "Czech", "Danish", "Polish"]
    static let localeKeyList: [String] = ["en", "fr", "it", "de", "nl", "cs", "da", "pl"]
    
    static let rolesList = ["Player", "Coach/Manager/Trainer", "Other"]
    
    static var countriesList = NSArray()
    
    static var isAppUpdateDispalyed = false
    
    static var accessCodeFromURL = NULL_STRING
    
    static var facebookDetailsPending = false
    
    static let dicKeyDivision = "isDivision"
    static let dicKeyDivisionRow = "isDivisionRow"
    static let dicKeyDivisionName = "dicKeyDivisionName"
    
    static var temLoginFlag = false
    
    // For maintaining targets
    enum CurrentTargetList: String {
        case ESR
        case EasyMM
    }
    static var currentTarget = CurrentTargetList.ESR.rawValue
        
    static func sharedInstance() -> ApplicationData {
        if (applicationData == nil) {
            applicationData = ApplicationData()
        }
        return applicationData
    }
    
    override init() {
        API_URL.BASE_URL = Environment().configuration(PlistKey.BaseURL)
        
        if let bundleID = Bundle.main.bundleIdentifier {
            if bundleID == "com.aecor.eurosports.easymatchmanager" {
                ApplicationData.currentTarget = CurrentTargetList.EasyMM.rawValue
            } else if bundleID == "com.aecordigital.eurosporting" {
                ApplicationData.currentTarget = CurrentTargetList.ESR.rawValue
            }
        }
        
        // Network connectivity check
        reachability.whenReachable = { reachability in
            if reachability.connection == .wifi || reachability.connection == .cellular {
                var userInfo: [String : Any] = [:]
                userInfo[kNotification.isShow] = false
                NotificationCenter.default.post(name: .internetConnectivity, object: nil, userInfo: userInfo)
            }
        }
        
        reachability.whenUnreachable = { _ in
            var userInfo: [String : Any] = [:]
            userInfo[kNotification.isShow] = true
            NotificationCenter.default.post(name: .internetConnectivity, object: nil, userInfo: userInfo)
        }
        
        do {
            try reachability.startNotifier()
        } catch {
            print("Unable to start notifier")
        }
    }
    
    func isTournamentInPreview() -> Bool {
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            if selectedTournament.status.trimmingCharacters(in: .whitespacesAndNewlines) == "Preview" {
                return true
            }
        }
        
        return false
    }
    
    static func setTextFieldAttributes(_ textField: UITextField) {
        textField.setLeftPaddingPoints(10)
        
        textField.attributedPlaceholder = NSAttributedString(string: textField.placeholder!, attributes: [
            NSAttributedStringKey.foregroundColor: UIColor.txtPlaceholderTxt,
            NSAttributedStringKey.font : UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldPlaceholder)!
            ])
    }
    
    static func setBorder(view: UIView, Color: UIColor, CornerRadius: CGFloat, Thickness: CGFloat) {
        let viewLayer = view.layer
        viewLayer.masksToBounds = true
        viewLayer.cornerRadius = CornerRadius
        viewLayer.borderWidth = Thickness
        viewLayer.borderColor = Color.cgColor
    }
    
    static func setBottomLine(_ view: UIView, Color: UIColor, Thickness: CGFloat) {
        let layer = CALayer()
        layer.borderWidth = Thickness
        layer.borderColor = Color.cgColor
        layer.frame = CGRect(x: 0, y: view.frame.size.height - Thickness, width:  view.frame.size.width, height: view.frame.size.height)
        view.layer.masksToBounds = true
        view.layer.addSublayer(layer)
    }
    
    static func setBorder(_ view: UIView, Color: UIColor, thickness: CGFloat, type: ViewBorderType) {
        let layer = CALayer()
        layer.borderWidth = thickness
        layer.borderColor = Color.cgColor
        
        if type == .top {
            layer.frame = CGRect(x: 0, y: 0, width: view.frame.size.width, height: thickness)
        } else if type == .bottom {
            layer.frame = CGRect(x: 0, y: view.frame.size.height - thickness, width:  view.frame.size.width, height: view.frame.size.height)
        } else if type == .left {
            layer.frame =  CGRect(x: 0, y: 0, width: thickness, height: view.frame.size.height)
        } else if type == .right {
            layer.frame = CGRect(x: view.frame.size.width - thickness, y: 0, width: thickness, height: view.frame.size.height)
        }
        
        view.layer.masksToBounds = true
        view.layer.addSublayer(layer)
    }
    
    func saveUserData(_ userData: UserData?) {
        USERDEFAULTS.set(NSKeyedArchiver.archivedData(withRootObject: userData), forKey: kUserDefaults.userData)
    }
    
    func getUserData() -> UserData? {
        if let data = USERDEFAULTS.data(forKey: kUserDefaults.userData) {
            if let user = NSKeyedUnarchiver.unarchiveObject(with: data) {
                return user as? UserData
            }
        }
        return nil
    }
    
    func saveSelectedTournament(_ tournament: Tournament?) {
        USERDEFAULTS.set(NSKeyedArchiver.archivedData(withRootObject: tournament), forKey: kUserDefaults.selectedTournament)
    }
    
    func getSelectedTournament() -> Tournament? {
        if let data = USERDEFAULTS.data(forKey: kUserDefaults.selectedTournament) {
            if let tournament = NSKeyedUnarchiver.unarchiveObject(with: data) {
                return tournament as? Tournament
            }
        }
        return nil
    }
    
    static func getCountDownTime(_ dateStr: String, dateFormat: String = kDateFormat.format1) -> Date? {
        let formatter = DateFormatter()
        formatter.dateFormat = dateFormat
        formatter.timeZone = TimeZone(identifier: TimeZone.current.identifier)
        
        // print("TIMEZONE: \(dateStr)")
        // print("TIMEZONE: \(TimeZone(identifier: TimeZone.current.identifier))")
        
        if let newDate = formatter.date(from: dateStr) {
            return newDate
        }
        
        return nil
    }
    
    static func getFormattedDate(_ dateStr: String, dateFormat: String = kDateFormat.format1) -> Date? {
        let formatter = DateFormatter()
        formatter.dateFormat = dateFormat
        
        var localeStr = "en"
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
            localeStr = userData.locale
        }
        
        formatter.locale = Locale(identifier: localeStr)
        
        if let newDate = formatter.date(from: dateStr) {
            return newDate
        }
        
        return nil
    }
    
    func getSelectedLocale() -> (String, String, Int){
        var selectedLocale = NULL_STRING
        var selectedLanguage = NULL_STRING
        var selectedPosition = 0
        if let userData = getUserData() {
            if !userData.locale.isEmpty {
                for i in 0..<ApplicationData.localeKeyList.count{
                    if userData.locale == ApplicationData.localeKeyList[i] {
                        selectedLocale = userData.locale
                        selectedLanguage = ApplicationData.languageList[i]
                        selectedPosition = i
                        break
                    }
                }
            }
        }
        
        return (selectedLocale, selectedLanguage, selectedPosition)
    }
    
    static func convertDateFromDateString(dateString: String, dateFormat: String) -> Date {
        let dateFormatter = DateFormatter()
        dateFormatter.timeZone = NSTimeZone.default
        dateFormatter.dateFormat = dateFormat
        return dateFormatter.date(from: dateString)!
    }
    
    static func convertDateStringFromDate(date: Date, dateFormat: String) -> String {
        let dateFormatter = DateFormatter()
        dateFormatter.timeZone = NSTimeZone.default
        dateFormatter.dateFormat = dateFormat
        return dateFormatter.string(from: date)
    }
    
    static func convertJsonStringFromJsonObject(_ jsonObject: Any) -> String {
        if let jsonData = try? JSONSerialization.data(withJSONObject: jsonObject, options: .prettyPrinted) {
            let jsonString = String(data: jsonData, encoding: .utf8)!
            print("jsonString : \(jsonString)")
            return jsonString
        }
        return NULL_STRING
    }
    
    static func convertJsonObjectFromJsonString(_ jsonString: String) -> Any {
        if jsonString.count > 0 {
            let jsonData = jsonString.data(using: .utf8)!
            if let jsonObject = try? JSONSerialization.jsonObject(with: jsonData, options: .mutableContainers) {
                print("jsonObject : \(jsonObject)")
                return jsonObject
            }
        }
        return (Any).self
    }
}
