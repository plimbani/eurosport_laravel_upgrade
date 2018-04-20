//
//  VenueDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 07/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface VenueDetailVC : UIViewController<UIGestureRecognizerDelegate>
@property (strong, nonatomic) NSMutableDictionary *matchDetails;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UILabel *pitchName;
@property (weak, nonatomic) IBOutlet UILabel *address;
@property (weak, nonatomic) IBOutlet UILabel *playingSurface;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (weak, nonatomic) IBOutlet UIView *bottomMapView;
@property (weak, nonatomic) IBOutlet UILabel *location;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)viewOnMapBtnClick:(id)sender;

@end
