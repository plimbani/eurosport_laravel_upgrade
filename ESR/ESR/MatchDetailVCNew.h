//
//  MatchDetailVCNew.h
//  ESR
//
//  Created by GBarot on 25/09/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MatchDetailVCNew : UIViewController<UIGestureRecognizerDelegate>
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UIStackView *stackviewTop;
@property (weak, nonatomic) IBOutlet UIStackView *stackviewMiddle;
// Team 1
@property (weak, nonatomic) IBOutlet UILabel *lblTeam1Score;
@property (weak, nonatomic) IBOutlet UILabel *lblTeam1Name;
@property (weak, nonatomic) IBOutlet UIImageView *imgViewTeam1Flag;
@property (weak, nonatomic) IBOutlet UIImageView *imgViewTeam1Tshirt;
@property (weak, nonatomic) IBOutlet UIImageView *imgViewTeam1Short;
// Team 2
@property (weak, nonatomic) IBOutlet UILabel *lblTeam2Score;
@property (weak, nonatomic) IBOutlet UILabel *lblTeam2Name;
@property (weak, nonatomic) IBOutlet UIImageView *imgViewTeam2Flag;
@property (weak, nonatomic) IBOutlet UIImageView *imgViewTeam2Tshirt;
@property (weak, nonatomic) IBOutlet UIImageView *imgViewTeam2Short;

@property (weak, nonatomic) IBOutlet UILabel *lblDate;
@property (weak, nonatomic) IBOutlet UILabel *lblTime;
@property (weak, nonatomic) IBOutlet UILabel *lblRefereeName;
@property (weak, nonatomic) IBOutlet UILabel *lblAgeNGroupInfo;
@property (weak, nonatomic) IBOutlet UILabel *lblMatchId;
@property (weak, nonatomic) IBOutlet UILabel *lblPlacing;

@property (weak, nonatomic) IBOutlet UIView *viewVenue;
@property (weak, nonatomic) IBOutlet UILabel *lblVenue;

@property (weak, nonatomic) IBOutlet UIView *viewWinnerStatus;
@property (weak, nonatomic) IBOutlet UILabel *lblWinnerStatus;
@property (strong, nonatomic) NSMutableDictionary *matchDetails;

@property (weak, nonatomic) IBOutlet NSLayoutConstraint *heightConstraintViewWinnerStatus;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *heightConstraintDateSeparator;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *scrollViewTopConstrain;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)venueBtnClick:(id)sender;
@end
