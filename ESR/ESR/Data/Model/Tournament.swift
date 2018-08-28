//
//  Tournament.swift
//  ESR
//
//  Created by Pratik Patel on 24/08/18.
//

import Foundation


class Tournament {
    
    var id: Int = NULL_ID
    var name: String = NULL_STRING
    var slug: String = NULL_STRING
    var maximumTeams: Int = NULL_ID
    var website: String = NULL_STRING
    var facebook: String = NULL_STRING
    var twitter: String = NULL_STRING
    var status: String = NULL_STRING
    var competitioType: String = NULL_STRING
    var logo: String = NULL_STRING
    var startDate: String = NULL_STRING
    var endDate: String = NULL_STRING
    var noOfPitches: String = NULL_STRING
    var noOfMatchPerDayPitch: String = NULL_STRING
    var pointsPerMatchWin: String = NULL_STRING
    var pointsPerMatchTie: String = NULL_STRING
    var pointsPerBye: String = NULL_STRING
    var tournamentLogo: String = NULL_STRING
    
    var startDateObj = Date()
    
    var isFavourite: Bool = false
    var isDefault: Int = 0 // 0 - no, 1 -  yes
}
