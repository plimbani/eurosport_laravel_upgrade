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
    
    static func sharedInstance() -> ApplicationData {
        if (applicationData == nil) {
            applicationData = ApplicationData()
        }
        return applicationData
    }
    
    override init() {
        
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
