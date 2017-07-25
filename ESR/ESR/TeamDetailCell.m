//
//  TeamDetailCell.m
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "TeamDetailCell.h"

@implementation TeamDetailCell

- (void)awakeFromNib {
    [super awakeFromNib];
    // Initialization code
    self.view.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.view.layer.borderWidth = 2.0f;
    
    self.view.layer.cornerRadius = 15;
    self.view.layer.masksToBounds = true;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
