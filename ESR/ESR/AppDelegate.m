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


@interface AppDelegate ()

@end

@implementation AppDelegate
//@synthesize tournamentName,selectedTournament,
@synthesize defaultTournamentDir,orientationFlag;
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
- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    // Override point for customization after application launch.
    [Fabric with:@[[Crashlytics class]]];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *token = [defaults objectForKey:@"token"];
//    NSString *loginFlag = [defaults objectForKey:@"loginflag"];
    
    orientationFlag =@"0";
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
        //UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        app.window.rootViewController = myVC;
        //navigationObject.navigationBar.hidden = TRUE;
        [app.window makeKeyAndVisible];
        [self GetDefaultTournament];
    }
    return YES;
}
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


- (void)applicationDidEnterBackground:(UIApplication *)application {
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}


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
