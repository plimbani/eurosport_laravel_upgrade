//
//  NotificationSoundVC.h
//  ESR
//
//  Created by Aecor Digital on 10/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface NotificationSoundVC : UIViewController<UIGestureRecognizerDelegate>{
    NSString *sound;
    NSString *vibration;
    NSString *notification;
}
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (weak, nonatomic) IBOutlet UISwitch *soundSwitch;
@property (weak, nonatomic) IBOutlet UISwitch *vibrationSwitch;
@property (weak, nonatomic) IBOutlet UISwitch *notificationSwitch;
- (IBAction)soundValueChange:(id)sender;
- (IBAction)vibrationValueChange:(id)sender;
- (IBAction)notificationValueChange:(id)sender;
- (IBAction)backBtnClick:(id)sender;

@end
