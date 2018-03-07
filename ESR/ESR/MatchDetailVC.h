//
//  MatchDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MatchDetailVC : UIViewController<UIGestureRecognizerDelegate>
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (strong, nonatomic) NSMutableDictionary *matchDetails;
@property (weak, nonatomic) IBOutlet UIView *mainView;
@property (weak, nonatomic) IBOutlet UILabel *homeTeamScore;
@property (weak, nonatomic) IBOutlet UILabel *awayTeamScore;
@property (weak, nonatomic) IBOutlet UITextView *awayTeamNameTextField;
@property (weak, nonatomic) IBOutlet UITextView *homeTeamNameTextField;
@property (weak, nonatomic) IBOutlet UILabel *homeTeamName;
@property (weak, nonatomic) IBOutlet UILabel *awayTeamName;
@property (weak, nonatomic) IBOutlet UILabel *homeTeamCountry;
@property (weak, nonatomic) IBOutlet UILabel *awayTeamCountry;
@property (weak, nonatomic) IBOutlet UIImageView *homeTeamImage;
@property (weak, nonatomic) IBOutlet UIImageView *awayTeamImage;
@property (weak, nonatomic) IBOutlet UILabel *dateTime;
@property (weak, nonatomic) IBOutlet UILabel *groupName;
@property (weak, nonatomic) IBOutlet UILabel *matchId;
@property (weak, nonatomic) IBOutlet UIButton *venueBtn;
@property (weak, nonatomic) IBOutlet UILabel *winnerLbl;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (weak, nonatomic) IBOutlet UILabel *referreLbl;
@property (weak, nonatomic) IBOutlet UILabel *positionLbl;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *winnerLblTopConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *bottomViewTopConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *mainViewHeightConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *homeTeamImageWidthConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *homeTeamHeightConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *awayTeamHeightConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *awayTeamWidthConstrain;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *middleTeamDetailViewHeightConstrain;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)venueBtnClick:(id)sender;

@end
