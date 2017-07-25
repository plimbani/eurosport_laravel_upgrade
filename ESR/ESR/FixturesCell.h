//
//  FixturesCell.h
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface FixturesCell : UITableViewCell
@property (weak, nonatomic) IBOutlet UILabel *HomeTeam;
@property (weak, nonatomic) IBOutlet UILabel *AwayTeam;
@property (weak, nonatomic) IBOutlet UILabel *venue_name;
@property (weak, nonatomic) IBOutlet UILabel *date;
@property (weak, nonatomic) IBOutlet UILabel *month;
@property (weak, nonatomic) IBOutlet UIView *dateTimeview;
@property (weak, nonatomic) IBOutlet UILabel *matchIDRound;

@end
