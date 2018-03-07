//
//  ForgotPasswordVC.h
//  ESR
//
//  Created by Aecor Digital on 22/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ForgotPasswordVC : UIViewController<UITextFieldDelegate,UIScrollViewDelegate,UIGestureRecognizerDelegate>{
    UITapGestureRecognizer *tap;
    UITapGestureRecognizer *tap1;
}
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UIScrollView *scroll1;
@property (weak, nonatomic) IBOutlet UIScrollView *scroll2;
@property (weak, nonatomic) IBOutlet UIButton *scroll1GetOTPBtn;
@property (weak, nonatomic) IBOutlet UITextField *scroll1EmailTxt;
@property (weak, nonatomic) IBOutlet UITextField *scroll2EmailTxt;
@property (weak, nonatomic) IBOutlet UITextField *scroll2OPTTxt;
@property (weak, nonatomic) IBOutlet UITextField *scroll2PasswordTxt;
@property (weak, nonatomic) IBOutlet UIView *scrollSubView;
- (IBAction)scroll1GetOTPBtnClick:(id)sender;
- (IBAction)scroll2ChangePasswordBtnClick:(id)sender;
- (IBAction)scroll1BackBtnClick:(id)sender;
- (IBAction)scroll2BackBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UIView *alertView;
@property (weak, nonatomic) IBOutlet UILabel *alertViewTitle;
@property (weak, nonatomic) IBOutlet UILabel *alertViewSubLbl;
- (IBAction)alertViewOkBtnClick:(id)sender;

@end
