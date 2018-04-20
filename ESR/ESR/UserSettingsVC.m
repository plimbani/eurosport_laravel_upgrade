//
//  UserSettingsVC.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "UserSettingsVC.h"
#import "AppDelegate.h"
#import "HomeVC.h"
#import "Utils.h"
#import "Reachability.h"

@interface UserSettingsVC ()

@end

@implementation UserSettingsVC

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}
-(void)viewWillAppear:(BOOL)animated{
    self.notificationLbl.text = NSLocalizedString(@"Notifications & sounds", @"");
    self.profileLbl.text = NSLocalizedString(@"Profile", @"");
    self.helpLbl.text = NSLocalizedString(@"Help", @"");
    self.privacyLbl.text = NSLocalizedString(@"Privacy & terms", @"");
    self.logoutLbl.text = NSLocalizedString(@"Log out", @"");
    [self.view setNeedsDisplay];
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

- (IBAction)helpBtnClick:(id)sender {
}

- (IBAction)privacyBtnClick:(id)sender {
}

- (IBAction)profileBtnClikc:(id)sender {
}

- (IBAction)notificationBtnClick:(id)sender {
}

- (IBAction)logoutBtnClick:(id)sender {
    self.alertView.hidden = FALSE;
    self.alertViewTitle.text = NSLocalizedString(@"Confirm",@"");
    self.alertViewSubTitle.text =NSLocalizedString(@"Are you sure you want to log out?", @"");
//    UIApplication *application = [UIApplication sharedApplication];
//    //NSURL *URL = [NSURL URLWithString:@"prefs:root=MUSIC"];
//    NSURL*URL=[NSURL URLWithString:@"prefs://"];
//    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
//        if (success) {
//            NSLog(@"Opened url");
//        }
//    }];
}
- (IBAction)alertViewOkBtnClick:(id)sender {
    self.alertView.hidden = TRUE;
    [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"token"];
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    HomeVC *myVC = (HomeVC *)[storyboard instantiateViewControllerWithIdentifier:@"HomeVC"];
    UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
    app.window.rootViewController = navigationObject;
    navigationObject.navigationBar.hidden = TRUE;
    [app.window makeKeyAndVisible];
    [self.navigationController popToRootViewControllerAnimated:TRUE];
}

- (IBAction)alertViewCancelBtnClick:(id)sender {
    self.alertView.hidden = TRUE;
}
@end
