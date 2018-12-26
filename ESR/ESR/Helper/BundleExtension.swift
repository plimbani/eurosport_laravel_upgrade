//
//  BundleExtension.swift
//  ESR
//
//  Created by Pratik Patel on 21/12/18.
//

import UIKit

private var bundleKey: UInt8 = 0

final class BundleExtension: Bundle {
    
    override func localizedString(forKey key: String, value: String?, table tableName: String?) -> String {
        return (objc_getAssociatedObject(self, &bundleKey) as? Bundle)?.localizedString(forKey: key, value: value, table: tableName) ?? super.localizedString(forKey: key, value: value, table: tableName)
    }
}

extension Bundle {
    
    static let once: Void = { object_setClass(Bundle.main, type(of: BundleExtension())) }()
    
    static func set(languageCode: String) {
        Bundle.once
        
        let isLanguageRTL = Locale.characterDirection(forLanguage: languageCode) == .rightToLeft
        UIView.appearance().semanticContentAttribute = isLanguageRTL == true ? .forceRightToLeft : .forceLeftToRight
        
        UserDefaults.standard.set(isLanguageRTL,   forKey: "AppleTe  zxtDirection")
        UserDefaults.standard.set(isLanguageRTL,   forKey: "NSForceRightToLeftWritingDirection")
        UserDefaults.standard.set([languageCode], forKey: "AppleLanguages")
        UserDefaults.standard.synchronize()
        
        guard let path = Bundle.main.path(forResource: languageCode, ofType: "lproj") else {
            print("Failed to get a bundle path.")
            return
        }
        
        objc_setAssociatedObject(Bundle.main, &bundleKey, Bundle(path: path), objc_AssociationPolicy.OBJC_ASSOCIATION_RETAIN_NONATOMIC);
    }
}
