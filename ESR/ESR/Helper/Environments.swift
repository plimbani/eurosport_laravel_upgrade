//
//  Environments.swift
//  ESR
//
//  Created by Pratik Patel on 17/08/18.
//

import Foundation

public enum PlistKey {
    case BaseURL
    case GoogleMapKey
    
    func value() -> String {
        switch self {
        case .BaseURL:
            return "base_url"
        case .GoogleMapKey:
            return "google_map_key"
        }
    }
}

public struct Environment {
    
    fileprivate var infoDict: [String: Any]  {
        get {
            if let dict = Bundle.main.infoDictionary {
                return dict
            }else {
                fatalError("Plist file not found")
            }
        }
    }
    public func configuration(_ key: PlistKey) -> String {
        switch key {
        case .BaseURL:
            return infoDict[PlistKey.BaseURL.value()] as! String
        case .GoogleMapKey:
            return infoDict[PlistKey.GoogleMapKey.value()] as! String
        }
    }
}

struct API_URL {
    static var BASE_URL = Environment().configuration(PlistKey.BaseURL)
}
