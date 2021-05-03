//
//  ApiManager.swift
//  Marketti
//
//  Created by My on 07/03/18.
//  Copyright © 2018 My. All rights reserved.
//

import Alamofire

class ApiManager {
  
    init() {
        // configuration
        let configuration = URLSessionConfiguration.default
        configuration.timeoutIntervalForRequest = 60
        configuration.timeoutIntervalForResource = 80
        _ = Alamofire.SessionManager(configuration: configuration)
    }
    
    // MARK:- Common methods
    
    func getHeaders(_ auth: Bool=false) -> HTTPHeaders {
        var headers = [
            "Content-Type": "application/json",
            "Accept": "application/json",
            "IsMobileUser" : "true"
        ]
        
        if let locale = USERDEFAULTS.string(forKey: kUserDefaults.locale) {
            headers["locale"] = "\(locale))"
        }
        
        if auth {
            if let token = USERDEFAULTS.string(forKey: kUserDefaults.token) {
                headers["Authorization"] = "Bearer \(token))"
            }
        }
        return headers as HTTPHeaders
    }
    
    func getErrorResult(_ data: Data) -> NSDictionary {
        let result: NSDictionary = [:]
        
        if let json = String(data: data, encoding: String.Encoding.utf8) {
            print("Error \(json)")
            let result = Utils.convertToDictionary(json)
            if result != nil {
                return result! as NSDictionary
            }
        }
        
        return result
    }
    
    func getRequest(_ apiURL: String,success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> (), _ isTokenNeeded: Bool = false) {
        Alamofire.request(apiURL, method: .get, parameters: nil,encoding: URLEncoding.queryString, headers: getHeaders(isTokenNeeded)).validate(statusCode: 200..<300).responseJSON {
            response in
            
//            if let response = response.response {
//                if response.statusCode == ResponseCode.unauthorised.rawValue {
//
//                    failure(NSDictionary())
//                    return
//                }
//            }
            
            switch response.result {
            case .success:
                if let json = response.result.value {
                    TestFairy.log("Success - \(apiURL)")
                    print("JSON: \(json)")
                    let result = json as! NSDictionary
                    success(result)
                }
            case .failure(let error):
                TestFairy.log("Failure - \(apiURL)")
                print(error.localizedDescription)
                if let data = response.data {
                    failure(self.getErrorResult(data))
                }
            }
        }
    }
    
    func postRequest(_ apiURL: String, _ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> (), _ isTokenNeeded: Bool = false) {
        
        Alamofire.request(apiURL, method: .post, parameters: parameters,encoding: JSONEncoding.default, headers: getHeaders(isTokenNeeded)).validate(statusCode: 200..<300).responseJSON {
            response in
            switch response.result {
            case .success:
                TestFairy.log("Success - \(apiURL)")
                if let json = response.result.value {
                    print("JSON: \(json)")
                    let result = json as! NSDictionary
                    success(result)
                }
            case .failure(let error):
                TestFairy.log("Failure - \(apiURL)")
                print(error.localizedDescription)
                if let data = response.data {
                    failure(self.getErrorResult(data))
                }
            }
        }
    }
  
    // MARK:- Login
    func login(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.LOGIN, parameters, success: success, failure: failure)
    }
    
    func socialMediaLogin(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.SOCIAL_LOGIN, parameters, success: success, failure: failure)
    }
    
    func register(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.REGISTER, parameters, success: success, failure: failure)
    }
    
    func getAppVersion(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.APP_VERSION, parameters, success: success, failure: failure, true)
    }
    
    func getUserDetails(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.CHECK_USER, parameters, success: success, failure: failure, true)
    }
    
    func forgotPassword(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.FORGOT_PASSWORD, parameters, success: success, failure: failure)
    }
    
    func updateProfile(userId: String, _ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(String.init(format: API_ENDPOINT.UPDATE_PROFILE, userId), parameters, success: success, failure: failure, true)
    }
    
    func updateUserSettings(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.UPDATE_SETTINGS, parameters, success: success, failure: failure, true)
    }
    
    func getUserSettings(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.GET_SETTINGS, parameters, success: success, failure: failure, true)
    }

    // MARK:- Tournaments list
    func getTournaments(success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        getRequest(API_ENDPOINT.TOURNAMENTS, success: success, failure: failure)
    }
    
    func getTournamentTeamDetails(_ parameters: [String: Any]?,success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.GET_TOURNAMENT_TEAM_DETAILS, parameters, success: success, failure: failure, true)
    }
    
    func getCountriesList(success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        getRequest(API_ENDPOINT.COUNTRIES_LIST, success: success, failure: failure)
    }
    
    func getFavTournaments(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.GET_FAVOURITE, parameters, success: success, failure: failure, true)
    }
    
    func setFavTournament(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.SET_FAVOURITE, parameters, success: success, failure: failure, true)
    }
    
    func setDefaultFavTournament(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.SET_DEFAULT_FAVOURITE, parameters, success: success, failure: failure, true)
    }
    
    func updateFCMTokem(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.UPDATE_FCM, parameters, success: success, failure: failure, true)
    }
    
    func removeFavTournament(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.REMOVE_FAVOURITE, parameters, success: success, failure: failure, true)
    }
    
    func getAgeCategories(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.AGE_CATEGORIES, parameters, success: success, failure: failure, true)
    }
    
    func getFinalPlacings(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.GET_FINAL_PLACING_MATCHES, parameters, success: success, failure: failure, true)
    }
    
    func getAgeCategoriesGroups(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.TOURNAMENT_GROUP, parameters, success: success, failure: failure, true)
    }
    
    func getTournamentClub(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.TOURNAMENT_CLUBS, parameters, success: success, failure: failure, true)
    }
    
    func getTeamList(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.GET_TEAM_LIST, parameters, success: success, failure: failure, true)
    }
    
    func getGroupStandings(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.GROUP_STANDING, parameters, success: success, failure: failure, true)
    }
    
    func getMatchFixtures(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.TEAM_FIXTURES, parameters, success: success, failure: failure, true)
    }
    
    func resendEmail(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.RESEND_EMAIL, parameters, success: success, failure: failure, true)
    }
    
    func getViewGraphicImage(_ parameters: [String: Any]?, success: @escaping (_ result: Any) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        // postRequest(API_ENDPOINT.VIEW_GRAPHIC, parameters, success: success, failure: failure, true)
        
        Alamofire.request(API_ENDPOINT.VIEW_GRAPHIC, method: .post, parameters: parameters,encoding: JSONEncoding.default, headers: getHeaders(true)).validate(statusCode: 200..<300).responseString { response in
             switch(response.result) {
                case .success(_):
                    if let data = response.result.value{
                        success(data)
                    }
                case .failure(_):
                    if let data = response.data {
                        failure(self.getErrorResult(data))
                    }
                    break
                }
        }
    }
    
    func updateAppVersion(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.UPDATE_APP_VERSION, parameters, success: success, failure: failure, true)
    }
    
    // MARK: Easy match manager APIS
    
    func accessCode(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.ACCESS_CODE, parameters, success: success, failure: failure, true)
    }
    
    
}
