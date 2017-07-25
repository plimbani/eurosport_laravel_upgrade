//
//  FixturesCell.m
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "FixturesCell.h"

@implementation FixturesCell

- (void)awakeFromNib {
    [super awakeFromNib];
    // Initialization code
    
    //self.profileImage.layer.cornerRadius = 50;
    self.dateTimeview.layer.cornerRadius = 30;
    self.dateTimeview.layer.masksToBounds = true;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
