//
//  UINavigationController+Rotation_IOS6.h
//  ESR
//
//  Created by Aecor Digital on 04/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UINavigationController (Rotation_IOS6)
-(BOOL)shouldAutorotate;
-(NSUInteger)supportedInterfaceOrientations;
- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation;
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation ;
@end
