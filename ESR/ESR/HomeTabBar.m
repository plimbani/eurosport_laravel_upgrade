//
//  HomeTabBar.m
//  ESR
//
//  Created by Aecor Digital on 21/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "HomeTabBar.h"
#import "AppDelegate.h"

@interface HomeTabBar ()

@end

@implementation HomeTabBar

- (void)viewDidLoad {
    [super viewDidLoad];
    self.delegate = self;
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(teamBtnClick:)
                                                 name:@"teamBtnClick" object:nil];
    

//    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
//    app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
//    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//    HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
//    UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
//    app.window.rootViewController = navigationObject;
//    navigationObject.navigationBar.hidden = TRUE;
//    [app.window makeKeyAndVisible];
//    [self.navigationController popToRootViewControllerAnimated:TRUE];
    // Do any additional setup after loading the view.
}
- (void)teamBtnClick:(NSNotification *)message {
    NSInteger tabitem = 2;
    //self.tabBarController.selectedIndex = 2;
    //[self.delegate tabBarController:self didSelectViewController:[self.tabBarController.viewControllers objectAtIndex:2]];
    [[self.viewControllers objectAtIndex:tabitem] popToRootViewControllerAnimated:YES];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
//- (void)tabBarController:(UITabBarController *)tabBarController didSelectViewController:(UIViewController *)viewController {
//
//    UITabBarItem *item = [tabBarController.tabBar selectedItem];
//    //    int index = [tabBarController.tabBar determinePositionInTabBar:item]; // custom method
//    //    [tabBarController.tabBar doSomethingWithTabBar];
//    //    [item doSomethingWithItem];
//    //    [item doSomethingWithItemAndIndex:index];
//}
//-(void)tabBar:(UITabBar *)tabBar didSelectItem:(UITabBarItem *)item
//{
//    
//    NSLog(@"test");
//    if(item.tag==2)
//    {
//        
//        UIStoryboard *mainStoryboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//        UITabBarController *TimeLine = [mainStoryboard instantiateViewControllerWithIdentifier:@"Post_Photo_One"];
//        [self presentViewController:TimeLine animated:YES completion:nil];
//    }
//    else
//    {
//        //your code
//    }
//}
-(void)tabBar:(UITabBar *)tabBar didSelectItem:(UITabBarItem *)item{

    [self.navigationController popToRootViewControllerAnimated:YES];
//    int index = [tabBar determinePositionInTabBar:item];
//    NSInteger tabitem = self.tabBarController.selectedIndex;
//    [[self.tabBarController.viewControllers objectAtIndex:index] popToRootViewControllerAnimated:YES];
}
//- (void)tabBarController:(UITabBarController *)tabBarCtrl didSelectViewController:(UIViewController *)viewController{
//    
//}
-(void)tabBarController:(UITabBarController *)tabBarController didSelectViewController:(UIViewController *)viewController{
    NSInteger tabitem = tabBarController.selectedIndex;
    [[tabBarController.viewControllers objectAtIndex:tabitem] popToRootViewControllerAnimated:YES];
    
//    UITabBarItem *item = [tabBarController.tabBar selectedItem];
////    [[self.tabBarController.viewControllers objectAtIndex:item] popToRootViewControllerAnimated:YES];
//    
//    [self.navigationController popToRootViewControllerAnimated:YES];
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
