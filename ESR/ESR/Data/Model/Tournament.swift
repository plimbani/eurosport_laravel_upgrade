//
//  Tournament.swift
//  ESR
//
//  Created by Pratik Patel on 24/08/18.
//

import Foundation


class Tournament: NSObject, NSCoding {
    
    var id: Int = NULL_ID
    var name: String = NULL_STRING
    var logo: String = NULL_STRING
    var startDate: String = NULL_STRING
    var endDate: String = NULL_STRING
    var telephone: String = NULL_STRING
    var tournamentStartTime: String = NULL_STRING
    var firstName: String = NULL_STRING
    var lastName: String = NULL_STRING
    var competitionType: String = NULL_STRING
    var startDateObj = Date()
    var endDateObj = Date()
    var isFavourite: Bool = false
    var isDefault: Int = 0 // 0 - no, 1 -  yes
    
    var slug: String = NULL_STRING
    var maximumTeams: Int = NULL_ID
    var website: String = NULL_STRING
    var facebook: String = NULL_STRING
    var twitter: String = NULL_STRING
    var status: String = NULL_STRING
    var noOfPitches: String = NULL_STRING
    var noOfMatchPerDayPitch: String = NULL_STRING
    var pointsPerMatchWin: String = NULL_STRING
    var pointsPerMatchTie: String = NULL_STRING
    var pointsPerBye: String = NULL_STRING
    var tournamentLogo: String = NULL_STRING
    
    var accessCode: String = NULL_STRING
    
    override init() {}
    
    required init(coder aDecoder: NSCoder) {
        self.id = aDecoder.decodeInteger(forKey: "id")
        
        if let name = aDecoder.decodeObject(forKey: "name") as? String {
            self.name = name
        }
        
        if let status = aDecoder.decodeObject(forKey: "status") as? String {
            self.status = status
        }
        
        if let telephone = aDecoder.decodeObject(forKey: "telephone") as? String {
            self.telephone = telephone
        }
        
        if let firstName = aDecoder.decodeObject(forKey: "firstName") as? String {
            self.firstName = firstName
        }
        
        if let lastName = aDecoder.decodeObject(forKey: "lastName") as? String {
            self.lastName = lastName
        }
        
        if let startDate = aDecoder.decodeObject(forKey: "startDate") as? String {
            self.startDate = startDate
        }
        
        if let endDate = aDecoder.decodeObject(forKey: "endDate") as? String {
            self.endDate = endDate
        }
        
        if let startDateObj = aDecoder.decodeObject(forKey: "startDateObj") as? Date {
            self.startDateObj = startDateObj
        }
        
        if let endDateObj = aDecoder.decodeObject(forKey: "endDateObj") as? Date {
            self.endDateObj = endDateObj
        }
        
        if let logo = aDecoder.decodeObject(forKey: "logo") as? String {
            self.logo = logo
        }
        
        if let competitionType = aDecoder.decodeObject(forKey: "competitionType") as? String {
            self.competitionType = competitionType
        }
        
        if let tournamentStartTime = aDecoder.decodeObject(forKey: "tournamentStartTime") as? String {
            self.tournamentStartTime = tournamentStartTime
        }
        
        if let accessCodeValue = aDecoder.decodeObject(forKey: "access_code") as? String {
            self.accessCode = accessCodeValue
        }
    }
    
    func encode(with aCoder: NSCoder) {
        aCoder.encode(id, forKey: "id")
        aCoder.encode(status, forKey: "status")
        aCoder.encode(telephone, forKey: "telephone")
        aCoder.encode(name, forKey: "name")
        aCoder.encode(firstName, forKey: "firstName")
        aCoder.encode(lastName, forKey: "lastName")
        aCoder.encode(startDate, forKey: "startDate")
        aCoder.encode(endDate, forKey: "endDate")
        aCoder.encode(startDateObj, forKey: "startDateObj")
        aCoder.encode(endDateObj, forKey: "endDateObj")
        aCoder.encode(logo, forKey: "logo")
        aCoder.encode(competitionType, forKey: "competitionType")
        aCoder.encode(tournamentStartTime, forKey: "tournamentStartTime")
        aCoder.encode(accessCode, forKey: "access_code")
    }
}
