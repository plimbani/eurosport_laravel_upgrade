//
//  ApplicationData.m
//

#import "ApplicationData.h"

static ApplicationData *applicationData = nil;

@implementation ApplicationData

@synthesize wifiReach;
@synthesize currentNetworkStatus;
@synthesize userFileManager;
//@synthesize shouldLoadContentLocally;
//@synthesize currentUser;

@synthesize userList;

- (id)init {
    self = [super init];
    if(self) {
        [NOTIFICATIONCENTER addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
        wifiReach = [Reachability reachabilityWithHostName:@"www.google.com"];
        [wifiReach startNotifier];
        currentNetworkStatus = ReachableViaWiFi;
        //self.shouldLoadContentLocally = NO;
        //currentUser = [[User alloc] init];

        userFileManager = [[UserFileManager alloc] init];
    }
    return self;
}

- (void)initialize {
    // set here initialize
}

+ (ApplicationData *)sharedInstance {
    if (applicationData == nil) {
        applicationData = [[super allocWithZone:NULL] init];
        [applicationData initialize];
        [ApplicationData setDefaultSettings];
        
    }
    return applicationData;
}

+ (id)allocWithZone:(NSZone *)zone {
    return [self sharedInstance];
}

- (id)copyWithZone:(NSZone *)zone {
    return self;
}

+ (void)setDefaultSettings {
    if([USERDEFAULTS integerForKey:kUserSettingsStatus] != kDefaultUserSetting) {
        [USERDEFAULTS setInteger:kDefaultUserSetting forKey:kUserSettingsStatus];
        [USERDEFAULTS setValue:NULL_STRING forKey:kLoginPass];
        [USERDEFAULTS setValue:NULL_STRING forKey:kLoginPass];
        [USERDEFAULTS setBool:NO forKey:kRememberUser];
    }
}

+ (void)showAlert:(NSString *)title Content:(NSString *)bodyText parent:(UIViewController *)parent {
    NSString *okTitle = NSLocalizedString(@"btn_ok", @"button title");
    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:bodyText preferredStyle:UIAlertControllerStyleAlert];
    UIAlertAction *ok = [UIAlertAction actionWithTitle:okTitle style:UIAlertActionStyleDefault handler:nil];
    [alertController addAction:ok];
    [parent presentViewController:alertController animated:YES completion:nil];
}

+ (void)setBorder:(UIView *)view Color:(UIColor *)color CornerRadius:(float)radius Thickness:(float)thickness {
    CALayer *viewLayer = [view layer];
    [viewLayer setMasksToBounds:YES];
    [viewLayer setCornerRadius:radius];
    [viewLayer setBorderWidth:thickness];
    [viewLayer setBorderColor:[color CGColor]];
}

+ (BOOL)NSStringIsValidEmail:(NSString *)checkString {
    BOOL stricterFilter = NO;
    NSString *stricterFilterString = @"[A-Z0-9a-z\\._%+-]+@([A-Za-z0-9-]+\\.)+[A-Za-z]{2,4}";
    NSString *laxString = @".+@([A-Za-z0-9-]+\\.)+[A-Za-z]{2}[A-Za-z]*";
    NSString *emailRegex = stricterFilter ? stricterFilterString : laxString;
    NSPredicate *emailTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", emailRegex];
    return [emailTest evaluateWithObject:checkString];
}

+ (BOOL)NSStringIsValidNumber:(NSString *)checkString {
    NSString *phoneRegex = @"^[0-9][0-9]{9}$";
    NSPredicate *phoneTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", phoneRegex];
    return [phoneTest evaluateWithObject:checkString];
}

+ (NSString *)getTodayDate {
    return [self convertDateStringFromDate:[NSDate date] dateFormat:kDateFormat];
}

+ (NSDate *)convertDateFromDateString:(NSString *)dateString dateFormat:(NSString *)dateFormat {
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setTimeZone:[NSTimeZone defaultTimeZone]];
    [dateFormatter setDateFormat:dateFormat];
    return [dateFormatter dateFromString:dateString];
}

+ (NSString *)convertDateStringFromDate:(NSDate *)date dateFormat:(NSString *)dateFormat {

    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setTimeZone:[NSTimeZone defaultTimeZone]];
    [dateFormatter setDateFormat:dateFormat];
    return [dateFormatter stringFromDate:date];
}

+ (NSString *)convertJsonStringFromJsonObject:(NSObject *)jsonObject {
    NSString *jsonString = NULL_STRING;
    if (jsonObject) {
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:jsonObject options:NSJSONWritingPrettyPrinted error:nil];
        if (jsonData) {
            jsonString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        }
    }
    NSLog(@"jsonString: %@",jsonString);
    return jsonString;
}

+ (NSObject *)convertJsonObjectFromJsonString:(NSString *)jsonString {
    NSObject *jsonObject = nil;
    if ([jsonString length] > 0) {
        NSData *jsonData = [jsonString dataUsingEncoding:NSUTF8StringEncoding];
        if (jsonData) {
            jsonObject = [NSJSONSerialization JSONObjectWithData:jsonData options:NSJSONReadingMutableContainers error:nil];
        }
    }
    NSLog(@"jsonObject: %@",jsonObject);
    return jsonObject;
}

+ (NSString *)getStringByCheckNullString:(NSString *)string {
    if ([string isEqual:[NSNull null]] || [string length] == 0) {
        string = NULL_STRING;
    }
    return string;
}

- (void)setAttributedText:(NSDictionary *)attribs inText:(NSMutableAttributedString *)atrbContentString word:(NSString *)word {
    NSUInteger count = 0, length = [atrbContentString length];
    NSRange range = NSMakeRange(0, length);
    
    while (range.location != NSNotFound) {
        range = [[atrbContentString string] rangeOfString:word options:0 range:range];
        if (range.location != NSNotFound) {
            [atrbContentString addAttributes:attribs range:NSMakeRange(range.location, [word length])];
            range = NSMakeRange(range.location + range.length, length - (range.location + range.length));
            count++;
        }
    }
}

#pragma -
#pragma Network Status Update

- (void) reachabilityChanged: (NSNotification* )note {
    NetworkStatus netStatus = [wifiReach currentReachabilityStatus];
    if(currentNetworkStatus == netStatus) {
        return;
    }
    currentNetworkStatus = netStatus;
}

- (BOOL)isReachable {
    NetworkStatus networkStatus = [wifiReach currentReachabilityStatus];
    if(networkStatus == NotReachable) {
        return NO;
    }
    return YES;
}

- (NSDictionary *)getDefaultTournamentDic:(id)defaultTournaments {
    NSDictionary *dicDefaultTournament = nil;
    if ([defaultTournaments isKindOfClass:[NSMutableArray class]] || [defaultTournaments isKindOfClass:[NSArray class]]) {
        for (NSDictionary *dic in defaultTournaments) {
            id obj_is_default = [dic valueForKey:@"is_default"];
            int is_default = 0;
            if ([obj_is_default isKindOfClass:[NSNull class]]) {
                is_default = 0;
            } else {
                if ([obj_is_default isKindOfClass:[NSString class]]) {
                    is_default = [[ApplicationData getStringByCheckNullString:obj_is_default] intValue];
                } else {
                    is_default = [obj_is_default intValue];
                }
            }
            if (is_default == 1) {
                dicDefaultTournament = dic;
                break;
            }
        }
    } else {
        dicDefaultTournament = (NSDictionary *)defaultTournaments;
    }
    return dicDefaultTournament;
}

- (void)logOut {
    [self initialize];
    [USERDEFAULTS setBool:NO forKey:kRememberUser];
    [USERDEFAULTS setValue:NULL_STRING forKey:kLoginUser];
    [USERDEFAULTS setValue:NULL_STRING forKey:kLoginPass];
}

@end
