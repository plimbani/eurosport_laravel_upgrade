//
//  GroupSummaryHeaderCell.h
//  ESR
//
//  Created by GBarot on 27/09/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GroupSummaryHeaderCell : UITableViewCell
@property (strong, nonatomic) IBOutlet UIButton *dropDownBtn;
@property (strong, nonatomic) IBOutlet UITextField *selectedGroupTxtField;
@property (strong, nonatomic) IBOutlet UIView *standingView;
@property (strong, nonatomic) IBOutlet UIView *matchView;
@property (strong, nonatomic) IBOutlet UIView *standingSeperaterView;
@property (strong, nonatomic) IBOutlet UIView *matchSeperaterView;
@property (strong, nonatomic) IBOutlet UIButton *stendingBtn;
@property (strong, nonatomic) IBOutlet UIButton *matchBtn;


@end
