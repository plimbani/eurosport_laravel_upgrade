//
//  AppDelegate.h
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreData/CoreData.h>
#import <Fabric/Fabric.h>
#import <Crashlytics/Crashlytics.h>
#import "Firebase.h"
@import Firebase;
@import FirebaseInstanceID;
@import FirebaseMessaging;

@interface AppDelegate : UIResponder <UIApplicationDelegate>{
    NSString *sound;
    NSString *vibration;
    NSString *notification;
}
@property (nonatomic, strong) NSMutableDictionary *defaultTournamentDir;
@property (nonatomic, strong) NSString *orientationFlag;
@property (nonatomic, strong) NSString *competationFormatId;
@property (nonatomic, strong) NSString *firebaseToken;
@property (nonatomic, strong) NSString *selectedTab;
//@property (nonatomic, assign) NSInteger selectedTournament;

@property (strong, nonatomic) UIWindow *window;

@property (readonly, strong) NSPersistentContainer *persistentContainer;

- (void)saveContext;


@end

