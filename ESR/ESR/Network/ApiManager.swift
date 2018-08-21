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
    
    func getHeaders(_ auth:Bool=false) -> HTTPHeaders {
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
                    print("JSON: \(json)")
                    let result = json as! NSDictionary
                    success(result)
                }
            case .failure(let error):
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
                if let json = response.result.value {
                    print("JSON: \(json)")
                    let result = json as! NSDictionary
                    success(result)
                }
            case .failure(let error):
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
    
    func getAppVersion(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.APP_VERSION, parameters, success: success, failure: failure, true)
    }
    
    func getUserDetails(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.CHECK_USER, parameters, success: success, failure: failure, true)
    }
    
    func forgotPassword(_ parameters: [String: Any]?, success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        postRequest(API_ENDPOINT.FORGOT_PASSWORD, parameters, success: success, failure: failure)
    }

    // MARK:- Tournaments list
    func getTournaments(success: @escaping (_ result: NSDictionary) -> (), failure: @escaping (_ result: NSDictionary) -> ()) {
        getRequest(API_ENDPOINT.TOURNAMENTS, success: success, failure: failure)
    }
}
