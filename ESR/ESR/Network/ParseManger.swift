//
//  ParseManger.swift
//  ESR
//
//  Created by Pratik Patel on 20/08/18.
//

import Foundation

class ParseManager {

    static func parseLogin(_ rootDic: NSDictionary) {
        let userData = UserData()
        
        if let userDataDic = rootDic.value(forKey: "userData") as? NSObject {
            if let email = userDataDic.value(forKey: "email") as? String {
                userData.email = email
            }
            if let firstName = userDataDic.value(forKey: "first_name") as? String {
                userData.firstName = firstName
            }
            if let surName = userDataDic.value(forKey: "sur_name") as? String {
                userData.surName = surName
            }
            if let id = userDataDic.value(forKey: "id") {
                if id is Int {
                    userData.id = id as! Int
                }
            }
            
            if let settingsDic = userDataDic.value(forKey: "settings") as? NSObject {
                if let value = settingsDic.value(forKey: "value") as? String {
                    let valueDic = Utils.convertToDictionary(value)! as NSDictionary
                    
                    if let isNotification = valueDic.value(forKey: "is_notification") as? String {
                        userData.isNotification = Bool(isNotification)!
                    }
                    
                    if let isVibration = valueDic.value(forKey: "is_vibration") as? String {
                        userData.isVibration = Bool(isVibration)!
                    }
                    
                    if let isSound = valueDic.value(forKey: "is_sound") as? String {
                        userData.isSound = Bool(isSound)!
                    }
                }
            }
        }
        
        ApplicationData.sharedInstance().saveUserData(userData)
    }
}
