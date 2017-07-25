//
//  TournamentDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
@class MBCircularProgressBarView;

@interface TournamentDetailVC : UIViewController{
    NSTimer *timer;
}
@property (weak, nonatomic) IBOutlet UIView *tournamentView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *dayView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *hourView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *minuteView;
@property (weak, nonatomic) IBOutlet MBCircularProgressBarView *secondView;
@property (weak, nonatomic) IBOutlet UILabel *dateLbl;
@property (weak, nonatomic) IBOutlet UITextField *changeTournamentTxtField;
@property (weak, nonatomic) IBOutlet UIImageView *tournamentImage;
@property (weak, nonatomic) IBOutlet UILabel *tournamentName;
- (IBAction)backBtnClick:(id)sender;
- (IBAction)facebookBtnClick:(id)sender;
- (IBAction)instagramBtnClick:(id)sender;
- (IBAction)twitterBtnClick:(id)sender;
- (IBAction)changeTournamentBnClick:(id)sender;

@end
