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
    
    static func sharedInstance() -> ApplicationData {
        if (applicationData == nil) {
            applicationData = ApplicationData()
        }
        return applicationData
    }
    
    override init() {
        API_URL.BASE_URL = Environment().configuration(PlistKey.BaseURL)
        
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
