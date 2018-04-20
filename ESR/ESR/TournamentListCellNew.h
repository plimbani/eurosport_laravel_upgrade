//
//  TournamentListCellNew.h
//  ESR
//
//  Created by Aecor Digital on 28/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TournamentListCellNew : UITableViewCell
@property (weak, nonatomic) IBOutlet UILabel *nameLbl;
@property (weak, nonatomic) IBOutlet UILabel *dateLbl;
@property (weak, nonatomic) IBOutlet UIImageView *image;
@property (weak, nonatomic) IBOutlet UIButton *faverateBtn;
@property (weak, nonatomic) IBOutlet UIButton *defaultBtn;

@end
