//
//  PrivacyVC.h
//  ESR
//
//  Created by sanjay on 02/08/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface PrivacyVC : UIViewController<UIGestureRecognizerDelegate>
- (IBAction)backBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (strong, nonatomic) IBOutlet UITextView *txtView;

@end
