//
//  UINavigationController+Rotation_IOS6.m
//  ESR
//
//  Created by Aecor Digital on 04/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "UINavigationController+Rotation_IOS6.h"
#import "LeagueDetailVC.h"

@implementation UINavigationController (Rotation_IOS6)

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    
    if ([self.topViewController isKindOfClass:[LeagueDetailVC class]])
        return (interfaceOrientation == UIInterfaceOrientationLandscapeLeft);
    else
        return (interfaceOrientation == UIInterfaceOrientationPortrait);
    
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
    
}
-(BOOL)shouldAutorotate
{
    return [self.topViewController supportedInterfaceOrientations];
}
-(NSUInteger)supportedInterfaceOrientations
{
    NSLog(@"%lu",(unsigned long)[self.topViewController supportedInterfaceOrientations]);
    return [self.topViewController supportedInterfaceOrientations];
}
- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    return [self.topViewController preferredInterfaceOrientationForPresentation];
}

//-(BOOL)shouldAutorotate
//{
//    
//    if ([[self visibleViewController] isKindOfClass:[LeagueDetailVC class]])
//        return YES;
//    else
//        return NO;
//    return [[self.viewControllers lastObject] shouldAutorotate];
//}
//
//-(NSUInteger)supportedInterfaceOrientations
//{
//    if ([[self visibleViewController] isKindOfClass:[LeagueDetailVC class]])
//        return UIInterfaceOrientationMaskLandscape;
//    else
//        return UIInterfaceOrientationMaskPortrait;
//    return [[self.viewControllers lastObject] supportedInterfaceOrientations];
//}
//
//- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
//{
//    if ([[self visibleViewController] isKindOfClass:[LeagueDetailVC class]])
//        return UIInterfaceOrientationLandscapeLeft;
//    else
//        return UIInterfaceOrientationPortrait;
//    return [[self.viewControllers lastObject] preferredInterfaceOrientationForPresentation];
//}
//- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
//    
//    if ([[self visibleViewController] isKindOfClass:[LeagueDetailVC class]])
//        return (interfaceOrientation == UIInterfaceOrientationLandscapeLeft);
//    else
//        return (interfaceOrientation == UIInterfaceOrientationPortrait);
//    
//    return (interfaceOrientation == UIInterfaceOrientationPortrait);
//
//}

//-(BOOL)shouldAutorotate
//{
//    return [self.topViewController shouldAutorotate];
//}
//
//-(NSUInteger)supportedInterfaceOrientations
//{
//    return [self.topViewController supportedInterfaceOrientations];
//}
//
//- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
//{
//    return [self.topViewController preferredInterfaceOrientationForPresentation];
//}


//-(BOOL)shouldAutorotate
//{
//    return [[self.viewControllers lastObject] shouldAutorotate];
//}
//
//-(NSUInteger)supportedInterfaceOrientations
//{
//    return [[self.viewControllers lastObject] supportedInterfaceOrientations];
//}

@end
