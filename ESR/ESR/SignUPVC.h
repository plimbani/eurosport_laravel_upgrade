//
//  SignUPVC.h
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SignUPVC : UIViewController<UITextFieldDelegate,UIScrollViewDelegate,UITableViewDataSource,UITableViewDelegate,UIGestureRecognizerDelegate,UIPickerViewDataSource, UIPickerViewDelegate>{
    CAGradientLayer *maskLayer;
    UITapGestureRecognizer *tap;
    UITapGestureRecognizer *tap1;
}
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UIPickerView *pickerView;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *tableHeight;
@property (weak, nonatomic) IBOutlet UIImageView *imageView;
@property (strong, nonatomic) NSMutableArray *autoCompleteArray;
@property (strong, nonatomic) IBOutlet UITableView *autoCompleteTableView;
@property (weak, nonatomic) NSString *selectedTournamentIndex;
@property (weak, nonatomic) IBOutlet UITextField *firstNameTxtField;
@property (weak, nonatomic) IBOutlet UITextField *surnameTxtField;
@property (weak, nonatomic) IBOutlet UITextField *emailTxtField;
@property (weak, nonatomic) IBOutlet UITextField *passwordTxtField;
@property (weak, nonatomic) IBOutlet UITextField *confirmTxtField;
@property (weak, nonatomic) IBOutlet UITextField *TournamentTxtField;
@property (weak, nonatomic) IBOutlet UIScrollView *scroll;
@property (weak, nonatomic) IBOutlet UIView *scrollSubView;
@property (weak, nonatomic) IBOutlet UIButton *signUpBtn;
- (IBAction)signUpBtnClick:(id)sender;
- (IBAction)tournamentBtnClick:(id)sender;
- (IBAction)backBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UIView *alertView;
@property (weak, nonatomic) IBOutlet UIView *passwordAlertView;
@property (weak, nonatomic) IBOutlet UILabel *passwordAlertTitle;
@property (weak, nonatomic) IBOutlet UILabel *passwordAlertSubTitle;
@property (weak, nonatomic) IBOutlet UILabel *alertTitle;
- (IBAction)passwordAlertOkBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UILabel *alertSubTitle;
- (IBAction)alertOkBtnClick:(id)sender;

@end
