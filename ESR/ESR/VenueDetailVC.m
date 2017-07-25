//
//  VenueDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 07/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "VenueDetailVC.h"
#import "MapVC.h"

@interface VenueDetailVC ()

@end

@implementation VenueDetailVC
@synthesize matchDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    self.pitchName.text = [matchDetails valueForKey:@"pitch_number"];
    self.address.text = [NSString stringWithFormat:@"%@, %@, %@, %@, %@",[matchDetails valueForKey:@"venueaddress"],[matchDetails valueForKey:@"venueCity"],[matchDetails valueForKey:@"venueCountry"],[matchDetails valueForKey:@"venueState"],[matchDetails valueForKey:@"venuePostcode"]];
    self.playingSurface.text = [matchDetails valueForKey:@"pitchType"];
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

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)viewOnMapBtnClick:(id)sender {
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    MapVC *myVC = (MapVC *)[storyboard instantiateViewControllerWithIdentifier:@"MapVC"];
    myVC.matchDetails = matchDetails;
    [self.navigationController pushViewController:myVC animated:YES];
}
@end
