//
//  ApplicationData.h
//  RealEstateManager
//

#import <UIKit/UIKit.h>
#import <Foundation/Foundation.h>
#import "Reachability.h"
#import "UserFileManager.h"

//@class User;

@interface ApplicationData : NSObject {

    //User *currentUser;

    Reachability *wifiReach;
    NetworkStatus currentNetworkStatus;
    UserFileManager *userFileManager;
    //HTTPResponseCode responseCode;
    //BOOL shouldLoadContentLocally;

    NSMutableArray *userList;
}

//@property (nonatomic, strong) User *currentUser;
@property (nonatomic, strong) Reachability *wifiReach;
@property (nonatomic, assign) NetworkStatus currentNetworkStatus;
@property (nonatomic, strong) UserFileManager *userFileManager;

//@property HTTPResponseCode responseCode;
//@property BOOL shouldLoadContentLocally;
@property (nonatomic, strong) NSMutableArray *userList;

+ (ApplicationData *)sharedInstance;
+ (void)setDefaultSettings;
+ (void)showAlert:(NSString *)title Content:(NSString *)bodyText parent:(UIViewController *)parent;
+ (void)setBorder:(UIView *)view Color:(UIColor *)color CornerRadius:(float)radius Thickness:(float)thickness;
+ (BOOL)NSStringIsValidEmail:(NSString *)checkString;
+ (BOOL)NSStringIsValidNumber:(NSString *)checkString;

+ (NSString *)getTodayDate;
+ (NSDate *)convertDateFromDateString:(NSString *)dateString dateFormat:(NSString *)dateFormat;
+ (NSString *)convertDateStringFromDate:(NSDate *)date dateFormat:(NSString *)dateFormat;
+ (NSString *)convertJsonStringFromJsonObject:(NSObject *)object;
+ (NSObject *)convertJsonObjectFromJsonString:(NSString *)jsonString;
+ (NSString *)getStringByCheckNullString:(NSString *)string;
- (void)setAttributedText:(NSDictionary *)attribs inText:(NSMutableAttributedString *)atrbContentString word:(NSString *)word;

- (void)reachabilityChanged:(NSNotification *)note;
- (BOOL)isReachable;

#pragma mark Instance methods

- (NSDictionary *)getDefaultTournamentDic:(id)defaultTournaments;
- (void)logOut;

@end
