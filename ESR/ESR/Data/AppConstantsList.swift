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
let NULL_ID         =   -1

// MARK:- Global Constants
let DEVICE          =   UIDevice.current
let DEVICE_WIDTH    =   UIScreen.main.bounds.size.width
let DEVICE_HEIGHT   =   UIScreen.main.bounds.size.height
let IPAD            =   UIUserInterfaceIdiom.pad
let IDIOM           =   DEVICE.userInterfaceIdiom
let SYSTEM_VERSION  =   DEVICE.systemVersion
let USERDEFAULTS    =   UserDefaults.standard
let APPDELEGATE     =   UIApplication.shared.delegate as! AppDelegate

struct API_ENDPOINT {
    
    static let TOURNAMENTS = API_URL.BASE_URL + "tournaments"
    static let APP_VERSION = API_URL.BASE_URL + "appversion"
    static let LOGIN = API_URL.BASE_URL + "auth/login"
    static let REGISTER = API_URL.BASE_URL + "user/create"
    static let FORGOT_PASSWORD = API_URL.BASE_URL + "password/email"
    static let CHECK_USER = API_URL.BASE_URL + "auth/check"
    static let GET_SETTINGS = API_URL.BASE_URL + "users/getSetting"
    static let UPDATE_SETTINGS = API_URL.BASE_URL + "users/postSetting"
    static let UPDATE_PROFILE = API_URL.BASE_URL + "user/update/%@"
    // Tab fav
    static let REMOVE_FAVOURITE = API_URL.BASE_URL + "users/removeFavourite"
    static let SET_FAVOURITE = API_URL.BASE_URL + "users/setFavourite"
    static let SET_DEFAULT_FAVOURITE = API_URL.BASE_URL + "users/setDefaultFavourite"
    static let GET_FAVOURITE = API_URL.BASE_URL + "users/getLoginUserFavouriteTournament"
    
    static let AGE_CATEGORIES = API_URL.BASE_URL + "age_group/getCompetationFormat"
    static let TOURNAMENT_GROUP = API_URL.BASE_URL + "match/getDraws"
    static let TEAM_FIXTURES = API_URL.BASE_URL + "match/getFixtures"
    static let GROUP_STANDING = API_URL.BASE_URL + "match/getStanding/yes"
}

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
    static let TabTeamsVC                       = "TabTeamsVC"
    static let TabAgeCategoriesVC               = "TabAgeCategoriesVC"
    static let TabSettingsVC                    = "TabSettingsVC"
    // Settings
    static let ProfileVC                        = "ProfileVC"
    static let NotificationAndSoundVC           = "NotificationAndSoundVC"
    static let PrivacyAndTermsVC                = "PrivacyAndTermsVC"
    static let AgeCategoriesGroupsVC            = "AgeCategoriesGroupsVC"
    static let AgeCategoriesGroupsSummaryVC     = "AgeCategoriesGroupsSummaryVC"
}

struct kUserDefaults {
    static let token                = "token"
    static let email                = "email"
    static let password             = "password"
    static let locale               = "locale"
    static let userData             = "userData"
    static let isNotification       = "isNotification"
    static let isVibration          = "isVibration"
    static let isSound              = "isSound"
}

enum AlertRequestCode: Int {
    case appUpgrade = 100
    case forgotPass = 101
    case logOut = 102
    case profileUpdate = 103
}

enum ResponseCode: Int {
    case created = 201
    case unauthorised = 401
    case unacceptable = 409
    case unprocessableEntity = 422
    case invalidUserRole = 403
    case receiptError = 400
}

extension Notification.Name {
    static let internetConnectivity = Notification.Name("internetConnectivity")
}

struct kNotification {
    static let isShow                          = "isShow"
}

struct kDateFormat {
    static let format1                          = "dd/MM/yyyy"
    static let format2                          = "dd/MM/yyyy hh:mm:ss"
    static let format3                          = "yyyy-MM-dd HH:mm:ss"
    static let format4                          = "dd/MM/yyyy HH:mm:ss"
    static let format5                          = "dd/MM/yyyy hh:mm a"
    static let MMM                              = "MMM"
    static let dd                               = "dd"
}

struct kNiB {
    struct Cell {
        static let TextFieldCell                = "TextFieldCell"
        static let LabelSelectionCell           = "LabelSelectionCell"
        static let ButtonCell                   = "ButtonCell"
        static let FavouriteTournamentCell      = "FavouriteTournamentCell"
        static let AgeCategoryCell              = "AgeCategoryCell"
        static let GroupSummaryMatchesCell      = "GroupSummaryMatchesCell"
        static let GroupSummaryStandingsCell    = "GroupSummaryStandingsCell"
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
        
        // CustomAlertView
        static let customAlertViewLblTitle = CGFloat(17)
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
    
    static let favDefaultSelected = UIColor(R: 0, G: 147, B: 0)
    static let favUnfav = UIColor(R: 187, G: 190, B: 195)
    static let favDefaultText = UIColor(R: 117, G: 117, B: 117)
    static let labelSlectionBg = UIColor(R: 222, G: 223, B: 226)
    static let btnDisable = UIColor(R: 204, G: 204, B: 204)
    static let btnYellow = UIColor(R: 237, G: 158, B: 45)
    static let txtPlaceholderBorder = UIColor(R: 223, G: 223, B: 223)
    static let txtPlaceholderTxt = UIColor(R: 117, G: 117, B: 117)
    static let txtPlaceholderDisable = UIColor(R: 246, G: 246, B: 246)
    static let txtDefaultTxt = UIColor(R: 81, G: 82, B: 88)
    static let settingsGreenArrow = UIColor(R: 109, G: 167, B: 33)
}

extension String {
    public static func localize(key: String, comment: String = "") -> String {
        return NSLocalizedString(key, comment: comment)
    }
}
