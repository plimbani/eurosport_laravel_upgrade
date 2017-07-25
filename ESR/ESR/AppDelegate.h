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

@interface AppDelegate : UIResponder <UIApplicationDelegate>{
    
}
@property (nonatomic, strong) NSMutableDictionary *defaultTournamentDir;
@property (nonatomic, strong) NSString *orientationFlag;
//@property (nonatomic, assign) NSInteger selectedTournament;

@property (strong, nonatomic) UIWindow *window;

@property (readonly, strong) NSPersistentContainer *persistentContainer;

- (void)saveContext;


@end

