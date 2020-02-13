//
//  ParseManger.swift
//  ESR
//
//  Created by Pratik Patel on 20/08/18.
//

import Foundation

class ParseManager {
    
    static func parseGroupStandings(_ record: NSDictionary) -> GroupStanding {
        let groupStanding = GroupStanding()
        
        if let id = record.value(forKey: "id") as? Int {
            groupStanding.id = id
        }
        
        if let id = record.value(forKey: "team_id") as? Int {
            groupStanding.teamId = id
        }
        
        if let tournamentId = record.value(forKey: "tournament_id") as? Int {
            groupStanding.tournamentId = tournamentId
        }
        
        if let played = record.value(forKey: "played") as? Int {
            groupStanding.played = played
        }
        
        if let won = record.value(forKey: "won") as? Int {
            groupStanding.won = won
        }
        
        if let lost = record.value(forKey: "lost") as? Int {
            groupStanding.lost = lost
        }
        
        if let draws = record.value(forKey: "draws") as? Int {
            groupStanding.draws = draws
        }
        
        if let points = record.value(forKey: "points") as? Int {
            groupStanding.points = points
        }
        
        if let goalFor = record.value(forKey: "goal_for") as? Int {
            groupStanding.goalFor = goalFor
        }
        
        if let goalAgainst = record.value(forKey: "goal_against") as? Int {
            groupStanding.goalAgainst = goalAgainst
        }
        
        if let teamFlag = record.value(forKey: "teamFlag") as? String {
            groupStanding.teamFlag = teamFlag
        }
        
        if let name = record.value(forKey: "name") as? String {
            groupStanding.name = name
        }
        
        return groupStanding
    }
    
    static func parseTeamFixture(_ record: NSDictionary) -> TeamFixture {
        let teamFixture = TeamFixture()
        
        if let text = record.value(forKey: "match_number") as? String {
            teamFixture.matchNumber = text
        }
        
        if let text = record.value(forKey: "group_name") as? String {
            teamFixture.groupName = text
        }
        
        if let text = record.value(forKey: "displayMatchNumber") as? String {
            teamFixture.displayMatchNumber = text
        }
        
        if let text = record.value(forKey: "round") as? String {
            teamFixture.round = text
        }
        
        if let text = record.value(forKey: "pitchType") as? String {
            teamFixture.pitchType = text
        }
        
        if let text = record.value(forKey: "actual_round") as? String {
            teamFixture.actualRound = text
        }
        
        if let text = record.value(forKey: "competation_name") as? String {
            teamFixture.competationName = text
        }
        
        if let text = record.value(forKey: "divisionName") as? String {
            teamFixture.divisionName = text
        }
        
        if let text = record.value(forKey: "isKnockoutPlacingMatches") as? Bool {
            teamFixture.isKnockoutPlacingMatches = text
        }
        
        if let text = record.value(forKey: "competation_round_no") as? String {
            teamFixture.competationRoundNo = text
        }
        
        //if let isScheduled = record.value(forKey: "is_scheduled") as? Int {
           // if isScheduled == 1 {
                if let text = record.value(forKey: "match_datetime") as? String {
                    teamFixture.matchDatetime = text
                    
                    if let date = ApplicationData.getFormattedDate(text, dateFormat: kDateFormat.format3) {
                        teamFixture.matchDatetimeObj = date
                    }
                }
           // }
        //}
        
        if let teamSize = record.value(forKey: "team_size") as? Int {
            teamFixture.teamSize = teamSize
        }
        
        if let text = record.value(forKey: "match_endtime") as? String {
            teamFixture.matchEndtime = text
        }
        
        if let text = record.value(forKey: "position") as? String {
            teamFixture.position = text
        }
        
        if let text = record.value(forKey: "match_winner") as? String {
            teamFixture.matchWinner = text
        }
        
        if let text = record.value(forKey: "match_status") as? String {
            teamFixture.matchStatus = text
        }
        
        if let competitionActualName = record.value(forKey: "competition_actual_name") as? String {
            teamFixture.competitionActualName = competitionActualName
        }
        
        if let ageGroupId = record.value(forKey: "age_group_id") as? Int {
            teamFixture.ageGroupId = ageGroupId
        }
        
        if let text = record.value(forKey: "displayHomeTeamPlaceholderName") as? String {
            teamFixture.displayHomeTeamPlaceholderName = text
        }
        
        if let text = record.value(forKey: "displayAwayTeamPlaceholderName") as? String {
            teamFixture.displayAwayTeamPlaceholderName = text
        }
        
        if let competitionId = record.value(forKey: "competitionId") as? Int {
            teamFixture.competitionId = competitionId
        }
        
        if let text = record.value(forKey: "venueCoordinates") as? String {
            teamFixture.venueCoordinates = text
        }
        
        if let text = record.value(forKey: "venue_name") as? String {
            teamFixture.venueName = text
        }
        
        if let text = record.value(forKey: "venueaddress") as? String {
            teamFixture.venueAddress = text
        }
        
        if let text = record.value(forKey: "venueCity") as? String {
            teamFixture.venueCity = text
        }
        
        if let text = record.value(forKey: "venueCountry") as? String {
            teamFixture.venueCountry = text
        }
        
        if let text = record.value(forKey: "venuePostcode") as? String {
            teamFixture.venuePostcode = text
        }
        
        if let text = record.value(forKey: "venueState") as? String {
            teamFixture.venueState = text
        }
        
        if let text = record.value(forKey: "pitch_number") as? String {
            teamFixture.pitchNumber = text
        }
        
        if let homeId = record.value(forKey: "Home_id") as? Int {
            teamFixture.homeId = homeId
        }
        
        if let awayId = record.value(forKey: "Away_id") as? Int {
            teamFixture.awayId = awayId
        }
        
        if let text = record.value(forKey: "HomeTeam") as? String {
            teamFixture.homeTeam = text
        }
        
        if let text = record.value(forKey: "AwayTeam") as? String {
            teamFixture.awayTeam = text
        }
        
        if let text = record.value(forKey: "first_name") as? String {
            teamFixture.firstName = text
        }
        if let text = record.value(forKey: "last_name") as? String {
            teamFixture.lastName = text
        }
        
        if let text = record.value(forKey: "HomeFlagLogo") as? String {
            teamFixture.homeFlagLogo = text
        }
        
        if let text = record.value(forKey: "AwayFlagLogo") as? String {
            teamFixture.awayFlagLogo = text
        }
        
        if let text = record.value(forKey: "HomeCountryName") as? String {
            teamFixture.homeCountryName = text
        }
        
        if let text = record.value(forKey: "AwayCountryName") as? String {
            teamFixture.awayCountryName = text
        }
        
        if let homeScore = record.value(forKey: "homeScore") as? Int {
            teamFixture.homeScore = homeScore
        }
        
        if let awayScore = record.value(forKey: "AwayScore") as? Int {
            teamFixture.awayScore = awayScore
        }
        
        if let text = record.value(forKey: "homeTeamName") as? String {
            teamFixture.homeTeamName = text
        }
        
        if let text = record.value(forKey: "homePlaceholder") as? String {
            teamFixture.homePlaceholder = text
        }
        
        if let text = record.value(forKey: "awayPlaceholder") as? String {
            teamFixture.awayPlaceholder = text
        }
        
        if let isResultOverride = record.value(forKey: "isResultOverride") as? Int {
            teamFixture.isResultOverride = isResultOverride
        }
        
        if let text = record.value(forKey: "awayTeamName") as? String {
            teamFixture.awayTeamName = text
        }
        
        if let pitchId = record.value(forKey: "pitchId") as? Int {
            teamFixture.pitchId = pitchId
        }
        
        if let text = record.value(forKey: "HomeTeamShirtColor") as? String {
            teamFixture.homeTeamShirtColor = text
        }
        
        if let text = record.value(forKey: "AwayTeamShirtColor") as? String {
            teamFixture.awayTeamShirtColor = text
        }
        
        if let text = record.value(forKey: "HomeTeamShortsColor") as? String {
            teamFixture.homeTeamShortsColor = text
        }
        
        if let text = record.value(forKey: "AwayTeamShortsColor") as? String {
            teamFixture.awayTeamShortsColor = text
        }
        
        return teamFixture
    }
    
    static func parseFavTournament(_ record: NSDictionary) -> Tournament {
        let tournamentObj = Tournament()
        
        if let id = record.value(forKey: "tournament_id") as? Int {
            tournamentObj.id = id
        }
        
        if let text = record.value(forKey: "name") as? String {
            tournamentObj.name = text
        }
        
        if let text = record.value(forKey: "start_date") as? String {
            tournamentObj.startDate = text
            
            if let formattedDate = ApplicationData.getFormattedDate(text, dateFormat: kDateFormat.format3) {
                tournamentObj.startDateObj = formattedDate
            }
        }
        
        if let text = record.value(forKey: "end_date") as? String {
            tournamentObj.endDate = text
            
            if let formattedDate = ApplicationData.getFormattedDate(text, dateFormat: kDateFormat.format3) {
                tournamentObj.endDateObj = formattedDate
            }
        }
        
        if let text = record.value(forKey: "TournamentStartTime") as? String {
            tournamentObj.tournamentStartTime = text
        }
        
        if let isDefault = record.value(forKey: "is_default") as? Int {
            tournamentObj.isDefault = isDefault
        }
        
        if let telephone = record.value(forKey: "telephone") as? String {
            tournamentObj.telephone = telephone
        }
        
        if let firstName = record.value(forKey: "first_name") as? String {
            tournamentObj.firstName = firstName
        }
        
        if let lastName = record.value(forKey: "last_name") as? String {
            tournamentObj.lastName = lastName
        }
        
        if let logo = record.value(forKey: "tournamentLogo") as? String {
            tournamentObj.logo = logo;
        }
        
        if let status = record.value(forKey: "status") as? String {
            tournamentObj.status = status;
        }
        
        return tournamentObj
    }
    
    static func parseFollowTournament(_ record: NSDictionary) -> Tournament {
        let tournamentObj = Tournament()
        
        if let id = record.value(forKey: "tournament_id") as? Int {
            tournamentObj.id = id
        }
        
        if let text = record.value(forKey: "name") as? String {
            tournamentObj.name = text
        }
        
        if let text = record.value(forKey: "tournamentLogo") as? String {
            tournamentObj.tournamentLogo = text
        }
        
        if let text = record.value(forKey: "access_code") as? String {
            tournamentObj.accessCode = text
        }
        
        return tournamentObj
    }
    
    static func parseTournament(_ record: NSDictionary) -> Tournament {
        let tournamentObj = Tournament()
        
        if let id = record.value(forKey: "id") as? Int {
            tournamentObj.id = id
        }
        
        if let text = record.value(forKey: "name") as? String {
            tournamentObj.name = text
        }
        
        if let text = record.value(forKey: "tournamentLogo") as? String {
            tournamentObj.tournamentLogo = text
        }

        if let text = record.value(forKey: "start_date") as? String {
            tournamentObj.startDate = text
            
            if let formattedDate = ApplicationData.getFormattedDate(text, dateFormat: kDateFormat.format1) {
                tournamentObj.startDateObj = formattedDate
            }
        }

        if let text = record.value(forKey: "end_date") as? String {
            tournamentObj.endDate = text
            
            if let formattedDate = ApplicationData.getFormattedDate(text, dateFormat: kDateFormat.format1) {
                tournamentObj.endDateObj = formattedDate
            }
        }
        
        return tournamentObj
    }

    static func parseLogin(_ rootDic: NSDictionary) {
        let userData = UserData()
        
        if let enableLogs = rootDic.value(forKey: "enable_logs_ios") as? Bool {
            userData.enableLogs = enableLogs
        }
        
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
            if let countryId = userDataDic.value(forKey: "country_id") as? Int {
                userData.countryId = countryId
            }
            if let role = userDataDic.value(forKey: "role") as? String {
                userData.role = role
            }
            if let id = userDataDic.value(forKey: "user_id") {
                if id is Int {
                    userData.id = id as! Int
                }
            }
            
            if let settingsDic = userDataDic.value(forKey: "settings") as? NSObject {
                if let value = settingsDic.value(forKey: "value") as? String {
                    let valueDic = Utils.convertToDictionary(value)! as NSDictionary
                    
                    if let isNotification = valueDic.value(forKey: "is_notification") as? String {
                        USERDEFAULTS.set(isNotification, forKey: kUserDefaults.isNotification)
                    }
                    
                   if let isSound = valueDic.value(forKey: "is_sound") as? String {
                        USERDEFAULTS.set(isSound, forKey: kUserDefaults.isSound)
                    }
                }
            }
        }
        
        ApplicationData.sharedInstance().saveUserData(userData)
    }
}
