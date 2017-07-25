//
//  ClubsVC.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "ClubsVC.h"
#import "ClubListVC.h"
#import "AgeListVC.h"
#import "GroupListVC.h"


@interface ClubsVC ()

@end

@implementation ClubsVC

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // Do any additional setup after loading the view.
    ClubListVC* clubListVC =[self.storyboard instantiateViewControllerWithIdentifier:@"ClubListVC"];
    AgeListVC* ageListVC =[self.storyboard instantiateViewControllerWithIdentifier:@"AgeListVC"];
    GroupListVC* groupListVC =[self.storyboard instantiateViewControllerWithIdentifier:@"GroupListVC"];
    clubListVC.title =@"CLUB";
    ageListVC.title =@"AGE";
    groupListVC.title = @"GROUPS";
    
    NSArray *viewControllers = @[clubListVC, ageListVC, groupListVC];
    MHTabBarController *tabBarController = [[MHTabBarController alloc] init];
    
    tabBarController.delegate = self;
   // [self.tabBarController.view setFrame:CGRectMake(0.0f, 0.0f, self.view.frame.size.width, self.view.frame.size.height)];
    tabBarController.viewControllers = viewControllers;
    [self.view addSubview:tabBarController.view];
    [self addChildViewController:tabBarController];
    [tabBarController didMoveToParentViewController:self];
    //self.view = tabBarController.view;
   // [self addChildViewController:tabBarController];
   // [self addSubview:tabBarController.view toView:self.view];
}
- (BOOL)mh_tabBarController:(MHTabBarController *)tabBarController shouldSelectViewController:(UIViewController *)viewController atIndex:(NSUInteger)index
{
    NSLog(@"mh_tabBarController %@ shouldSelectViewController %@ at index %lu", tabBarController, viewController, (unsigned long)index);
    
    // Uncomment this to prevent "Tab 3" from being selected.
    //return (index != 2);
    
    return YES;
}

- (void)mh_tabBarController:(MHTabBarController *)tabBarController didSelectViewController:(UIViewController *)viewController atIndex:(NSUInteger)index
{
    NSLog(@"mh_tabBarController %@ didSelectViewController %@ at index %lu", tabBarController, viewController, (unsigned long)index);
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

@end
