//
//  ClubsNewVC.h
//  ESR
//
//  Created by Aecor Digital on 06/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ClubsNewVC : UIViewController
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UIView *clubContainerView;
@property (weak, nonatomic) IBOutlet UIView *ageContainerView;
@property (weak, nonatomic) IBOutlet UIView *GroupContainerView;
@property (weak, nonatomic) IBOutlet UILabel *clubLbl;
@property (weak, nonatomic) IBOutlet UILabel *groupLbl;
@property (weak, nonatomic) IBOutlet UILabel *ageLbl;
@property (weak, nonatomic) IBOutlet UIButton *ageBtn;
@property (weak, nonatomic) IBOutlet UIButton *groupBtn;
@property (weak, nonatomic) IBOutlet UIButton *clubBtn;
@property (weak, nonatomic) IBOutlet UILabel *teamLbl;
- (IBAction)clubBtnClick:(id)sender;
- (IBAction)ageBtnClick:(id)sender;
- (IBAction)groupBtnClick:(id)sender;

@end
