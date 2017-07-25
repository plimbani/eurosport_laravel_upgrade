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
    
}
- (IBAction)alertViewOkBtnClick:(id)sender {
    self.alertView.hidden = TRUE;
    self.alertViewTitle.text = @"Confirm";
    self.alertViewSubTitle.text = @"Are you sure you want to log out?";
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
