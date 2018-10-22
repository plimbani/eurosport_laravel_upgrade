//
//  ApplicationUpdateVC.m
//  ESR
//
//  Created by Aecor Digital on 09/01/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import "ApplicationUpdateVC.h"
#import "AppDelegate.h"
#import "HomeTabBar.h"
#import "LoginVC.h"
#import "HomeVC.h"

@interface ApplicationUpdateVC ()

@end

@implementation ApplicationUpdateVC

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
- (IBAction)updateBtnClick:(id)sender {
    //    NSString *simple = @"itms-apps://itunes.apple.com/app/id1234567890";
//    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:simple]];

    NSString *iTunesLink = @"itms-apps://itunes.apple.com/app/id1437488944";
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:iTunesLink]];
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *token = [defaults objectForKey:@"token"];
    if (token != NULL) {
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
        HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
        myVC.selectedIndex = 1;
        UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        app.window.rootViewController = navigationObject;
        navigationObject.navigationBar.hidden = TRUE;
        [app.window makeKeyAndVisible];
        [self.navigationController popToRootViewControllerAnimated:TRUE];
    }else{
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
        HomeVC *myVC = (HomeVC *)[storyboard instantiateViewControllerWithIdentifier:@"HomeVC"];
        UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        app.window.rootViewController = navigationObject;
        navigationObject.navigationBar.hidden = TRUE;
        [app.window makeKeyAndVisible];
        [self.navigationController popToRootViewControllerAnimated:TRUE];
    }
    
   // https://itunes.apple.com/us/app/euro-sportring-tournaments/id1437488944?mt=8
}

- (IBAction)cancelBtnClick:(id)sender {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *token = [defaults objectForKey:@"token"];
    if (token != NULL) {
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
        HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
        myVC.selectedIndex = 1;
        UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        app.window.rootViewController = navigationObject;
        navigationObject.navigationBar.hidden = TRUE;
        [app.window makeKeyAndVisible];
        [self.navigationController popToRootViewControllerAnimated:TRUE];
    }else{
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
        HomeVC *myVC = (HomeVC *)[storyboard instantiateViewControllerWithIdentifier:@"HomeVC"];
        UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
        app.window.rootViewController = navigationObject;
        navigationObject.navigationBar.hidden = TRUE;
        [app.window makeKeyAndVisible];
        [self.navigationController popToRootViewControllerAnimated:TRUE];
    }
    
}
@end
