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

let APPSTORE_APP_URL = "itms-apps://itunes.apple.com/app/id1437488944"

struct API_ENDPOINT {
    
    static let COUNTRIES_LIST = API_URL.BASE_URL + "getCountries"
    static let TOURNAMENTS = API_URL.BASE_URL + "tournaments"
    static let APP_VERSION = API_URL.BASE_URL + "get_project_configurations"
    static let LOGIN = API_URL.BASE_URL + "auth/login"
    static let SOCIAL_LOGIN = API_URL.BASE_URL + "auth/social/login"
    static let REGISTER = API_URL.BASE_URL + "user/create"
    static let FORGOT_PASSWORD = API_URL.BASE_URL + "password/email"
    static let CHECK_USER = API_URL.BASE_URL + "auth/check"
    static let GET_SETTINGS = API_URL.BASE_URL + "users/getSetting"
    static let UPDATE_SETTINGS = API_URL.BASE_URL + "users/postSetting"
    static let UPDATE_PROFILE = API_URL.BASE_URL + "user/update/%@"
    static let GET_FINAL_PLACING_MATCHES = API_URL.BASE_URL + "age_group/getPlacingsData"
    // Tab fav
    static let REMOVE_FAVOURITE = API_URL.BASE_URL + "users/removeFavourite"
    static let SET_FAVOURITE = API_URL.BASE_URL + "users/setFavourite"
    static let SET_DEFAULT_FAVOURITE = API_URL.BASE_URL + "users/setDefaultFavourite"
    static let GET_FAVOURITE = API_URL.BASE_URL + "users/getLoginUserFavouriteTournament"
    // Tab team
    static let GET_TEAM_LIST = API_URL.BASE_URL + "teams/getTeamsList"
    
    static let AGE_CATEGORIES = API_URL.BASE_URL + "age_group/getCompetationFormat"
    static let TOURNAMENT_GROUP = API_URL.BASE_URL + "match/getDraws"
    static let TOURNAMENT_CLUBS = API_URL.BASE_URL + "tournaments/getTournamentClub"
    static let TEAM_FIXTURES = API_URL.BASE_URL + "match/getFixtures"
    static let GROUP_STANDING = API_URL.BASE_URL + "match/getStanding/yes"
    
    static let UPDATE_FCM = API_URL.BASE_URL + "users/updatefcm"
    
    static let VIEW_GRAPHIC = API_URL.BASE_URL + "viewGraphicImage"
    static let RESEND_EMAIL = API_URL.BASE_URL + "userResendEmail"
    
    static let ACCESS_CODE = API_URL.BASE_URL + "tournament/access_code";
    
    static let UPDATE_APP_VERSION = API_URL.BASE_URL + "updateAppDeviceVersion";
    static let GET_TOURNAMENT_TEAM_DETAILS = API_URL.BASE_URL + "getTournamentTeamDetails";
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
    static let TabFollowVC                      = "TabFollowVC"
    
    static let AllMatchesVC                     = "AllMatchesVC"
    static let ClubListVC                       = "ClubListVC"
    static let CategoryListVC                   = "CategoryListVC"
    static let GroupListVC                      = "GroupListVC"
    static let TeamListingVC                    = "TeamListingVC"
    static let TeamVC                           = "TeamVC"
    static let TabAgeCategoriesVC               = "TabAgeCategoriesVC"
    static let TabSettingsVC                    = "TabSettingsVC"
    static let FinalPlacingsVC                  = "FinalPlacingsVC"
    // Settings
    static let ProfileVC                        = "ProfileVC"
    static let NotificationAndSoundVC           = "NotificationAndSoundVC"
    static let PrivacyAndTermsVC                = "PrivacyAndTermsVC"
    static let AgeCategoriesGroupsVC            = "AgeCategoriesGroupsVC"
    static let AgeCategoriesGroupsSummaryVC     = "AgeCategoriesGroupsSummaryVC"
    static let GroupDetailsVC                   = "GroupDetailsVC"
    static let MatchInfoVC                      = "MatchInfoVC"
    static let VenueVC                          = "VenueVC"
    
    static let MapVC                            = "MapVC"
    static let CustomAlertVC                    = "CustomAlertVC"
    static let CustomAlertTwoBtnVC              = "CustomAlertTwoBtnVC"
    static let ViewScheduleImageVC              = "ViewScheduleImageVC"
    static let PickerVC                         = "PickerVC"
    static let GetStartedTournamentVC           = "GetStartedTournamentVC"
}

enum TabIndex: Int {
    case tabFav = 0
    case tabTournament = 1
    case tabTeams = 2
    case tabAgeCategories = 3
    case tabsettings = 4
}

struct kUserDefaults {
    static let token                = "token"
    static let fcmToken             = "fcmToken"
    static let email                = "email"
    static let password             = "password"
    static let locale               = "locale"
    static let userData             = "userData"
    static let isNotification       = "isNotification"
    static let isSound              = "isSound"
    static let selectedTournament   = "selectedTournament"
    static let isRememberLogin      = "isRememberLogin"
    static let isLogin              = "isLogin"
    static let isFacebookLogin      = "isFacebookLogin"
    // System reserved key
    static let currentLanguageKey   = "i18n_language" // i18n_language currentLanguageKey
}

enum ViewBorderType: Int {
    case top = 1
    case bottom = 2
    case left = 3
    case right = 4
}

enum AlertRequestCode: Int {
    case appUpgrade = 100
    case forgotPass = 101
    case logOut = 102
    case profileUpdate = 103
    case resendEmail = 104
    case tournamentExpire = 105
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
    static let selectCountry = Notification.Name("selectCountry")
    static let goToTabFollow = Notification.Name("goToTabFollow")
    static let accessCodeAPI = Notification.Name("accessCodeAPI")
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
    static let format6                          = "hh:mm dd MMMM yyyy"
    static let MMM                              = "MMM"
    static let dd                               = "dd"
    static let hhmm                             = "hh:mm"
}

struct kNiB {
    struct Cell {
        static let TextFieldCell                = "TextFieldCell"
        static let TwoLabelCell                 = "TwoLabelCell"
        static let LabelSelectionCell           = "LabelSelectionCell"
        static let ButtonCell                   = "ButtonCell"
        static let FavouriteTournamentCell      = "FavouriteTournamentCell"
        static let FollowTournamentCell         = "FollowTournamentCell"
        static let AgeCategoryCell              = "AgeCategoryCell"
        static let TeamListCell                 = "TeamListCell"
        static let GroupSummaryMatchesCell      = "GroupSummaryMatchesCell"
        static let GroupSummaryStandingsCell    = "GroupSummaryStandingsCell"
        static let TournamentClubCell           = "TournamentClubCell"
        static let LabelCell                    = "LabelCell"
        static let TextViewCell                 = "TextViewCell"
        static let FinalPlacingsCell            = "FinalPlacingsCell"
        static let TabAgeCategoriesCell         = "TabAgeCategoriesCell"
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
    static let HELVETICA_REGULAR = "HelveticaNeue"
    static let HELVETICA_BOLD = "HelveticaNeue-Bold"
    static let HELVETICA_MEDIUM = "HelveticaNeue-Medium"
    
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
    case TwoLabelCell               = 4
    case LabelCell                  = 5
    case TextViewCell               = 6
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

extension Date {
    func getMonthName() -> String {
        let dateFormatter = DateFormatter()
        dateFormatter.dateFormat = "MMMM"
        let strMonth = dateFormatter.string(from: self)
        return String.localize(key: strMonth)
    }
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
    
    // #C70A20
    static func AppColor() -> UIColor {
        return UIColor(named: "navigationbarcolor") ?? UIColor(R: 199, G: 10, B: 32)
    }
    
    static let tournamentDetailYellow = UIColor(R: 255, G: 182, B: 39)
    static let teamTabLblDefault = UIColor(R: 221, G: 170, B: 171)
    static let teamTabLblDefaultBlue = UIColor(R: 159, G: 213, B: 238)
    static let teamTabOrange = UIColor(R: 213, G: 143, B: 40)
    static let teamTabBlue = UIColor(R: 7, G: 76, B: 132)
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
    static let viewScheduleBlue = UIColor(R: 45, G: 145, B: 199)
}

extension String {
    public static func localize(key: String, comment: String = "") -> String {
        return NSLocalizedString(key, comment: comment)
    }
    
    func capitalizingFirstLetter() -> String {
        return prefix(1).uppercased() + self.lowercased().dropFirst()
    }
    
    mutating func capitalizeFirstLetter() {
        self = self.capitalizingFirstLetter()
    }
    
    subscript (i: Int) -> Character {
        return self[index(startIndex, offsetBy: i)]
    }
    subscript (bounds: CountableRange<Int>) -> Substring {
        let start = index(startIndex, offsetBy: bounds.lowerBound)
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[start ..< end]
    }
    subscript (bounds: CountableClosedRange<Int>) -> Substring {
        let start = index(startIndex, offsetBy: bounds.lowerBound)
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[start ... end]
    }
    subscript (bounds: CountablePartialRangeFrom<Int>) -> Substring {
        let start = index(startIndex, offsetBy: bounds.lowerBound)
        let end = index(endIndex, offsetBy: -1)
        return self[start ... end]
    }
    subscript (bounds: PartialRangeThrough<Int>) -> Substring {
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[startIndex ... end]
    }
    subscript (bounds: PartialRangeUpTo<Int>) -> Substring {
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[startIndex ..< end]
    }
}

extension Substring {
    subscript (i: Int) -> Character {
        return self[index(startIndex, offsetBy: i)]
    }
    subscript (bounds: CountableRange<Int>) -> Substring {
        let start = index(startIndex, offsetBy: bounds.lowerBound)
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[start ..< end]
    }
    subscript (bounds: CountableClosedRange<Int>) -> Substring {
        let start = index(startIndex, offsetBy: bounds.lowerBound)
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[start ... end]
    }
    subscript (bounds: CountablePartialRangeFrom<Int>) -> Substring {
        let start = index(startIndex, offsetBy: bounds.lowerBound)
        let end = index(endIndex, offsetBy: -1)
        return self[start ... end]
    }
    subscript (bounds: PartialRangeThrough<Int>) -> Substring {
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[startIndex ... end]
    }
    subscript (bounds: PartialRangeUpTo<Int>) -> Substring {
        let end = index(startIndex, offsetBy: bounds.upperBound)
        return self[startIndex ..< end]
    }
}

// Example for string and substring
//let str = "abcde"
//print(type(of: str))
//print(str[1])     // => b
//print(str[1..<3]) // => bc
//print(str[1...3]) // => bcd
//print(str[1...])  // => bcde
//print(str[...3])  // => abcd
//print(str[..<3])  // => abc
//print("")
//
//// With substrings:
//let sub = str[0...]
//print(type(of: sub))
//print(sub[1])     // => b
//print(sub[1..<3]) // => bc
//print(sub[1...3]) // => bcd
//print(sub[1...])  // => bcde
//print(sub[...3])  // => abcd
//print(sub[..<3])  // => abc
