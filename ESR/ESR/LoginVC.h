//
//  LoginVC.h
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LoginVC : UIViewController<UITextFieldDelegate,UIScrollViewDelegate>
@property (weak, nonatomic) IBOutlet UIView *offlineView;

@property (weak, nonatomic) IBOutlet UITextField *emailTxtField;
@property (weak, nonatomic) IBOutlet UITextField *passwordTxtField;
@property (weak, nonatomic) IBOutlet UIButton *loginBtn;
@property (weak, nonatomic) IBOutlet UIView *alertView;
@property (weak, nonatomic) IBOutlet UILabel *alertViewTitle;
@property (weak, nonatomic) IBOutlet UILabel *alertViewSubtitle;
@property (weak, nonatomic) IBOutlet UIScrollView *scroll;
@property (weak, nonatomic) IBOutlet UIView *scrollSubView;
- (IBAction)loginBtnClick:(id)sender;
- (IBAction)forgotPasswordBtnClick:(id)sender;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)alertViewOkBtnClick:(id)sender;

@end
