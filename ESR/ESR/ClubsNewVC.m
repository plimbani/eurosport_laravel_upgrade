//
//  ClubsNewVC.m
//  ESR
//
//  Created by Aecor Digital on 06/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "ClubsNewVC.h"
#import "UIColor+fromHex.h"
#import "Utils.h"
#import "Reachability.h"

@interface ClubsNewVC ()

@end

@implementation ClubsNewVC

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
-(void)viewWillAppear:(BOOL)animated{
    self.teamLbl.text = NSLocalizedString(@"TEAMS", @"");
    [self.ageBtn setTitle:NSLocalizedString(@"CATEGORY", @"") forState:UIControlStateNormal];
    [self.clubBtn setTitle:NSLocalizedString(@"CLUBS", @"") forState:UIControlStateNormal];
    [self.groupBtn setTitle:NSLocalizedString(@"GROUPS", @"") forState:UIControlStateNormal];
    self.clubContainerView.hidden = FALSE;
    self.ageContainerView.hidden = TRUE;
    self.GroupContainerView.hidden = TRUE;
    self.clubLbl.backgroundColor = [UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    self.ageLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.groupLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    
    self.clubBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica-Bold" size:15.0];
    self.ageBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
    self.groupBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

- (IBAction)clubBtnClick:(id)sender {
    self.clubContainerView.hidden = FALSE;
    self.ageContainerView.hidden = TRUE;
    self.GroupContainerView.hidden = TRUE;
    self.clubLbl.backgroundColor = [UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    self.ageLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.groupLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    
    self.clubBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica-Bold" size:15.0];
    self.ageBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
    self.groupBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
}

- (IBAction)ageBtnClick:(id)sender {
    self.clubContainerView.hidden = TRUE;
    self.ageContainerView.hidden = FALSE;
    self.GroupContainerView.hidden = TRUE;
    self.clubLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.ageLbl.backgroundColor = [UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    self.groupLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.clubBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
    self.ageBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica-Bold" size:15.0];
    self.groupBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
}

- (IBAction)groupBtnClick:(id)sender {
    self.clubContainerView.hidden = TRUE;
    self.ageContainerView.hidden = TRUE;
    self.GroupContainerView.hidden = false;
    self.clubLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.ageLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.groupLbl.backgroundColor = [UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    self.clubBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
    self.ageBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica" size:15.0];
    self.groupBtn.titleLabel.font = [UIFont fontWithName:@"Helvetica-Bold" size:15.0];
}
@end
