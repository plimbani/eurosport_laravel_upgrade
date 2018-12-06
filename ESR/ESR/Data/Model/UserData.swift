//
//  User.swift
//  Marketti
//
//  Created by My on 29/03/18.
//  Copyright © 2018 Aecor. All rights reserved.
//

import UIKit

class UserData: NSObject, NSCoding {
    
    var id:Int = NULL_ID
    var firstName:String = NULL_STRING
    var surName:String = NULL_STRING
    var email:String = NULL_STRING
    var tournamentId:Int = NULL_ID
    var locale:String = NULL_STRING
    var imageURL:String = NULL_STRING
    
    override init() {}
    
    required init(coder aDecoder: NSCoder) {
        self.id = aDecoder.decodeInteger(forKey: "id")
        self.tournamentId = aDecoder.decodeInteger(forKey: "tournamentId")
        
        if let firstName = aDecoder.decodeObject(forKey: "firstName") as? String {
            self.firstName = firstName
        }
        
        if let surName = aDecoder.decodeObject(forKey: "surName") as? String {
            self.surName = surName
        }
        
        if let email = aDecoder.decodeObject(forKey: "email") as? String {
            self.email = email
        }
        
        if let locale = aDecoder.decodeObject(forKey: "locale") as? String {
            self.locale = locale
        }
        
        if let imageURL = aDecoder.decodeObject(forKey: "imageURL") as? String {
            self.imageURL = imageURL
        }
    }
    
    func encode(with aCoder: NSCoder) {
        aCoder.encode(id, forKey: "id")
        aCoder.encode(tournamentId, forKey: "tournamentId")
        aCoder.encode(firstName, forKey: "firstName")
        aCoder.encode(surName, forKey: "surName")
        aCoder.encode(email, forKey: "email")
        aCoder.encode(locale, forKey: "locale")
        aCoder.encode(imageURL, forKey: "imageURL")
    }
}
