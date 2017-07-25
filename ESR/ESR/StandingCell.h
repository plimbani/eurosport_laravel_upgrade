//
//  StandingCell.h
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface StandingCell : UITableViewCell
@property (weak, nonatomic) IBOutlet UIImageView *image;
@property (weak, nonatomic) IBOutlet UILabel *name;
@property (weak, nonatomic) IBOutlet UILabel *played;
@property (weak, nonatomic) IBOutlet UILabel *plusMinus;
@property (weak, nonatomic) IBOutlet UILabel *points;

@end
