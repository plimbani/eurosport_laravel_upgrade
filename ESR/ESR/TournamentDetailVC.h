//
//  TournamentDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
@class MBCircularProgressBarView;

@interface TournamentDetailVC : UIViewController<UITableViewDataSource,UITableViewDelegate,UIGestureRecognizerDelegate,UIPickerViewDataSource, UIPickerViewDelegate>{
    NSTimer *timer;
}
@property (weak, nonatomic) IBOutlet UIView *contactDetailAlertView;
@property (weak, nonatomic) IBOutlet UILabel *nameLbl;
@property (weak, nonatomic) IBOutlet UILabel *contactLbl;
- (IBAction)closeBtnClick:(id)sender;
- (IBAction)contactBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UIView *offlineView;

@property (weak, nonatomic) IBOutlet UIView *pickerView;
@property (weak, nonatomic) IBOutlet UIPickerView *picker;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *tableHeight;
@property (weak, nonatomic) IBOutlet UIView *scrollSubView;
@property (strong, nonatomic) NSMutableArray *autoCompleteArray;
@property (strong, nonatomic) IBOutlet UITableView *autoCompleteTableView;
@property (weak, nonatomic) IBOutlet UIView *tournamentView;
@property (weak, nonatomic) IBOutlet UILabel *dayLbl;
@property (weak, nonatomic) IBOutlet UILabel *hoursLbl;
@property (weak, nonatomic) IBOutlet UILabel *minutesLbl;
@property (weak, nonatomic) IBOutlet UILabel *secondLbl;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *dayView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *hourView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *minuteView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *secondView;
@property (weak, nonatomic) IBOutlet UILabel *dateLbl;
@property (weak, nonatomic) IBOutlet UITextField *changeTournamentTxtField;
@property (weak, nonatomic) IBOutlet UIImageView *tournamentImage;
@property (weak, nonatomic) IBOutlet UILabel *tournamentName;
@property (weak, nonatomic) IBOutlet UIButton *teamsBtn;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)facebookBtnClick:(id)sender;
- (IBAction)instagramBtnClick:(id)sender;
- (IBAction)twitterBtnClick:(id)sender;
- (IBAction)changeTournamentBnClick:(id)sender;
- (IBAction)pickerOkBtnClick:(id)sender;
- (IBAction)pickerCancelBtnClick:(id)sender;
- (IBAction)teamBtnClick:(id)sender;
- (IBAction)finalPlacingBtnClick:(id)sender;

@end
