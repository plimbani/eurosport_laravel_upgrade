//
//  AppConstantsList.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//
import UIKit
import Foundation

// MARK:- Default Value Constants
let NULL_STRING     =   ""
let NA_STRING       =   "NA"

// MARK:- Global Constants
let DEVICE          =   UIDevice.current
let DEVICE_WIDTH    =   UIScreen.main.bounds.size.width
let DEVICE_HEIGHT   =   UIScreen.main.bounds.size.height
let IPAD            =   UIUserInterfaceIdiom.pad
let IDIOM           =   DEVICE.userInterfaceIdiom
let SYSTEM_VERSION  =   DEVICE.systemVersion
let USERDEFAULTS    =   UserDefaults.standard
let APPDELEGATE     =   UIApplication.shared.delegate as! AppDelegate

struct kViewController {
    
    // Login
    static let ForgotPasswordVC                 = "ForgotPasswordVC"
    static let CreateAccountVC                  = "CreateAccountVC"
    static let LoginVC                          = "LoginVC"
    static let LandingVC                        = "LandingVC"
    // Tabs
    static let MainTabViewController            = "MainTabViewController"
    static let TabFavouritesVC                  = "TabFavouritesVC"
    static let TabTournamentVC                  = "TabTournamentVC"
    static let TabTeamsVC                       = "TabOffersVC"
    static let TabAgeCategoriesVC               = "TabAgeCategoriesVC"
    static let TabSettingsVC                    = "TabSettingsVC"
    
}

struct kDateFormat {
    static let format1                          = "dd/MM/yyyy"
    static let format2                          = "dd/MM/yyyy hh:mm:ss"
    static let format3                          = "yyyy-MM-dd HH:mm:ss"
    static let format4                          = "dd/MM/yyyy HH:mm:ss"
    static let format5                          = "dd/MM/yyyy hh:mm a"
}

struct kNiB {
    struct Cell {
        static let TextFieldCell                = "TextFieldCell"
        static let LabelSelectionCell           = "LabelSelectionCell"
        static let ButtonCell                   = "ButtonCell"
    }
    
    struct View {
        static let LoadingView                  = "CustomLoadingView"
    }
}

struct DeviceType {
    static let MAX_LENGTH           = max(DEVICE_WIDTH, DEVICE_HEIGHT)
    static let MIN_LENGTH           = min(DEVICE_WIDTH, DEVICE_HEIGHT)
    
    static let IS_IPHONE_4_OR_LESS  = UIDevice.current.userInterfaceIdiom == .phone && MAX_LENGTH < 568.0
    static let IS_IPHONE_5          = UIDevice.current.userInterfaceIdiom == .phone && MAX_LENGTH == 568.0
    static let IS_IPHONE_6          = UIDevice.current.userInterfaceIdiom == .phone && MAX_LENGTH == 667.0
    static let IS_IPHONE_6P         = UIDevice.current.userInterfaceIdiom == .phone && MAX_LENGTH == 736.0
    static let IS_IPAD              = UIDevice.current.userInterfaceIdiom == .pad && MAX_LENGTH   == 1024.0
    static let IS_IPAD_PRO          = UIDevice.current.userInterfaceIdiom == .pad && MAX_LENGTH   == 1366.0
}

struct Font {
    // Avenir next
    static let HELVETICA_REGULAR = "Helvetica"
    static let HELVETICA_BOLD = "Helvetica-Bold"
    
    struct Size {
        static let commonBtnSize = CGFloat(16)
        static let commonLblSize = CGFloat(16)
        static let commonTextFieldTxt = CGFloat(16)
        static let commonTextFieldPlaceholder = CGFloat(16)
    }
    
}

enum CellType: Int {
    case TextFieldCell              = 1
    case LabelSelectionCell         = 2
    case ButtonCell                 = 3
}

enum CustomKeyboardType: Int {
    case general = 1
    case number
    case email
    case URL
}

enum CustomKeyboardReturn: Int {
    case kReturn = 1
    case next
    case search
    case send
    case done
}

// MARK:- Extensions
// MARK:- Color Extension
extension UIColor {
    
    // Create a UIColor from RGB
    convenience init(R: Int, G: Int, B: Int, Alpha: CGFloat = 1.0) {
        self.init(
            red: CGFloat(R) / 255.0,
            green: CGFloat(G) / 255.0,
            blue: CGFloat(B) / 255.0,
            alpha: Alpha
        )
    }
    
    // Create a UIColor from a hex value (E.g 0x000000)
    convenience init(hex: Int, Alpha: CGFloat = 1.0) {
        self.init(
            R: (hex >> 16) & 0xFF,
            G: (hex >> 8) & 0xFF,
            B: hex & 0xFF,
            Alpha: Alpha
        )
    }
    
    convenience init(hexString: String) {
        let hex = hexString.trimmingCharacters(in: CharacterSet.alphanumerics.inverted)
        var int = UInt32()
        Scanner(string: hex).scanHexInt32(&int)
        let a, r, g, b: UInt32
        switch hex.count {
        case 3: // RGB (12-bit)
            (a, r, g, b) = (255, (int >> 8) * 17, (int >> 4 & 0xF) * 17, (int & 0xF) * 17)
        case 6: // RGB (24-bit)
            (a, r, g, b) = (255, int >> 16, int >> 8 & 0xFF, int & 0xFF)
        case 8: // ARGB (32-bit)
            (a, r, g, b) = (int >> 24, int >> 16 & 0xFF, int >> 8 & 0xFF, int & 0xFF)
        default:
            (a, r, g, b) = (1, 1, 1, 0)
        }
        self.init(red: CGFloat(r) / 255, green: CGFloat(g) / 255, blue: CGFloat(b) / 255, alpha: CGFloat(a) / 255)
    }
    
    static func AppColor() -> UIColor {
        return UIColor(R: 199, G: 10, B: 32)
    }
    
    static let btnDisable = UIColor(R: 204, G: 204, B: 204)
    static let btnYellow = UIColor(R: 237, G: 158, B: 45)
    static let txtPlaceholderBorder = UIColor(R: 223, G: 223, B: 223)
    static let txtPlaceholderTxt = UIColor(R: 117, G: 117, B: 117)
    static let txtPlaceholderDisable = UIColor(R: 246, G: 246, B: 246)
    static let txtDefaultTxt = UIColor(R: 52, G: 52, B: 52)
}

extension String {
    public static func localize(key: String, comment: String = "") -> String {
        return NSLocalizedString(key, comment: comment)
    }
}
