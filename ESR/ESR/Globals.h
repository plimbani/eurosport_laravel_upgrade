//
//  Globals.h
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#ifndef Globals_h
#define Globals_h

//#define BaseURL @"http://kamal-eurosport.dev.aecortech.com"
#define BaseURL @"http://esr.aecordigitalqa.com"
//#define BaseURL @"http://192.168.0.6:8180/"

#define Registration @"/api/user/create"
#define Login @"/api/auth/login"
#define CheckApi @"/api/auth/check"
#define Tournaments @"/api/tournaments"
#define TournamentSummary @"/api/tournaments/tournamentSummary"
#define Forgotpassword @"/api/password/email"
#define Resetpassword @"/api/password/reset"
#define UpdateProfile @"/api/user/update/"
#define MakeTournamentFavourite @"/api/users/setFavourite"
#define RemoveTournamentFavourite @"/api/users/removeFavourite"
#define SetTournamentDefault @"/api/users/setDefaultFavourite"
#define GetTournamentDefault @"/api/users/getLoginUserDefaultTournament"
#define GetUserFavouriteTournamentList @"/api/users/getLoginUserFavouriteTournament"
#define GetTournamentAge @"/api/age_group/getCompetationFormat"
#define GetTournamentGroups @"/api/match/getDraws"

#define GetTournamentClub @"/api/tournaments/getTournamentClub"
#define GetClubTeam @"/api/teams/getTeamsList"
#define GetAgeTeam @"/api/teams/getTeamsList"
#define GetGroupTeam @"/api/teams/getTeamsList"

#define  GetDrawTable @"/api/match/getDrawTable"
#define GetStanding @"/api/match/getStanding"
#define GetMatchFixtures @"/api/match/getFixtures"
#define GetMatchFixturesTeamID @"/api/match/getFixtures"
#define GetMatchFixturesClubID @"/api/match/getFixtures"
#define GetAgeGroup @"/api/match/getDraws"

#define UpdateProfileImage @"/api/users/updateProfileImage"
#define GetSetting @"/api/users/getSetting"
#define PostSetting @"/api/users/postSetting"
#endif /* Globals_h */
