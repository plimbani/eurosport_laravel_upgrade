//
//  UpdateProfileVC.h
//  ESR
//
//  Created by Aecor Digital on 27/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UpdateProfileVC : UIViewController<UITextFieldDelegate,UIScrollViewDelegate,UIActionSheetDelegate,UIImagePickerControllerDelegate,UINavigationControllerDelegate>{
    
}
@property (weak, nonatomic) NSString *selectedTournamentIndex;
@property (weak, nonatomic) IBOutlet UIImageView *profileImage;
@property (weak, nonatomic) IBOutlet UITextField *firstNameTxtField;
@property (weak, nonatomic) IBOutlet UITextField *surnameTxtField;
@property (weak, nonatomic) IBOutlet UITextField *emailTxtField;
@property (weak, nonatomic) IBOutlet UITextField *passwordTxtField;
@property (weak, nonatomic) IBOutlet UITextField *TournamentTxtField;
@property (weak, nonatomic) IBOutlet UITextField *languageTextField;
@property (weak, nonatomic) IBOutlet UIScrollView *scroll;
@property (weak, nonatomic) IBOutlet UIButton *signUpBtn;
@property (weak, nonatomic) IBOutlet UIView *alertView;
@property (weak, nonatomic) IBOutlet UILabel *alertViewTitle;
@property (weak, nonatomic) IBOutlet UILabel *alertViewSubTitle;
- (IBAction)signUpBtnClick:(id)sender;
- (IBAction)tournamentBtnClick:(id)sender;
- (IBAction)languageBtnClick:(id)sender;
- (IBAction)cameraBtnClick:(id)sender;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)alertViewOkBtnClick:(id)sender;

@end
