//
//  VenueDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 07/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface VenueDetailVC : UIViewController
@property (strong, nonatomic) NSMutableDictionary *matchDetails;
@property (weak, nonatomic) IBOutlet UILabel *pitchName;
@property (weak, nonatomic) IBOutlet UILabel *address;
@property (weak, nonatomic) IBOutlet UILabel *playingSurface;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)viewOnMapBtnClick:(id)sender;

@end
