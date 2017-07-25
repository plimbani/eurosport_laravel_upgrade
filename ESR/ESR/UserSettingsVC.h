//
//  UserSettingsVC.h
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UserSettingsVC : UIViewController
- (IBAction)helpBtnClick:(id)sender;
- (IBAction)privacyBtnClick:(id)sender;
- (IBAction)profileBtnClikc:(id)sender;
- (IBAction)notificationBtnClick:(id)sender;
- (IBAction)logoutBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UIView *alertView;
@property (weak, nonatomic) IBOutlet UILabel *alertViewTitle;
@property (weak, nonatomic) IBOutlet UILabel *alertViewSubTitle;
- (IBAction)alertViewOkBtnClick:(id)sender;
- (IBAction)alertViewCancelBtnClick:(id)sender;

@end
