//
//  AppDelegate.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "AppDelegate.h"
#import "Globals.h"
#import "Utils.h"
#import <AFNetworking/AFNetworking.h>
#import "SVProgressHUD.h"
#import "HomeTabBar.h"
#import "LeagueDetailVC.h"
#import <UserNotifications/UserNotifications.h>
#import <AudioToolbox/AudioToolbox.h>
#import "LanguageManager.h"
#import "ApplicationUpdateVC.h"
@interface AppDelegate ()<UNUserNotificationCenterDelegate, FIRMessagingDelegate>

@end

@implementation AppDelegate
//@synthesize tournamentName,selectedTournament,
@synthesize defaultTournamentDir,orientationFlag,selectedTab,firebaseToken,competationFormatId;
NSString *const kGCMMessageIDKey = @"gcm.message_id";
-(void)updateToken{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    NSDictionary *params = @{@"email": [userData valueForKey:@"email"],@"password":[userData valueForKey:@"password"],@"forgotpassword":@"0",@"remember":@""};
    //NSDictionary *params = @{@"email": @"spatel@aecrodigital.com",@"password":@"sanjay1!" ,@"tournament_id":@"1",@"first_name":@"sanjay",@"sur_name":@"patel" };
    //Configure your session with common header fields like authorization etc
    NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
    sessionConfiguration.HTTPAdditionalHeaders = @{@"IsMobileUser": @"true"};
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
    NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Login ];
    //NSString *url =@"https://manager.gimbal.com/api/v2/places";
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                           cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
    NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
    [request setHTTPMethod:@"POST"];
    [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
    [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
    [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
    [request setHTTPBody: requestData];
    NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                      {
                                          if (error) {
                                              NSLog(@"data%@",data);
                                              NSLog(@"response%@",error);
                                              [SVProgressHUD dismiss];
                                          } else{
                                              NSError *parseError = nil;
                                              NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                              NSLog(@"%@",responseDictionary);
                                              NSString *token =responseDictionary[@"token"];
                                              NSString *error =responseDictionary[@"error"];
                                              
                                              if(token != NULL){
                                                  [[NSUserDefaults standardUserDefaults] setObject:token forKey:@"token"];
                                                  [self GetDefaultTournament];
                                                  [self getSetting];
                                              }else{
                                                  [SVProgressHUD dismiss];
                                                  
                                                  if (error != NULL) {
                                                      
                                                  }
                                              }
                                              
                                          }
                                      }];
    [dataTask resume];

}
-(void)GetDefaultTournament{
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"] };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetTournamentDefault];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: requestData];
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              if (error) {
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  defaultTournamentDir =[responseDictionary[@"data"] mutableCopy];
                                                  //NSLog(@"%@",defaultTournamentDir);
                                                  
//                                                  selectedTournament = [[responseDictionary valueForKey:@"tournament_id"] integerValue];
//                                                  tournamentName = [responseDictionary valueForKey:@"name"];
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)getSetting{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSDictionary *userData = [defaults objectForKey:@"userData"];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"]  };
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetSetting];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: requestData];
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              if (error) {
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  NSString *jsonString = [[[responseDictionary valueForKey:@"data"] objectAtIndex:0] valueForKey:@"value"];
                                                  NSData *data1 = [jsonString dataUsingEncoding:NSUTF8StringEncoding];
                                                  id responseDictionary1 = [NSJSONSerialization JSONObjectWithData:data1 options:0 error:nil];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      
                                                      if ([[responseDictionary1 valueForKey:@"is_sound"] isEqualToString:@"true"]) {
                                                          sound = @"true";
                                                      }else{
                                                          sound = @"false";
                                                    }
                                                      if ([[responseDictionary1  valueForKey:@"is_vibration"] isEqualToString:@"true"]) {
                                                          vibration = @"true";
                                                      }else{
                                                          vibration = @"false";
                                                      }
                                                      if ([[responseDictionary1  valueForKey:@"is_notification"] isEqualToString:@"true"]) {
                                                          notification = @"true";
                                                      }else{
                                                          notification = @"false";
                                                      }
                                                      if([sound isEqualToString:@"true"])
                                                      {
                                                          sound = @"true";
                                                          NSLog(@"The switch is on");
                                                          [UNUserNotificationCenter currentNotificationCenter].delegate = self;
                                                          UNAuthorizationOptions authOptions =
                                                          UNAuthorizationOptionAlert
                                                          | UNAuthorizationOptionSound
                                                          | UNAuthorizationOptionBadge;
                                                          [[UNUserNotificationCenter currentNotificationCenter] requestAuthorizationWithOptions:authOptions completionHandler:^(BOOL granted, NSError * _Nullable error) {
                                                              NSLog(@"%@",error);
                                                              NSLog(@"%d",granted);
                                                          }];
                                                          if([notification isEqualToString:@"true"])
                                                          {
                                                              notification = @"true";
                                                              NSLog(@"The switch is on");
                                                              [[UIApplication sharedApplication] registerForRemoteNotifications];
                                                          }
                                                          else
                                                          {
                                                              notification = @"false";
                                                              NSLog(@"The switch is off");
                                                              [[UIApplication sharedApplication] unregisterForRemoteNotifications];
                                                          }
                                                          [FIRMessaging messaging].remoteMessageDelegate = self;
                                                      }
                                                      else
                                                      {
                                                          sound = @"false";
                                                          NSLog(@"The switch is off");
                                                          [UNUserNotificationCenter currentNotificationCenter].delegate = self;
                                                          UNAuthorizationOptions authOptions =
                                                          UNAuthorizationOptionAlert
                                                          | UNAuthorizationOptionBadge;
                                                          [[UNUserNotificationCenter currentNotificationCenter] requestAuthorizationWithOptions:authOptions completionHandler:^(BOOL granted, NSError * _Nullable error) {
                                                              NSLog(@"%@",error);
                                                              NSLog(@"%d",granted);
                                                          }];
                                                          if([notification isEqualToString:@"true"])
                                                          {
                                                              [[UIApplication sharedApplication] registerForRemoteNotifications];
                                                          }
                                                          else
                                                          {
                                                              [[UIApplication sharedApplication] unregisterForRemoteNotifications];
                                                          }
                                                          [FIRMessaging messaging].remoteMessageDelegate = self;
                                                          
                                                      }
                                                  });
                                                  
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)getVersionDetail{

    if([Utils isNetworkAvailable] ==YES){
        NSDictionary *params = @{};
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,VersionDetail];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: requestData];
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              if (error) {
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  NSLog(@"%@",responseDictionary);
                                                  NSString *version = [responseDictionary valueForKey:@"ios_app_version"] ;
                                                  NSString *buildVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleShortVersionString"];
                                                  NSString *build = [[[NSBundle mainBundle] infoDictionary] objectForKey:(NSString *)kCFBundleVersionKey];
                                                  NSLog(@"%@  %@ %@",version,buildVersion,build);
                                                  if (version != NULL) {
                                                      if (![build isEqualToString:version]) {
                                                          dispatch_async(dispatch_get_main_queue(), ^{
                                                              AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                              app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
                                                              UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
                                                              ApplicationUpdateVC *myVC = (ApplicationUpdateVC *)[storyboard instantiateViewControllerWithIdentifier:@"ApplicationUpdateVC"];
                                                              UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
                                                              app.window.rootViewController = navigationObject;
                                                              navigationObject.navigationBar.hidden = TRUE;
                                                              [app.window makeKeyAndVisible];
                                                          });
                                                      }
                                                  }

                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
    
}
- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    // Override point for customization after application launch.
    [Crashlytics sharedInstance].debugMode = TRUE;
    [Fabric with:@[[Crashlytics class]]];
    //Crashlytics.logException(new Exception("my custom log for crashLytics !!!"));
//    [CrashlyticsKit setUserIdentifier:@"12345"];
//    [CrashlyticsKit setUserEmail:@"user@fabric.io"];
//    [CrashlyticsKit setUserName:@"Test User"];
//    NSMutableArray *array = [[NSMutableArray alloc]init];
//    @try
//    {
//        NSString *string = [array objectAtIndex:10];
//    }
//    @catch (NSException *exception)
//    {
//        NSError *error;
//        NSLog(@"%@ ",exception.name);
//        NSLog(@"Reason: %@ ",exception.reason);
////        NSDictionary *attributes = @{ };
////        [Crashlytics logEvent:@"authenticate.error" attributes:attributes];
////        [CrashlyticsKit recordError:exception.name];
//        [[Crashlytics sharedInstance] recordError:error];
////        [[Crashlytics sharedInstance] recordCustomExceptionName:@"test" reason:@"test" frameArray:<#(nonnull NSArray<CLSStackFrame *> *)#>
//
//    }
//    @finally
//    {
//        NSError *error;
//        [[Crashlytics sharedInstance] recordError:error];
//        NSLog(@"@@finaly Always Executes");
//    }
//    
//    [[Crashlytics sharedInstance] crash];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *token = [defaults objectForKey:@"token"];
//    NSString *loginFlag = [defaults objectForKey:@"loginflag"];
    firebaseToken = [[NSString alloc] init];
    orientationFlag =@"0";
    selectedTab = @"0";
    [[UIDevice currentDevice] beginGeneratingDeviceOrientationNotifications];
    
    [[NSNotificationCenter defaultCenter]addObserver:self selector:@selector(orientationChanged:)
                                                name:UIDeviceOrientationDidChangeNotification
                                              object:[UIDevice currentDevice]];
    //NSDictionary *userData = [defaults valueForKey:@""]
    //yourObjectType * token = [NSKeyedUnarchiver unarchiveObjectWithData:data];
//    if (![loginFlag isEqualToString:@"0"]) {
//        
//    }
    if (token != NULL) {
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
        myVC.selectedIndex = 1;
        UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        app.window.rootViewController = navigationObject;
        navigationObject.navigationBar.hidden = TRUE;
        //UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        //app.window.rootViewController = myVC;
        //navigationObject.navigationBar.hidden = TRUE;
        [app.window makeKeyAndVisible];
        [self updateToken];
        //[self getSetting];
    }
    
    if (floor(NSFoundationVersionNumber) <= NSFoundationVersionNumber_iOS_7_1) {
        // iOS 7.1 or earlier. Disable the deprecation warnings.
#pragma clang diagnostic push
#pragma clang diagnostic ignored "-Wdeprecated-declarations"
        UIRemoteNotificationType allNotificationTypes =
        (UIRemoteNotificationTypeSound |
         UIRemoteNotificationTypeAlert |
         UIRemoteNotificationTypeBadge);
        [application registerForRemoteNotificationTypes:allNotificationTypes];
#pragma clang diagnostic pop
    } else {
        // iOS 8 or later
        // [START register_for_notifications]
        if (floor(NSFoundationVersionNumber) <= NSFoundationVersionNumber_iOS_9_x_Max) {
            UIUserNotificationType allNotificationTypes =
            (UIUserNotificationTypeSound | UIUserNotificationTypeAlert | UIUserNotificationTypeBadge);
            UIUserNotificationSettings *settings =
            [UIUserNotificationSettings settingsForTypes:allNotificationTypes categories:nil];
            [[UIApplication sharedApplication] registerUserNotificationSettings:settings];
        } else {
            // iOS 10 or later
#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
            // For iOS 10 display notification (sent via APNS)
            
            [UNUserNotificationCenter currentNotificationCenter].delegate = self;
            UNAuthorizationOptions authOptions =
            UNAuthorizationOptionAlert
            | UNAuthorizationOptionSound
            | UNAuthorizationOptionBadge;
            [[UNUserNotificationCenter currentNotificationCenter] requestAuthorizationWithOptions:authOptions completionHandler:^(BOOL granted, NSError * _Nullable error) {
            }];
            // For iOS 10 data message (sent via FCM)
            [FIRMessaging messaging].remoteMessageDelegate = self;
            [FIRApp configure];
#endif
        }
        [[UIApplication sharedApplication] registerForRemoteNotifications];
        // [END register_for_notifications]
    }
    
    // [START configure_firebase]
    
    if (token != NULL) {
       // [self getSetting];
    }
    // [END configure_firebase]
    // [START add_token_refresh_observer]
    // Add observer for InstanceID token refresh callback.
    NSString *refreshedToken = [[FIRInstanceID instanceID] token];
    if (refreshedToken != NULL) {
        firebaseToken = refreshedToken;
        [self sendToken:refreshedToken];
        NSLog(@"InstanceID token: %@", refreshedToken);
    }
 //   [LanguageManager languageStrings];
    
//
//    NSString *path = [[ NSBundle mainBundle ] pathForResource:@"fr" ofType:@"lproj" ];
//    [NSBundle bundleWithPath:path] ;
//    NSMutableArray *appleLangs = [NSMutableArray arrayWithArray:[[NSUserDefaults standardUserDefaults] objectForKey:@"AppleLanguages"]];
//    
//    [appleLangs insertObject:@"fr" atIndex:0];
//    
//    [[NSUserDefaults standardUserDefaults] setObject:appleLangs forKey:@"AppleLanguages"];
//    [[NSUserDefaults standardUserDefaults] synchronize]; // needs a restart.
//    
//    
//    [[NSUserDefaults standardUserDefaults] setObject:@"fr" forKey:@"ICPreferredLanguage"];
//    [[NSUserDefaults standardUserDefaults] synchronize];
    [self getVersionDetail];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(tokenRefreshNotification:)
                                                 name:kFIRInstanceIDTokenRefreshNotification object:nil];
    return YES;
}


- (void)tokenRefreshNotification:(NSNotification *)notification {
    // Note that this callback will be fired everytime a new token is generated, including the first
    // time. So if you need to retrieve the token as soon as it is available this is where that
    // should be done.
    NSString *refreshedToken = [[FIRInstanceID instanceID] token];
    if (refreshedToken != NULL) {
        NSLog(@"InstanceID token: %@", refreshedToken);
        firebaseToken = refreshedToken;
        [self sendToken:refreshedToken];
    }
    
    // Connect to FCM since connection may have failed when attempted before having a token.
    [self connectToFcm];
    // TODO: If necessary send token to application server.
}
-(void)sendToken:(NSString *)registration_id{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    NSString *userEmail =[userData valueForKey:@"email"];
    NSString *token = registration_id;
    if([Utils isNetworkAvailable] ==YES){
        if (userEmail != NULL) {
            NSDictionary *params = @{@"email": userEmail, @"fcm_id": token};
            NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
            NSString *token = [defaults objectForKey:@"token"];
            NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
            NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
            NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
            sessionConfiguration.HTTPAdditionalHeaders = header;
            NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
            NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,PushNotification];
            NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                                   cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
            NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
            [request setHTTPMethod:@"POST"];
            [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
            [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
            [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
            [request setHTTPBody: requestData];
            NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                        completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                              {
                                                  if (error) {
                                                      NSLog(@"data%@",data);
                                                      NSLog(@"response%@",error);
                                                      [SVProgressHUD dismiss];
                                                  } else{
                                                      [SVProgressHUD dismiss];
                                                      NSError *parseError = nil;
                                                      NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                      NSLog(@"%@",responseDictionary);
                                                      
                                                  }
                                              }];
            [dataTask resume];
            
        }
    }else{
        
    }
}
- (void)connectToFcm {
    // Won't connect since there is no token
    if (![[FIRInstanceID instanceID] token]) {
        return;
    }
    // Disconnect previous FCM connection if it exists.
    [[FIRMessaging messaging] disconnect];
    [[FIRMessaging messaging] connectWithCompletion:^(NSError * _Nullable error) {
        if (error != nil) {
            NSLog(@"Unable to connect to FCM. %@", error);
        } else {
            NSLog(@"Connected to FCM.");
        }
    }];
}
- (void)applicationDidEnterBackground:(UIApplication *)application {
    [[FIRMessaging messaging] disconnect];
    NSLog(@"Disconnected from FCM");
}
- (void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo {
    // If you are receiving a notification message while your app is in the background,
    // this callback will not be fired till the user taps on the notification launching the application.
    // TODO: Handle data of notification
    // Print message ID.
    if (userInfo[kGCMMessageIDKey]) {
        NSLog(@"Message ID: %@", userInfo[kGCMMessageIDKey]);
    }
    // Print full message.
    NSLog(@"userInfo %@", userInfo);
}
-(void)checkSoundON  :(NSDictionary *)alert{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"]  };
    NSString *token = [defaults objectForKey:@"token"];
    NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
    NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
    sessionConfiguration.HTTPAdditionalHeaders = header;
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
    NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetSetting];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                           cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
    NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
    [request setHTTPMethod:@"POST"];
    [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
    [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
    [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
    [request setHTTPBody: requestData];
    NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                      {
                                          if (error) {
                                              NSLog(@"data%@",data);
                                              NSLog(@"response%@",error);
                                          } else{
                                              NSError *parseError = nil;
                                              NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                              NSString *jsonString = [[[responseDictionary valueForKey:@"data"] objectAtIndex:0] valueForKey:@"value"];
                                              NSData *data1 = [jsonString dataUsingEncoding:NSUTF8StringEncoding];
                                              id responseDictionary1 = [NSJSONSerialization JSONObjectWithData:data1 options:0 error:nil];
                                              dispatch_async(dispatch_get_main_queue(), ^{
                                                  
                                                  if ([[responseDictionary1 valueForKey:@"is_sound"] isEqualToString:@"true"]) {
                                                      UIAlertView *alertView = [[UIAlertView alloc]
                                                                                initWithTitle:[alert valueForKey:@"title"]
                                                                                message: [NSString stringWithFormat:@"%@", [alert valueForKey:@"body"]]
                                                                                delegate:self
                                                                                cancelButtonTitle: @"Ok"
                                                                                otherButtonTitles: nil];
                                                      
                                                      [alertView show];
                                                      AudioServicesPlaySystemSound(1315);
                                                      
                                                  }else{
                                                      UIAlertView *alertView = [[UIAlertView alloc]
                                                                                initWithTitle:[alert valueForKey:@"title"]
                                                                                message: [NSString stringWithFormat:@"%@", [alert valueForKey:@"body"]]
                                                                                delegate:self
                                                                                cancelButtonTitle: @"Ok"
                                                                                otherButtonTitles: nil];
                                                      
                                                      [alertView show];
                                                  }
                                                  
                                                  
                                              });
                                              
                                              
                                          }
                                      }];
    [dataTask resume];

}
- (void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo
fetchCompletionHandler:(void (^)(UIBackgroundFetchResult))completionHandler {
    // If you are receiving a notification message while your app is in the background,
    // this callback will not be fired till the user taps on the notification launching the application.
    // TODO: Handle data of notification
    
    // Print message ID.
    if (userInfo[kGCMMessageIDKey]) {
        NSLog(@"Message ID: %@", userInfo[kGCMMessageIDKey]);
    }
    
    // Print full message.
    NSLog(@"userInfo fetchCompletionHandler%@", userInfo);
    NSDictionary *alert = userInfo[@"aps"][@"alert"];
    if (application.applicationState == UIApplicationStateActive) {
        [self checkSoundON:alert];
        
    }
    
    
    
//    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"ESR" message:[alert valueForKey:@"body"] preferredStyle:UIAlertControllerStyleAlert];
//    UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:^(UIAlertAction * action) {
//        
//    }];
//    [alertController addAction:ok];
//    [self.window.rootViewController presentViewController:alertController animated:YES completion:nil];

    
    completionHandler(UIBackgroundFetchResultNewData);
}
- (void)application:(UIApplication *)application
didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)deviceToken {
    
    [[FIRInstanceID instanceID] setAPNSToken:deviceToken
                                        type:FIRInstanceIDAPNSTokenTypeSandbox];
    NSString *refreshedToken = [[FIRInstanceID instanceID] token];
    firebaseToken =refreshedToken;
    NSLog(@"firebaseToken %@",firebaseToken);
    //    NSString* newToken = [deviceToken description];
    //
    //    newToken = [newToken stringByTrimmingCharactersInSet:[NSCharacterSet characterSetWithCharactersInString:@"<>"]];
    //    newToken = [newToken stringByReplacingOccurrencesOfString:@" " withString:@""];
    
}
#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
// Receive data message on iOS 10 devices while app is in the foreground.
- (void)applicationReceivedRemoteMessage:(FIRMessagingRemoteMessage *)remoteMessage {
    // Print full message
    NSLog(@"applicationReceivedRemoteMessage %@", remoteMessage.appData);
}
#endif
- (void) orientationChanged:(NSNotification *)note
{
    UIDevice * device = note.object;
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    switch(device.orientation)
    {
        case UIDeviceOrientationPortrait:
            /*  */
            if ([app.orientationFlag isEqualToString:@"0"]) {
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
            }else{
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationLandscapeLeft) forKey:@"orientation"];
            }
            break;
            
        case UIDeviceOrientationPortraitUpsideDown:
            /*  */
            if ([app.orientationFlag isEqualToString:@"0"]) {
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
            }else{
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationLandscapeLeft) forKey:@"orientation"];
            }
            break;
        case UIDeviceOrientationLandscapeLeft:
            if ([app.orientationFlag isEqualToString:@"0"]) {
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
            }else{
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationLandscapeLeft) forKey:@"orientation"];
            }
            /*  */
            break;
            
        case UIDeviceOrientationLandscapeRight:
            if ([app.orientationFlag isEqualToString:@"0"]) {
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
            }else{
                [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationLandscapeLeft) forKey:@"orientation"];
            }
            /*  */
            break;
        default:
            break;
    };
}
//- (UIInterfaceOrientationMask)application:(UIApplication *)application supportedInterfaceOrientationsForWindow:(UIWindow *)window
//{
//    if ([self.window.rootViewController.presentedViewController isKindOfClass:[LeagueDetailVC class]])
//    {
//        LeagueDetailVC *secondController = (LeagueDetailVC *) self.window.rootViewController.parentViewController;
//        
//        if (secondController.isPresented)
//        {
//            return UIInterfaceOrientationMaskLandscape;
//        }
//        else return UIInterfaceOrientationMaskPortrait;
//    }
//    else return UIInterfaceOrientationMaskPortrait;
//}

- (void)applicationWillResignActive:(UIApplication *)application {
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and invalidate graphics rendering callbacks. Games should use this method to pause the game.
}


//- (void)applicationDidEnterBackground:(UIApplication *)application {
//    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
//    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
//}


- (void)applicationWillEnterForeground:(UIApplication *)application {
    // Called as part of the transition from the background to the active state; here you can undo many of the changes made on entering the background.
}


- (void)applicationDidBecomeActive:(UIApplication *)application {
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}


- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
    // Saves changes in the application's managed object context before the application terminates.
    [self saveContext];
}


#pragma mark - Core Data stack

@synthesize persistentContainer = _persistentContainer;

- (NSPersistentContainer *)persistentContainer {
    // The persistent container for the application. This implementation creates and returns a container, having loaded the store for the application to it.
    @synchronized (self) {
        if (_persistentContainer == nil) {
            _persistentContainer = [[NSPersistentContainer alloc] initWithName:@"ESR"];
            [_persistentContainer loadPersistentStoresWithCompletionHandler:^(NSPersistentStoreDescription *storeDescription, NSError *error) {
                if (error != nil) {
                    // Replace this implementation with code to handle the error appropriately.
                    // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
                    
                    /*
                     Typical reasons for an error here include:
                     * The parent directory does not exist, cannot be created, or disallows writing.
                     * The persistent store is not accessible, due to permissions or data protection when the device is locked.
                     * The device is out of space.
                     * The store could not be migrated to the current model version.
                     Check the error message to determine what the actual problem was.
                    */
                    NSLog(@"Unresolved error %@, %@", error, error.userInfo);
                    abort();
                }
            }];
        }
    }
    return _persistentContainer;
}

#pragma mark - Core Data Saving support

- (void)saveContext {
    NSManagedObjectContext *context = self.persistentContainer.viewContext;
    NSError *error = nil;
    if ([context hasChanges] && ![context save:&error]) {
        // Replace this implementation with code to handle the error appropriately.
        // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
        NSLog(@"Unresolved error %@, %@", error, error.userInfo);
        abort();
    }
}
@end
