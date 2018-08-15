//
//
//
#import <Foundation/Foundation.h>

#ifdef DEBUG_MODE
#define DLog( s, ... ) NSLog( @"<%p %@:(%d)> %@", self, [[NSString stringWithUTF8String:__FILE__] lastPathComponent], __LINE__, [NSString stringWithFormat:(s), ##__VA_ARGS__] )
//#define DLog( s, ... ) 
#else
#define DLog( s, ... ) 
#endif

#ifndef kCFCoreFoundationVersionNumber_iOS_8_0
#define kCFCoreFoundationVersionNumber_iOS_8_0 1140.10
#endif

#ifndef kCFCoreFoundationVersionNumber_iOS_8_1
#define kCFCoreFoundationVersionNumber_iOS_8_1 1141.14
#endif

#ifndef iOS9
#define iOS9 (kCFCoreFoundationVersionNumber > kCFCoreFoundationVersionNumber_iOS_8_4)
#endif

#ifndef iOS8
#define iOS8 (kCFCoreFoundationVersionNumber >= kCFCoreFoundationVersionNumber_iOS_8_0)
#endif

#ifndef iOS7
#define iOS7 (kCFCoreFoundationVersionNumber >= kCFCoreFoundationVersionNumber_iOS_7_0)
#endif

#ifndef iOS6
#define iOS6 (kCFCoreFoundationVersionNumber >= kCFCoreFoundationVersionNumber_iOS_6_0)
#endif

#define IS_OS_8_OR_LATER ([[[UIDevice currentDevice] systemVersion] floatValue] >= 8.0)

#define SYSTEM_VERSION_GREATER_THAN_OR_EQUAL_TO(v)  ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] != NSOrderedAscending)

#define IDIOM   UI_USER_INTERFACE_IDIOM()
#define IPAD    UIUserInterfaceIdiomPad

//////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////  Constants /////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

// Keys
#define kDefaultUserSetting             1
#define kUserSettingsStatus             @"settingsstatus"
#define kLoginUser                      @"loginuser"
#define kLoginPass                      @"loginpassword"
#define kRememberUser                   @"rememberuser"
#define kLogout                         @"logout"

#define kFireBaseToken                  @"firebaseToken"
#define kToken                          @"token"

// Webservice Urls
#define HostName_URL                    @"74.120.218.195"
//#define BaseURL                         @"https://qa.wot.esrtmp.com"    // QA
//#define BaseURL                         @"https://www.esrtmp.com"       // Live
//#define BaseURL                         @"http://192.168.0.6:8180/"
//#define BaseURL                         @"https://qa.esrtmp.com"
//#define BaseURL                         @"http://esr.aecordigitalqa.com"
//#define BaseURL                         @"http://mukesh-wot.dev.aecortech.com"
//#define BaseURL                         @"http://sunny-eurosport.dev.aecortech.com"
//#define BaseURL                         @"http://krunal-eurosport.dev.aecortech.com"
#define BaseURL                         @"http://usama-eurosport.dev.aecortech.com"

#define VersionDetail                   @"/api/appversion"
#define Forgotpassword                  @"/api/password/email"

#define Login                           @"/api/auth/login"
#define CheckApi                        @"/api/auth/check"

#define Registration                    @"/api/user/create"
#define UpdateProfile                   @"/api/user/update/"

#define MakeTournamentFavourite         @"/api/users/setFavourite"
#define RemoveTournamentFavourite       @"/api/users/removeFavourite"
#define SetTournamentDefault            @"/api/users/setDefaultFavourite"
#define GetUserFavouriteTournamentList  @"/api/users/getLoginUserFavouriteTournament"
#define GetSetting                      @"/api/users/getSetting"
#define PostSetting                     @"/api/users/postSetting"
#define PushNotification                @"/api/users/updatefcm"

#define Tournaments                     @"/api/tournaments"
#define GetTournamentClub               @"/api/tournaments/getTournamentClub"

#define GetTeamList                     @"/api/teams/getTeamsList"

#define GetTournamentGroups             @"/api/match/getDraws"
#define GetStanding                     @"/api/match/getStanding/yes"
#define GetMatchFixtures                @"/api/match/getFixtures"

#define GetTournamentAge                @"/api/age_group/getCompetationFormat"
#define PlacingMatch                    @"/api/age_group/getPlacingsData"

// Not going to use
#define Resetpassword                   @"/api/password/reset"
#define UpdateProfileImage              @"/api/users/updateProfileImage"
#define GetTournamentDefault            @"/api/users/getLoginUserDefaultTournament"

#define NULL_TO_NIL(obj) ({ __typeof__ (obj) __obj = (obj); __obj == [NSNull null] ? nil : obj; })

// Default Values
#define DEVICE_BOUNDS                   [UIScreen mainScreen].bounds
#define DEVICE_WIDTH                    [UIScreen mainScreen].bounds.size.width
#define DEVICE_HEIGHT                   [UIScreen mainScreen].bounds.size.height
#define APPDELEGATE                     ((AppDelegate *)[[UIApplication sharedApplication] delegate])
#define USERDEFAULTS                    [NSUserDefaults standardUserDefaults]
#define NOTIFICATIONCENTER              [NSNotificationCenter defaultCenter]

#define DB_NAME                         @"data.sqlite"
#define NULL_STRING                     @""
#define NA_STRING                       @"NA"
#define kGoogleAnalyticsID              @""

// Constant Values
#define RGBA(r, g, b, a)                [UIColor colorWithRed:(float)r / 255.0 green:(float)g / 255.0 blue:(float)b / 255.0 alpha:a]
#define kClearColor                     [UIColor clearColor]
#define kWhiteColor                     [UIColor whiteColor]
#define kBlackColor                     [UIColor blackColor]
#define kGrayColor                      RGBA(100, 100, 100, 1)
#define kThemeColor                     RGBA(70, 166, 219, 1)
#define kThemeHexaColor                 @"47A6DB"

// Date Formats
#define kDateFormat                     @"dd/MM/yy"
#define kDateFormat1                    @"HH:mm dd MMM yyyy"

// Segue ID
#define SEGUE_LOGIN                     @"segue_login"

// VIew XiBs
#define kSendAlertView                  @"SendAlertView"

// Custom Notifications
#define GET_NOTIFICATION                @"getnotification"
#define JOB_NOTIFICATION                @"jobnotification"

// Storyboard Controller ID
#define kLoginVC                        @"LoginVC"

typedef enum {
    GeneralRequest = 1,
    HTTPLoginRequest,
    HTTPForgotPasswordRequest,
} HTTPRequest;

typedef enum {
    ServerError = 0,
    Success,
    NetworkError,
} HTTPResponseCode;

typedef enum {
    kLoadLocallyFirst = 1,
    kLoadLocallyLast,
    kNoCaching,
} HTTPRequestCachingType;

typedef enum {
    kTextFieldCell = 1,
    kLabelCell,
    KSelectionCell,
    kTextViewCell,
    kSwitchCell,
} CellType;

typedef enum{
    kGeneralKeyboard = 1,
    kNumberKeyboard = 2,
    kEmailKeyboard = 3,
    kURLKeyboard = 4,
} CustomKeyboardType;
