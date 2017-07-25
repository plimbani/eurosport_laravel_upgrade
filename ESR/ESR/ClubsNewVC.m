//
//  ClubsNewVC.m
//  ESR
//
//  Created by Aecor Digital on 06/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "ClubsNewVC.h"
#import "UIColor+fromHex.h"

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
}

- (IBAction)ageBtnClick:(id)sender {
    self.clubContainerView.hidden = TRUE;
    self.ageContainerView.hidden = FALSE;
    self.GroupContainerView.hidden = TRUE;
    self.clubLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.ageLbl.backgroundColor = [UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    self.groupLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
}

- (IBAction)groupBtnClick:(id)sender {
    self.clubContainerView.hidden = TRUE;
    self.ageContainerView.hidden = TRUE;
    self.GroupContainerView.hidden = false;
    self.clubLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.ageLbl.backgroundColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
    self.groupLbl.backgroundColor = [UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
}
@end
