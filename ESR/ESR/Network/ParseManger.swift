//
//  ParseManger.swift
//  ESR
//
//  Created by Pratik Patel on 20/08/18.
//

import Foundation

class ParseManager {
    
    static func parseTournament(_ record: NSDictionary) -> Tournament {
        let tournamentObj = Tournament()
        
        if let id = record.value(forKey: "id") as? Int {
            tournamentObj.id = id
        }
        
        if let text = record.value(forKey: "name") as? String {
            tournamentObj.name = text
        }

        let formatter = DateFormatter()
        formatter.dateFormat = kDateFormat.format1
        
        if let text = record.value(forKey: "start_date") as? String {
            tournamentObj.startDate = text
            tournamentObj.startDateObj = formatter.date(from: text)!
        }

        if let text = record.value(forKey: "end_date") as? String {
            tournamentObj.endDate = text
        }
        
        if let isDefault = record.value(forKey: "is_default") as? Int {
            tournamentObj.isDefault = isDefault
        }
        
        return tournamentObj
    }

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
            if let locale = userDataDic.value(forKey: "locale") as? String {
                userData.locale = locale
            }
            if let tournamentId = userDataDic.value(forKey: "tournament_id") as? Int {
                userData.tournamentId = tournamentId
            }
            if let id = userDataDic.value(forKey: "user_id") {
                if id is Int {
                    userData.id = id as! Int
                }
            }
            
            if let settingsDic = userDataDic.value(forKey: "settings") as? NSObject {
                if let value = settingsDic.value(forKey: "value") as? String {
                    let valueDic = Utils.convertToDictionary(value)! as NSDictionary
                    
                    if let isNotification = valueDic.value(forKey: "is_notification") as? Bool {
                        USERDEFAULTS.set(isNotification, forKey: kUserDefaults.isNotification)
                    }
                    
                    if let isVibration = valueDic.value(forKey: "is_vibration") as? Bool {
                        USERDEFAULTS.set(isVibration, forKey: kUserDefaults.isVibration)
                    }
                    
                    if let isSound = valueDic.value(forKey: "is_sound") as? Bool {
                        USERDEFAULTS.set(isSound, forKey: kUserDefaults.isSound)
                    }
                }
            }
        }
        
        ApplicationData.sharedInstance().saveUserData(userData)
    }
}
